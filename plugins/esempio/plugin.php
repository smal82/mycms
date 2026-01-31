<?php
class EsempioPlugin {
    private $cms;
    
    public function __construct($cms) {
        $this->cms = $cms;
    }
    
    // Hook eseguito prima del routing
    public function hook_before_route($data) {
        // Es: logging, redirect, etc.
        return $data;
    }
    
    // Hook eseguito prima del render
    public function hook_before_render($content) {
        // Es: modifica contenuto
        if (isset($content['content'])) {
            $content['content'] = str_replace('[data]', date('d/m/Y'), $content['content']);
        }
        return $content;
    }
    
    // Hook eseguito dopo il render
    public function hook_after_render($data) {
        // Es: analytics, cleanup
        return $data;
    }
}
?>