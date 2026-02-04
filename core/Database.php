<?php
require_once __DIR__ . '/Database/DatabaseConnectionTrait.php';
require_once __DIR__ . '/Database/SettingsTrait.php';
require_once __DIR__ . '/Database/MediaTrait.php';
require_once __DIR__ . '/Database/CustomPostTypeTrait.php';
require_once __DIR__ . '/Database/PostMetaTrait.php';
require_once __DIR__ . '/Database/ContentTrait.php';
require_once __DIR__ . '/Database/PluginTrait.php';
require_once __DIR__ . '/Database/ThemeTrait.php';
require_once __DIR__ . '/Database/MenuTrait.php';
require_once __DIR__ . '/Database/WidgetTrait.php';
require_once __DIR__ . '/Database/PageTrait.php';
require_once __DIR__ . '/Database/PostTrait.php';
require_once __DIR__ . '/Database/Other.php';
require_once __DIR__ . '/Database/ImpostazioniTrait.php';


class Database {
    use DatabaseConnectionTrait;
    use SettingsTrait;
    use MediaTrait;
    use CustomPostTypeTrait;
    use PostMetaTrait;
    use ContentTrait;
    use PluginTrait;
    use ThemeTrait;
    use MenuTrait;
    use WidgetTrait;
    use PageTrait;
    use Posttrait;
    use OtherTrait;
    use ImpostazioniTrait;

    public function __construct() {
        $this->initDatabase();
    }
}
