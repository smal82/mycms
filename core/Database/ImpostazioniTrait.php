<?php
trait ImpostazioniTrait {
    
    // ===== GENERALI ===== (compatibile sitetitle/sitedescription esistenti)
    public function getImpostazioniGenerali() {
        return [
            'site_title' => $this->getSetting('site_title') ?: $this->getSetting('site_title', 'MyCMS'),
            'site_description' => $this->getSetting('site_description') ?: $this->getSetting('site_description', ''),
            'registrazioni_attive' => $this->getSetting('registrazioni_attive', '1'),
            'site_logo' => $this->getSetting('site_logo'),
            'site_favicon' => $this->getSetting('site_favicon')
        ];
    }

    // ===== LETTURA =====
    public function getImpostazioniLettura() {
        return [
            'posts_per_page' => (int)$this->getSetting('posts_per_page', 10),
            'search_engine_visibility' => $this->getSetting('search_engine_visibility', '0')
        ];
    }

    public function saveImpostazioniLettura($data) {
        $this->updateSetting('posts_per_page', (int)($data['posts_per_page'] ?? 10));
        $this->updateSetting('search_engine_visibility', isset($data['search_engine_visibility']) ? '1' : '0');
        return true;
    }

    // ===== PERMALINK =====
    public function getImpostazioniPermalink() {
        return [
            'permalink_structure' => $this->getSetting('permalink_structure', '/%slug%/')
        ];
    }
    
    public function getRobotsMeta() {
    return $this->getSetting('search_engine_visibility') === '0' 
        ? '<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">' 
        : '';
    }


    public function saveImpostazioniPermalink($data) {
        $this->updateSetting('permalink_structure', trim($data['permalink_structure'] ?? '/%slug%/'));
        return $this->updateHtaccess();
    }

    public function updateHtaccess() {
        $htaccess = "# BEGIN cmsmio\n";
        $htaccess .= "<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase /\n";
        $htaccess .= "RewriteRule ^index\\.php$ - [L]\n";
        $htaccess .= "RewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n";
        $htaccess .= "RewriteRule . /index.php [L]\n</IfModule>\n# END cmsmio\n";
        return file_put_contents('../.htaccess', $htaccess, LOCK_EX);
    }

    public function getHtaccessContent() {
        return file_exists('../.htaccess') ? file_get_contents('../.htaccess') : '# Genera salvando permalink';
    }
}
