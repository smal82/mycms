<?php
trait ThemeTrait {
    public function getAvailableThemes() {
        $themes = [];
        if (is_dir(THEME_PATH)) {
            $dirs = scandir(THEME_PATH);
            foreach ($dirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir(THEME_PATH . '/' . $dir)) {
                    $themes[] = $dir;
                }
            }
        }
        return $themes;
    }
}
