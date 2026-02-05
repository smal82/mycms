<?php
class ApiRouter {
    private $db;
    private $method;
    private $path;
    private $apiKey;
    
    public function __construct() {
        $this->db = new Database();
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Estrai il path dall'URI - METODO MIGLIORATO
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Rimuovi /api.php e /api/ dall'inizio
        $uri = preg_replace('#^/api\.php#', '', $uri);
        $uri = preg_replace('#^/api/#', '', $uri);
        $uri = preg_replace('#^/api$#', '', $uri);
        
        $this->path = trim($uri, '/');
        
        // Se il path è ancora vuoto, prova a vedere se c'è in PATH_INFO
        if (empty($this->path) && isset($_SERVER['PATH_INFO'])) {
            $this->path = trim($_SERVER['PATH_INFO'], '/');
        }
        
        // Verifica API Key (opzionale)
        $this->apiKey = $this->getApiKey();
    }
    
    private function getApiKey() {
        // Da header Authorization: Bearer YOUR_KEY
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        }
        // Da header X-API-Key
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            return $_SERVER['HTTP_X_API_KEY'];
        }
        // Da query string
        return $_GET['api_key'] ?? null;
    }
    
    private function validateApiKey() {
        if (!$this->apiKey) {
            return false;
        }
        
        // Verifica la chiave nel database
        try {
            $stmt = $this->db->pdo->prepare("
                SELECT id FROM " . DB_PREFIX . "api_keys 
                WHERE api_key = ? AND is_active = 1
            ");
            $stmt->execute([$this->apiKey]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function handleRequest() {
        // Se il path è vuoto, mostra le route disponibili
        if (empty($this->path)) {
            $this->sendResponse(200, [
                'message' => 'API is running',
'available_endpoints' => [
            'GET /api/health' => 'Health check - returns OK status',
            'GET /api/status' => 'Alias for health check',
            'GET /api/posts' => 'List all posts',
            'GET /api/posts/{id}' => 'Get single post by ID',
            'GET /api/posts/{slug}' => 'Get single post by slug',
            'GET /api/pages' => 'List all pages',
            'GET /api/pages/{id}' => 'Get single page',
            'GET /api/media' => 'List all media files',
            'GET /api/media/{filename}' => 'Get single media file info',
            'GET /api/analytics/stats' => 'Get site statistics',
            'GET /api/analytics/status' => 'Check analytics installation status',
            'POST /api/analytics/track' => 'Track a page visit',
            'POST /api/analytics/install' => 'Install analytics table'
        ]
            ]);
            return;
        }
        
        if ($this->path === 'health' || $this->path === 'status') {
            // Esegui task pending quando viene chiamato /api/status
            $cronStats = $this->executePendingCrons();
            
        $this->sendResponse(200, [
            'status' => 'ok',
            'message' => 'Service is running',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        return;
    }
        
        // Route pubbliche (senza autenticazione) - SOLO GET
        $publicRoutes = ['posts', 'pages', 'media', 'analytics'];
        
        // Se non è una route pubblica, verifica l'API key
$isPublic = false;

// Analytics è pubblico per GET e POST (per tracking)
if (strpos($this->path, 'analytics') === 0) {
    $isPublic = true;
} elseif ($this->method === 'GET') {
    // Altri endpoint pubblici solo per GET
    foreach ($publicRoutes as $route) {
        if (strpos($this->path, $route) === 0 || $this->path === $route) {
            $isPublic = true;
            break;
        }
    }
}

        
        if (!$isPublic && !$this->validateApiKey()) {
            $this->sendResponse(401, ['error' => 'Unauthorized', 'message' => 'Invalid or missing API key']);
            return;
        }
        
        // Routing
        $pathParts = explode('/', $this->path);
        $resource = $pathParts[0] ?? '';
        $id = $pathParts[1] ?? null;
        
        try {
            switch ($resource) {
                case 'posts':
                    require_once __DIR__ . '/Controllers/PostController.php';
                    $controller = new PostController($this->db);
                    $this->handleResource($controller, $id);
                    break;
                    
                case 'pages':
                    require_once __DIR__ . '/Controllers/PageController.php';
                    $controller = new PageController($this->db);
                    $this->handleResource($controller, $id);
                    break;
                    
                case 'media':
                    require_once __DIR__ . '/Controllers/MediaController.php';
                    $controller = new MediaController($this->db);
                    $this->handleResource($controller, $id);
                    break;
                    
                case 'analytics':
                    require_once __DIR__ . '/Controllers/AnalyticsController.php';
                    $controller = new AnalyticsController($this->db);
                    $this->handleAnalytics($controller, $pathParts);
                    break;
                    
                default:
                    $this->sendResponse(404, [
                        'error' => 'Not Found', 
                        'message' => 'Resource not found',
                        'requested_resource' => $resource,
                        'parsed_path' => $this->path
                    ]);
            }
        } catch (Exception $e) {
            $this->sendResponse(500, ['error' => 'Internal Server Error', 'message' => $e->getMessage()]);
        }
    }
    
    private function handleResource($controller, $id) {
    // SOLO LETTURA - Blocca tutti i metodi tranne GET
    if ($this->method !== 'GET') {
        $this->sendResponse(405, [
            'error' => 'Method Not Allowed',
            'message' => 'This API is read-only. Only GET requests are allowed.'
        ]);
        return;
    }
    
    // Gestisce solo GET
    if ($id) {
        $result = $controller->getOne($id);
    } else {
        $result = $controller->getAll($_GET);
    }
    
    $this->sendResponse($result['status'], $result['data']);
}

    
    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
 * Gestisce le richieste analytics (permette POST per tracking)
 */
private function handleAnalytics($controller, $pathParts) {
    $action = $pathParts[1] ?? 'stats';
    
    // Analytics permette POST solo per track e install
    $allowedPostActions = ['track', 'install', 'uninstall'];
    
    if ($this->method === 'POST' && in_array($action, $allowedPostActions)) {
        // Permetti POST per queste azioni
        $result = $controller->$action();
        $this->sendResponse(200, $result);
        return;
    }
    
    if ($this->method === 'GET') {
        // Azioni GET
        switch ($action) {
            case 'stats':
                $result = $controller->stats();
                break;
            case 'status':
                $result = $controller->status();
                break;
            default:
                $this->sendResponse(404, [
                    'error' => 'Not Found',
                    'message' => 'Analytics action not found'
                ]);
                return;
        }
        $this->sendResponse(200, $result);
        return;
    }
    
    // Metodo non supportato
    $this->sendResponse(405, [
        'error' => 'Method Not Allowed',
        'message' => 'Method not allowed for this endpoint'
    ]);
}

/**
 * Esegue tutti i task cron pending
 */
private function executePendingCrons() {
    $prefix = DB_PREFIX;

    try {
        // Carica cron hooks
        if (file_exists(__DIR__ . '/../cron-hooks.php')) {
            require_once __DIR__ . '/../cron-hooks.php';
        }

        // Carica plugin
        $pluginsDir = __DIR__ . '/../../plugins';
        if (is_dir($pluginsDir)) {
            $pluginFolders = glob($pluginsDir . '/*', GLOB_ONLYDIR);
            foreach ($pluginFolders as $pluginFolder) {
                $pluginFile = $pluginFolder . '/plugin.php';
                if (file_exists($pluginFile)) {
                    require_once $pluginFile;

                    $pluginName = basename($pluginFolder);
                    $className = str_replace('-', '', ucwords($pluginName, '-')) . 'Plugin';
                    if (class_exists($className)) {
                        new $className($this->db);
                    }
                }
            }
        }

        // Task pending scaduti
        $stmt = $this->db->pdo->prepare("
            SELECT * 
            FROM {$prefix}scheduled_tasks
            WHERE status = 'pending'
              AND scheduled_at <= NOW()
            ORDER BY scheduled_at ASC
        ");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $results = [
            'total_pending' => count($tasks),
            'executed' => 0,
            'failed' => 0
        ];

        foreach ($tasks as $task) {

            // lock atomico
            $lock = $this->db->pdo->prepare("
                UPDATE {$prefix}scheduled_tasks
                SET status = 'running'
                WHERE id = ? AND status = 'pending'
            ");
            $lock->execute([$task['id']]);

            if ($lock->rowCount() === 0) {
                continue;
            }

            try {
                // esegui task reale
                $this->executeTask($task);

                // completed
                $this->db->pdo->prepare("
                    UPDATE {$prefix}scheduled_tasks
                    SET status = 'completed', executed_at = NOW()
                    WHERE id = ?
                ")->execute([$task['id']]);

                $results['executed']++;

            } catch (Exception $e) {

                $this->db->pdo->prepare("
                    UPDATE {$prefix}scheduled_tasks
                    SET status = 'failed',
                        error_message = ?,
                        executed_at = NOW()
                    WHERE id = ?
                ")->execute([$e->getMessage(), $task['id']]);

                $results['failed']++;
            }
        }

        return $results;

    } catch (Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage()
        ];
    }
}



/**
 * Esegue un singolo task
 */
private function executeTask($task) {
    $data = json_decode($task['task_data'], true);
    if (!$data) {
        throw new Exception('JSON invalido');
    }
    
    // Task built-in
    if ($task['task_type'] === 'publish_post') {
        $this->publishPost($data['post_id']);
        return;
    }
    
    if ($task['task_type'] === 'publish_page') {
        $this->publishPage($data['page_id']);
        return;
    }
    
    // Task custom (rss_import e qualsiasi altro registrato dai plugin)
    global $cron_task_handlers;
    if (isset($cron_task_handlers[$task['task_type']]) && 
        is_callable($cron_task_handlers[$task['task_type']])) {
        $cron_task_handlers[$task['task_type']]($data, $this->db);
        return;
    }
    
    throw new Exception("Task type non gestito: {$task['task_type']}");
}


private function publishPost($postId) {
    $prefix = DB_PREFIX;
    $stmt = $this->db->pdo->prepare("
        UPDATE {$prefix}posts 
        SET status = 'pubblicato' 
        WHERE id = ? AND status != 'pubblicato'
    ");
    $stmt->execute([$postId]);
}

private function publishPage($pageId) {
    $prefix = DB_PREFIX;
    $stmt = $this->db->pdo->prepare("
        UPDATE {$prefix}pages 
        SET status = 'pubblicato' 
        WHERE id = ? AND status != 'pubblicato'
    ");
    $stmt->execute([$pageId]);
}


}