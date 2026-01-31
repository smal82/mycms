<?php
/**
 * Classe per importare feed RSS
 */

class RSSImporter {
    private $db;
    private $prefix;
    
    public function __construct($db) {
        $this->db = $db;
        $this->prefix = DB_PREFIX;
    }
    
    /**
     * Importa elementi da un feed specifico
     */
    public function importaFeed($feedId) {
        try {
            // Recupera info feed
            $stmt = $this->db->pdo->prepare("
                SELECT * FROM {$this->prefix}rss_feeds 
                WHERE id = ? AND stato = 'attivo'
            ");
            $stmt->execute([$feedId]);
            $feed = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$feed) {
                throw new Exception("Feed non trovato o non attivo");
            }
            
            // Carica il feed RSS
            $rss = $this->caricaRSS($feed['url']);
            
            if (!$rss) {
                throw new Exception("Impossibile caricare il feed RSS da: " . $feed['url']);
            }
            
            // Verifica struttura del feed
            if (!isset($rss->channel)) {
                throw new Exception("Feed non valido: manca il tag 'channel'");
            }
            
            if (!isset($rss->channel->item)) {
                throw new Exception("Feed non valido: nessun elemento 'item' trovato");
            }
            
            $importati = 0;
            $totaleElementi = count($rss->channel->item);
            
            // Processa ogni elemento
            foreach ($rss->channel->item as $item) {
                if ($this->importaElemento($feedId, $item, $feed)) {
                    $importati++;
                }
            }
            
            // Aggiorna statistiche feed
            $this->aggiornaFeedImport($feedId, $importati, $feed['frequenza']);
            
            return $importati;
            
        } catch (Exception $e) {
            $this->aggiornaStatoFeed($feedId, 'errore', $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Carica un feed RSS
     */
    private function caricaRSS($url) {
        libxml_use_internal_errors(true);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'RSS Aggregator Plugin/1.0'
            ]
        ]);
        
        $content = @file_get_contents($url, false, $context);
        
        if ($content === false) {
            return false;
        }
        
        $rss = simplexml_load_string($content);
        
        if ($rss === false) {
            return false;
        }
        
        return $rss;
    }
    
    /**
 * Importa un singolo elemento
 */
private function importaElemento($feedId, $item, $feed) {
    // Estrai GUID (identificatore univoco)
    $guid = isset($item->guid) ? (string)$item->guid : (string)$item->link;
    
    if (empty($guid)) {
        return false;
    }
    
    // Controlla se già importato
    $stmt = $this->db->pdo->prepare("
        SELECT id FROM {$this->prefix}rss_elementi_importati 
        WHERE feed_id = ? AND guid = ?
    ");
    $stmt->execute([$feedId, $guid]);
    
    if ($stmt->fetch()) {
        return false; // Già importato
    }
    
    // Estrai dati
    $titolo = (string)$item->title;
    $link = (string)$item->link;
    $descrizione = isset($item->description) ? (string)$item->description : '';
    
    // Prova anche content:encoded (usato da molti feed)
    if (empty($descrizione) && isset($item->children('content', true)->encoded)) {
        $descrizione = (string)$item->children('content', true)->encoded;
    }
    
    $autore = isset($item->author) ? (string)$item->author : null;
    if (empty($autore) && isset($item->children('dc', true)->creator)) {
        $autore = (string)$item->children('dc', true)->creator;
    }
    
    $pubDate = isset($item->pubDate) ? strtotime((string)$item->pubDate) : time();
    
    // Estrai immagine
    $immagineUrl = $this->estraiImmagine($item, $descrizione);
    
    // Crea excerpt (massimo 250 caratteri)
    $excerpt = $this->creaExcerpt($descrizione);
    
    // Crea slug
    $slug = $this->creaSlug($titolo);
    
    // Crea la news (SENZA contenuto)
    $newsId = $this->creaNews([
        'feed_id' => $feedId,
        'titolo' => $titolo,
        'slug' => $slug,
        'excerpt' => $excerpt,
        'link_originale' => $link,
        'immagine_url' => $immagineUrl,
        'autore_originale' => $autore,
        'data_pubblicazione' => date('Y-m-d H:i:s', $pubDate)
    ]);
    
    if ($newsId) {
        // Registra elemento importato
        $stmt = $this->db->pdo->prepare("
            INSERT INTO {$this->prefix}rss_elementi_importati 
            (feed_id, guid, news_id) VALUES (?, ?, ?)
        ");
        $stmt->execute([$feedId, $guid, $newsId]);
        
        return true;
    }
    
    return false;
}
    
    /**
     * Estrai immagine dal feed o dal contenuto HTML
     */
    private function estraiImmagine($item, $descrizione) {
        // Prova con media:content (namespace media)
        if (isset($item->children('media', true)->content)) {
            $media = $item->children('media', true)->content;
            if (isset($media->attributes()->url)) {
                return (string)$media->attributes()->url;
            }
        }
        
        // Prova con media:thumbnail
        if (isset($item->children('media', true)->thumbnail)) {
            $thumb = $item->children('media', true)->thumbnail;
            if (isset($thumb->attributes()->url)) {
                return (string)$thumb->attributes()->url;
            }
        }
        
        // Prova con enclosure
        if (isset($item->enclosure)) {
            $type = (string)$item->enclosure->attributes()->type;
            if (strpos($type, 'image') !== false) {
                return (string)$item->enclosure->attributes()->url;
            }
        }
        
        // Cerca nel contenuto HTML
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $descrizione, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    /**
 * Crea excerpt dalla descrizione
 */
private function creaExcerpt($descrizione) {
    // Rimuovi HTML
    $testo = strip_tags($descrizione);
    
    // Decodifica entità HTML
    $testo = html_entity_decode($testo, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Limita a 250 caratteri
    if (mb_strlen($testo) > 250) {
        $testo = mb_substr($testo, 0, 250) . '...';
    }
    
    return trim($testo);
}
    
    /**
     * Crea slug dal titolo
     */
    private function creaSlug($titolo) {
        // Converti in minuscolo
        $slug = mb_strtolower($titolo, 'UTF-8');
        
        // Sostituisci caratteri speciali
        $slug = str_replace(
            ['à', 'è', 'é', 'ì', 'ò', 'ù', 'á', 'í', 'ó', 'ú'],
            ['a', 'e', 'e', 'i', 'o', 'u', 'a', 'i', 'o', 'u'],
            $slug
        );
        
        // Rimuovi tutto tranne lettere, numeri e spazi
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        
        // Sostituisci spazi con trattini
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        
        // Rimuovi trattini all'inizio e alla fine
        $slug = trim($slug, '-');
        
        // Limita lunghezza
        $slug = substr($slug, 0, 100);
        
        // Verifica unicità
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugEsiste($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Verifica se uno slug esiste già
     */
    private function slugEsiste($slug) {
        $stmt = $this->db->pdo->prepare("
            SELECT id FROM {$this->prefix}rss_news 
            WHERE slug = ?
        ");
        $stmt->execute([$slug]);
        
        return $stmt->fetch() !== false;
    }
    
    /**
 * Crea una news nel database (SENZA contenuto)
 */
private function creaNews($dati) {
    try {
        $stmt = $this->db->pdo->prepare("
            INSERT INTO {$this->prefix}rss_news 
            (feed_id, titolo, slug, excerpt, link_originale, immagine_url, autore_originale, data_pubblicazione)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $dati['feed_id'],
            $dati['titolo'],
            $dati['slug'],
            $dati['excerpt'],
            $dati['link_originale'],
            $dati['immagine_url'],
            $dati['autore_originale'],
            $dati['data_pubblicazione']
        ]);
        
        return $this->db->pdo->lastInsertId();
        
    } catch (Exception $e) {
        error_log("Errore creazione news RSS: " . $e->getMessage());
        return false;
    }
}
    
    /**
     * Aggiorna statistiche dopo l'import
     */
    private function aggiornaFeedImport($feedId, $importati, $frequenza) {
        $prossimoImport = date('Y-m-d H:i:s', time() + $frequenza);
        
        $stmt = $this->db->pdo->prepare("
            UPDATE {$this->prefix}rss_feeds 
            SET ultimo_import = NOW(),
                prossimo_import = ?,
                elementi_importati = elementi_importati + ?,
                stato = 'attivo',
                messaggio_errore = NULL
            WHERE id = ?
        ");
        
        $stmt->execute([$prossimoImport, $importati, $feedId]);
    }
    
    /**
     * Aggiorna stato del feed
     */
    private function aggiornaStatoFeed($feedId, $stato, $messaggio = null) {
        $stmt = $this->db->pdo->prepare("
            UPDATE {$this->prefix}rss_feeds 
            SET stato = ?, messaggio_errore = ?
            WHERE id = ?
        ");
        
        $stmt->execute([$stato, $messaggio, $feedId]);
    }
}
?>
