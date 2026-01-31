<?php
class MediaController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAll($params = []) {
        try {
            $uploadsDir = __DIR__ . '/../../../uploads/';
            
            if (!is_dir($uploadsDir)) {
                return ['status' => 404, 'data' => ['error' => 'Uploads directory not found']];
            }
            
            // Estensioni immagine supportate
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            
            $files = scandir($uploadsDir);
            $media = [];
            
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                
                $filePath = $uploadsDir . $file;
                
                // Solo file (non directory)
                if (!is_file($filePath)) {
                    continue;
                }
                
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                
                // Solo immagini
                if (!in_array($extension, $allowedExtensions)) {
                    continue;
                }
                
                $fileInfo = [
                    'filename' => $file,
                    'url' => '/uploads/' . $file,
                    'full_url' => $this->getBaseUrl() . '/uploads/' . $file,
                    'size' => filesize($filePath),
                    'size_formatted' => $this->formatBytes(filesize($filePath)),
                    'extension' => $extension,
                    'mime_type' => mime_content_type($filePath),
                    'uploaded_at' => date('Y-m-d H:i:s', filemtime($filePath)),
                    'dimensions' => $this->getImageDimensions($filePath)
                ];
                
                $media[] = $fileInfo;
            }
            
            // Ordina per data (più recenti prima)
            usort($media, function($a, $b) {
                return strtotime($b['uploaded_at']) - strtotime($a['uploaded_at']);
            });
            
            // Paginazione
            $page = isset($params['page']) ? (int)$params['page'] : 1;
            $perPage = isset($params['per_page']) ? (int)$params['per_page'] : 20;
            $total = count($media);
            $totalPages = ceil($total / $perPage);
            $offset = ($page - 1) * $perPage;
            
            $paginatedMedia = array_slice($media, $offset, $perPage);
            
            return [
                'status' => 200,
                'data' => [
                    'media' => $paginatedMedia,
                    'pagination' => [
                        'total' => $total,
                        'page' => $page,
                        'per_page' => $perPage,
                        'total_pages' => $totalPages
                    ]
                ]
            ];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function getOne($filename) {
        try {
            $uploadsDir = __DIR__ . '/../../../uploads/';
            $filePath = $uploadsDir . basename($filename); // basename per sicurezza
            
            if (!file_exists($filePath) || !is_file($filePath)) {
                return ['status' => 404, 'data' => ['error' => 'File not found']];
            }
            
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            $fileInfo = [
                'filename' => basename($filename),
                'url' => '/uploads/' . basename($filename),
                'full_url' => $this->getBaseUrl() . '/uploads/' . basename($filename),
                'size' => filesize($filePath),
                'size_formatted' => $this->formatBytes(filesize($filePath)),
                'extension' => $extension,
                'mime_type' => mime_content_type($filePath),
                'uploaded_at' => date('Y-m-d H:i:s', filemtime($filePath)),
                'dimensions' => $this->getImageDimensions($filePath)
            ];
            
            return ['status' => 200, 'data' => $fileInfo];
        } catch (Exception $e) {
            return ['status' => 500, 'data' => ['error' => $e->getMessage()]];
        }
    }
    
    public function create($data) {
        return ['status' => 501, 'data' => ['error' => 'Upload not implemented via API. Use admin panel.']];
    }
    
    public function update($id, $data) {
        return ['status' => 501, 'data' => ['error' => 'Update not supported for media files']];
    }
    
    public function delete($filename) {
        return ['status' => 501, 'data' => ['error' => 'Delete not supported for media files']];
    }
    
    // Helper: ottieni URL base del sito
    private function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . '://' . $host;
    }
    
    // Helper: formatta bytes in formato leggibile
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    // Helper: ottieni dimensioni immagine
    private function getImageDimensions($filePath) {
        $imageInfo = @getimagesize($filePath);
        
        if ($imageInfo === false) {
            return null;
        }
        
        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'aspect_ratio' => round($imageInfo[0] / $imageInfo[1], 2)
        ];
    }
}
