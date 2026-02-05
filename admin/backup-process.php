<?php
/**
 * FILE: /admin/backup-process.php
 * Script di elaborazione backup
 */

// Previeni timeout e aumenta memoria
ini_set('max_execution_time', 600); // 10 minuti
ini_set('memory_limit', '512M');

// Disabilita output buffering per risposta progressiva
if (ob_get_level()) ob_end_clean();
header('Content-Type: text/plain');
header('X-Accel-Buffering: no'); // Nginx

require_once '../core/bootstrap.php';
require_once '../core/Admin.php';

// Avvia sessione se non già avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$admin = new Admin();

// Verifica autenticazione e ruolo amministratore
if (!$admin->user->isLoggedIn() || !$admin->user->hasRole(User::ROLE_ADMIN)) {
    sendProgress(['error' => 'Accesso non autorizzato']);
    exit;
}

// Verifica token CSRF
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    sendProgress(['error' => 'Token CSRF non valido']);
    exit;
}

// Funzione per inviare aggiornamenti al client
function sendProgress($data) {
    echo json_encode($data) . "\n";
    flush();
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
}

try {
    // Definisci directory
    $backupDir = BASE_PATH . '/backups/';
    $tempDir = $backupDir . 'temp/';
    
    // Crea directory se non esistono
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0755, true);
    }
    
    // Nome file backup con timestamp
    $timestamp = date('Y-m-d_His');
    $backupName = 'backup_' . $timestamp;
    $tempBackupDir = $tempDir . $backupName . '/';
    
    // Crea directory temporanea
    if (!is_dir($tempBackupDir)) {
        mkdir($tempBackupDir, 0755, true);
    }
    
    sendProgress(['progress' => 5, 'message' => 'Inizializzazione completata...']);
    
    // ============= FASE 1: BACKUP DATABASE =============
    sendProgress(['progress' => 10, 'message' => 'Esecuzione dump del database...']);
    
    $datasql = date('Y-m-d_H-i-s');
    $sqlFile = $tempBackupDir . 'database_' . $datasql . '.sql';
    $dumpSuccess = createDatabaseDump($admin->db->pdo, $sqlFile);
    
    if (!$dumpSuccess) {
        throw new Exception('Errore durante il dump del database');
    }
    
    sendProgress(['progress' => 30, 'message' => 'Dump database completato']);
    
    // ============= FASE 2: COPIA FILE CMS =============
    sendProgress(['progress' => 35, 'message' => 'Inizio copia file del CMS...']);
    
    $excludeDirs = ['backups', '.well-known']; // Directory da escludere
    $filesCopied = 0;
    
    // Conta totale file per calcolare progresso
    $totalFiles = countFiles(BASE_PATH, $excludeDirs);
    
    // Copia ricorsiva file
    copyDirectory(BASE_PATH, $tempBackupDir, $excludeDirs, $totalFiles, $filesCopied);
    
    sendProgress(['progress' => 80, 'message' => 'Copia file completata (' . $filesCopied . ' file copiati)']);
    
    // ============= FASE 3: CREAZIONE ZIP =============
    sendProgress(['progress' => 85, 'message' => 'Creazione archivio ZIP...']);
    
    $zipFile = $backupDir . $backupName . '.zip';
    
    if (!createZipArchive($tempBackupDir, $zipFile)) {
        throw new Exception('Errore durante la creazione del file ZIP');
    }
    
    sendProgress(['progress' => 95, 'message' => 'Archivio ZIP creato']);
    
    // ============= FASE 4: PULIZIA =============
    sendProgress(['progress' => 97, 'message' => 'Pulizia file temporanei...']);
    
    // Elimina directory temporanea
    deleteDirectory($tempBackupDir);
    
    // Pulizia backup vecchi secondo impostazioni
    cleanOldBackups($admin->db->pdo, $backupDir);
    
    sendProgress(['progress' => 100, 'message' => 'Backup completato!']);
    
    // Risposta finale
    $fileSize = filesize($zipFile);
    sendProgress([
        'completed' => true,
        'filename' => basename($zipFile),
        'size' => $fileSize,
        'message' => 'Backup completato con successo!'
    ]);
    
} catch (Exception $e) {
    sendProgress(['error' => $e->getMessage()]);
}

// ============= FUNZIONI =============

/**
 * Crea dump del database
 */
function createDatabaseDump($pdo, $outputFile) {
    try {
        $sql = "-- Backup Database MyCMS\n";
        $sql .= "-- Data: " . date('Y-m-d H:i:s') . "\n\n";
        
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET time_zone = \"+00:00\";\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = \"0\";\n\n";
        
        // FIX: Usa prepared statement invece di concatenazione
        $prefix = DB_PREFIX . '%';
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$prefix]);
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Resto della funzione invariato...
        foreach ($tables as $table) {
            // Struttura tabella
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $sql .= "\n\n-- Struttura tabella `$table`\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $row['Create Table'] . ";\n\n";
            
            // Dati tabella
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                $sql .= "-- Dump dati tabella `$table`\n";
                
                foreach ($rows as $row) {
                    $values = array_map(function($value) use ($pdo) {
                        return $value === null ? 'NULL' : $pdo->quote($value);
                    }, array_values($row));
                    
                    $sql .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                }
                
                $sql .= "\n";
            }
        }
        
        return file_put_contents($outputFile, $sql) !== false;
        
    } catch (Exception $e) {
        $errorMsg = "-- ERRORE: " . $e->getMessage() . "\n";
        file_put_contents($outputFile, $errorMsg, FILE_APPEND);
        return false;
    }
}


/**
 * Conta file totali
 */
function countFiles($dir, $excludeDirs = []) {
    $count = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        $path = $file->getPathname();
        $skip = false;
        
        foreach ($excludeDirs as $exclude) {
            if (strpos($path, DIRECTORY_SEPARATOR . $exclude . DIRECTORY_SEPARATOR) !== false ||
                strpos($path, DIRECTORY_SEPARATOR . $exclude) === strlen($path) - strlen($exclude) - 1) {
                $skip = true;
                break;
            }
        }
        
        // NUOVA RIGA: Non contare file .zip
        if (!$skip && $file->isFile() && strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'zip') {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Copia ricorsiva directory
 */
function copyDirectory($source, $dest, $excludeDirs = [], $totalFiles = 0, &$filesCopied = 0) {
    if (!is_dir($dest)) {
        mkdir($dest, 0755, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    $lastProgress = 0;
    
    foreach ($iterator as $item) {
        $sourcePath = $item->getPathname();
        $relativePath = substr($sourcePath, strlen($source));
        $destPath = $dest . $relativePath;
        
        // Verifica esclusioni directory
        $skip = false;
        foreach ($excludeDirs as $exclude) {
            if (strpos($sourcePath, DIRECTORY_SEPARATOR . $exclude . DIRECTORY_SEPARATOR) !== false ||
                strpos($sourcePath, DIRECTORY_SEPARATOR . $exclude) === strlen($sourcePath) - strlen($exclude) - 1) {
                $skip = true;
                break;
            }
        }
        
        if ($skip) {
            continue;
        }
        
        // NUOVA RIGA: Escludi tutti i file .zip
        if ($item->isFile() && strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) === 'zip') {
            continue;
        }
        
        if ($item->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }
        } else {
            copy($sourcePath, $destPath);
            $filesCopied++;
            
            // Aggiorna progresso ogni 50 file
            if ($totalFiles > 0 && $filesCopied % 50 === 0) {
                $progress = 35 + (int)(($filesCopied / $totalFiles) * 45);
                if ($progress > $lastProgress) {
                    sendProgress([
                        'progress' => $progress,
                        'message' => 'Copiati ' . $filesCopied . ' di ' . $totalFiles . ' file...'
                    ]);
                    $lastProgress = $progress;
                }
            }
        }
    }
}

/**
 * Crea archivio ZIP
 */
function createZipArchive($sourceDir, $zipFile) {
    $zip = new ZipArchive();
    
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return false;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        $filePath = $file->getPathname();
        $relativePath = substr($filePath, strlen($sourceDir));
        
        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    return $zip->close();
}

/**
 * Elimina directory ricorsivamente
 */
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }
    
    rmdir($dir);
}

/**
 * Pulisci backup vecchi secondo limite impostato
 */
function cleanOldBackups($pdo, $backupDir) {
    // Ottieni limite da settings
    $stmt = $pdo->prepare("SELECT setting_value FROM " . DB_PREFIX . "settings WHERE setting_key = 'backup_max_limit'");
    $stmt->execute();
    $maxBackups = intval($stmt->fetchColumn() ?: 5);
    
    // Lista backup
    $files = glob($backupDir . 'backup_*.zip');
    
    if (count($files) <= $maxBackups) {
        return;
    }
    
    // Ordina per data (pi첫 vecchi prima)
    usort($files, function($a, $b) {
        return filemtime($a) - filemtime($b);
    });
    
    // Elimina i pi첫 vecchi
    $toDelete = count($files) - $maxBackups;
    for ($i = 0; $i < $toDelete; $i++) {
        if (file_exists($files[$i])) {
            unlink($files[$i]);
        }
    }
}