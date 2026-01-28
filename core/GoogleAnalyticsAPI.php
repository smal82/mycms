<?php
class GoogleAnalyticsAPI {
    private $credentials;
    private $propertyId;
    private $accessToken;
    private $tokenExpiry;
    
    public function __construct($serviceAccountJson, $propertyId) {
        // ... costruttore
    }
    
    private function refreshAccessToken() {
        // ... metodo refresh
    }
    
    private function createJWT($payload) {
        // ... metodo JWT
    }
    
    private function base64UrlEncode($data) {
        // ... metodo encode
    }
    
    public function runReport($dateStart, $dateEnd, $metrics, $dimensions = []) {
        // ... metodo runReport
    }
    
    // AGGIUNGI QUESTI QUI:
    public function getVisitors($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['activeUsers', 'sessions', 'screenPageViews']
        );
    }
    
    public function getPageViews($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['screenPageViews'],
            ['date']
        );
    }
    
    public function getTopPages($days = 30, $limit = 10) {
        $data = $this->runReport(
            $days . 'daysAgo',
            'today',
            ['screenPageViews', 'activeUsers'],
            ['pagePath']
        );
        
        if (isset($data['rows']) && count($data['rows']) > $limit) {
            $data['rows'] = array_slice($data['rows'], 0, $limit);
        }
        
        return $data;
    }
    
    public function getTrafficSources($days = 30) {
        return $this->runReport(
            $days . 'daysAgo',
            'today',
            ['sessions'],
            ['sessionSource']
        );
    }
}
