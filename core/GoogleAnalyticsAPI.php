<?php
/**
 * Google Analytics Data API v1 (GA4)
 * Classe per recuperare dati da Google Analytics 4
 */
class GoogleAnalyticsAPI {
    private $credentials;
    private $propertyId;
    private $accessToken;
    private $tokenExpiry;
    
    public function __construct($serviceAccountJson, $propertyId) {
        $this->credentials = json_decode($serviceAccountJson, true);
        
        if (!$this->credentials) {
            throw new Exception('Credenziali JSON non valide');
        }
        
        // Pulisce il propertyId da spazi e rimuove "properties/" se presente
        $this->propertyId = trim($propertyId);
        if (strpos($this->propertyId, 'properties/') === 0) {
            $this->propertyId = substr($this->propertyId, 11);
        }
        
        $this->refreshAccessToken();
    }
    
    /**
     * Ottiene un access token OAuth2 usando JWT
     */
    private function refreshAccessToken() {
        $now = time();
        
        // Token valido per 1 ora
        if ($this->accessToken && $this->tokenExpiry > $now + 300) {
            return;
        }
        
        // Crea JWT per richiedere access token
        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $jwtClaim = json_encode([
            'iss' => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]);
        
        $jwtSignature = $this->createJWT($jwtHeader, $jwtClaim);
        
        // Richiedi access token
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwtSignature
            ])
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("Errore OAuth2: " . $response);
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            throw new Exception("Access token non ricevuto");
        }
        
        $this->accessToken = $data['access_token'];
        $this->tokenExpiry = $now + ($data['expires_in'] ?? 3600);
    }
    
    /**
     * Crea JWT firmato con RSA256
     */
    private function createJWT($header, $payload) {
    $segments = [
        $this->base64UrlEncode($header),
        $this->base64UrlEncode($payload)
    ];
    
    $signingInput = implode('.', $segments);
    
    $privateKey = openssl_pkey_get_private($this->credentials['private_key']);
    
    if (!$privateKey) {
        throw new Exception('Chiave privata non valida');
    }
    
    openssl_sign($signingInput, $signature, $privateKey, 'SHA256');
    // ❌ RIMUOVI QUESTA RIGA: openssl_free_key($privateKey);
    
    $segments[] = $this->base64UrlEncode($signature);
    
    return implode('.', $segments);
}

    
    /**
     * Base64 URL-safe encoding
     */
    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Esegue un report su Google Analytics Data API v1
     * 
     * @param string $dateStart Data inizio (es. "7daysAgo", "2024-01-01")
     * @param string $dateEnd Data fine (es. "today", "2024-01-31")
     * @param array $metrics Metriche da recuperare (es. ["screenPageViews", "activeUsers"])
     * @param array $dimensions Dimensioni da raggruppare (es. ["date", "pagePath"])
     * @return array Risultati del report
     */
    public function runReport($dateStart, $dateEnd, $metrics, $dimensions = []) {
        $this->refreshAccessToken();
        
        // Costruisci il body della richiesta
        $requestBody = [
            'dateRanges' => [
                ['startDate' => $dateStart, 'endDate' => $dateEnd]
            ],
            'metrics' => array_map(function($metric) {
                return ['name' => $metric];
            }, $metrics)
        ];
        
        if (!empty($dimensions)) {
            $requestBody['dimensions'] = array_map(function($dimension) {
                return ['name' => $dimension];
            }, $dimensions);
        }
        
        // Endpoint API
        $url = "https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport";
        
        // Esegui richiesta POST
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($requestBody)
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            // Log dell'errore per debug
            error_log("Google Analytics API Error: HTTP $httpCode - $response");
            throw new Exception("Errore API Google Analytics (HTTP $httpCode): " . substr($response, 0, 200));
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            throw new Exception("Risposta API non valida");
        }
        
        return $data;
    }
    
    /**
     * Ottiene il numero di visitatori
     */
    public function getVisitors($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['activeUsers', 'sessions', 'screenPageViews']
        );
    }
    
    /**
     * Ottiene le visualizzazioni di pagina giornaliere
     */
    public function getPageViews($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['screenPageViews'],
            ['date']
        );
    }
    
    /**
     * Ottiene le pagine più visualizzate
     */
    public function getTopPages($days = 30, $limit = 10) {
    $data = $this->runReport(
        $days . 'daysAgo',
        'today',
        ['screenPageViews', 'activeUsers'],  // ← Aggiungi activeUsers
        ['pagePath', 'pageTitle']
    );
    
    // Limita i risultati
    if (isset($data['rows']) && count($data['rows']) > $limit) {
        $data['rows'] = array_slice($data['rows'], 0, $limit);
    }
    
    return $data;
}
    
    /**
     * Ottiene le sorgenti di traffico
     */
    public function getTrafficSources($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['sessions'],
            ['sessionSource']
        );
    }
    
    /**
 * Ottiene le visite per paese
 */
public function getVisitorsByCountry($days = 30, $limit = 10) {
    $data = $this->runReport(
        $days . 'daysAgo',
        'today',
        ['activeUsers', 'sessions'],
        ['country']
    );
    
    // Limita i risultati
    if (isset($data['rows']) && count($data['rows']) > $limit) {
        $data['rows'] = array_slice($data['rows'], 0, $limit);
    }
    
    return $data;
}

/**
 * Ottiene le visite per città
 */
public function getVisitorsByCity($days = 30, $limit = 10) {
    $data = $this->runReport(
        $days . 'daysAgo',
        'today',
        ['activeUsers', 'sessions'],
        ['city', 'country']
    );
    
    // Limita i risultati
    if (isset($data['rows']) && count($data['rows']) > $limit) {
        $data['rows'] = array_slice($data['rows'], 0, $limit);
    }
    
    return $data;
}

}
?>
