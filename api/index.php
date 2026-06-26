<?php
// ============================================================
// Janjez-Socio - Core Backend Setup (PHP + MySQL)
// ============================================================
// This file contains the complete backend structure for:
// - User authentication (login, signup, session)
// - Dashboard & profile management
// - Settings & preferences
// - Action panel for all CTA requests
// - Dynamic user provisioning
// ============================================================

error_reporting(E_ALL);
ini_set('display_errors', '0');
session_start();
date_default_timezone_set('UTC');

// ============================================================
// 1. DATABASE CONFIGURATION
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'janjez_socio');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// ============================================================
// 2. DATABASE SCHEMA (auto-install if tables missing)
// ============================================================
function installSchema($pdo) {
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) UNIQUE NOT NULL,
            username VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            full_name VARCHAR(255),
            avatar_url VARCHAR(500) DEFAULT '/assets/default-avatar.png',
            bio TEXT,
            company VARCHAR(255),
            website VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            is_active BOOLEAN DEFAULT TRUE,
            role ENUM('user', 'admin') DEFAULT 'user'
        )",

        "user_settings" => "CREATE TABLE IF NOT EXISTS user_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNIQUE NOT NULL,
            theme VARCHAR(50) DEFAULT 'light',
            language VARCHAR(10) DEFAULT 'en',
            notifications_email BOOLEAN DEFAULT TRUE,
            notifications_inapp BOOLEAN DEFAULT TRUE,
            two_factor_enabled BOOLEAN DEFAULT FALSE,
            api_key VARCHAR(64) UNIQUE,
            default_platform VARCHAR(50) DEFAULT 'all',
            content_auto_approve BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",

        "sessions" => "CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            expires_at TIMESTAMP NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",

        "user_actions" => "CREATE TABLE IF NOT EXISTS user_actions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            action_type VARCHAR(100) NOT NULL,
            action_data JSON,
            status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
            result_data JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",

        "platform_connections" => "CREATE TABLE IF NOT EXISTS platform_connections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            platform VARCHAR(50) NOT NULL,
            access_token TEXT,
            refresh_token TEXT,
            token_expiry TIMESTAMP NULL,
            platform_user_id VARCHAR(255),
            platform_username VARCHAR(255),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY (user_id, platform)
        )",

        "generated_posts" => "CREATE TABLE IF NOT EXISTS generated_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            platform VARCHAR(50),
            content TEXT,
            media_urls JSON,
            caption TEXT,
            hashtags TEXT,
            campaign_goal VARCHAR(100),
            status VARCHAR(50) DEFAULT 'draft',
            scheduled_for TIMESTAMP NULL,
            published_at TIMESTAMP NULL,
            engagement_data JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];

    foreach ($tables as $sql) {
        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            // table might already exist with different structure - skip
        }
    }
}
installSchema($pdo);

// ============================================================
// 3. CORE USER FUNCTIONS
// ============================================================
class UserAuth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function signup($email, $username, $password, $fullName = '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email address'];
        }
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['success' => false, 'message' => 'Username must be 3-50 characters'];
        }
        if (strlen($password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email or username already exists'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->pdo->prepare("INSERT INTO users (email, username, password_hash, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $username, $hash, $fullName]);
        $userId = $this->pdo->lastInsertId();

        $stmt = $this->pdo->prepare("INSERT INTO user_settings (user_id) VALUES (?)");
        $stmt->execute([$userId]);

        return ['success' => true, 'user_id' => $userId, 'message' => 'Account created successfully'];
    }

    public function login($emailOrUsername, $password, $ip = null, $userAgent = null) {
        $stmt = $this->pdo->prepare("SELECT id, username, email, password_hash, role, is_active FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$emailOrUsername, $emailOrUsername]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is deactivated'];
        }

        $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);

        $sessionId = bin2hex(random_bytes(32));
        $stmt = $this->pdo->prepare("INSERT INTO sessions (id, user_id, ip_address, user_agent, expires_at) VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))");
        $stmt->execute([$sessionId, $user['id'], $ip, $userAgent]);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['session_id'] = $sessionId;

        return ['success' => true, 'user' => $user, 'session_id' => $sessionId];
    }

    public function logout() {
        if (isset($_SESSION['session_id'])) {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
            $stmt->execute([$_SESSION['session_id']]);
        }
        session_destroy();
        return ['success' => true];
    }

    public function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    public function getUserSettings($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM user_settings WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function updateUser($userId, $data) {
        $allowed = ['full_name', 'bio', 'company', 'website', 'avatar_url'];
        $updates = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        if (empty($updates)) {
            return ['success' => false, 'message' => 'No valid fields to update'];
        }
        $params[] = $userId;
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true];
    }

    public function updateSettings($userId, $data) {
        $allowed = ['theme', 'language', 'notifications_email', 'notifications_inapp', 'two_factor_enabled', 'default_platform', 'content_auto_approve'];
        $updates = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = ?";
                $params[] = $value;
            }
        }
        if (empty($updates)) {
            return ['success' => false, 'message' => 'No valid settings to update'];
        }
        $params[] = $userId;
        $sql = "UPDATE user_settings SET " . implode(', ', $updates) . " WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true];
    }
}

// ============================================================
// 4. ACTION PANEL - CORE DYNAMIC ACTION HANDLER
// ============================================================
class ActionPanel {
    private $pdo;
    private $userId;

    public function __construct($pdo, $userId) {
        $this->pdo = $pdo;
        $this->userId = $userId;
    }

    public function generatePost($data) {
        $platform = $data['platform'] ?? 'all';
        $campaignGoal = $data['campaign_goal'] ?? 'showcase';
        $productImages = $data['product_images'] ?? [];
        $brandColors = $data['brand_colors'] ?? [];
        $captionTone = $data['caption_tone'] ?? 'professional';

        $actionId = $this->logAction('generate_post', [
            'platform' => $platform,
            'campaign_goal' => $campaignGoal,
            'caption_tone' => $captionTone,
            'image_count' => count($productImages)
        ]);

        $generated = [
            'visuals' => [
                'url' => '/api/generated/' . uniqid() . '.png',
                'dimensions' => $platform === 'instagram' ? '1080x1080' : '1200x630'
            ],
            'caption' => "✨ {$campaignGoal} campaign! Check out our latest product. #JanjezSocio #AI",
            'hashtags' => ['#JanjezSocio', '#AIGrowth', '#SocialMedia', '#MarketingAI'],
            'platforms' => $platform === 'all' ? ['facebook', 'instagram', 'tiktok', 'youtube', 'x', 'whatsapp'] : [$platform]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO generated_posts (user_id, platform, content, caption, hashtags, campaign_goal, status) VALUES (?, ?, ?, ?, ?, ?, 'draft')");
        $stmt->execute([
            $this->userId,
            $platform,
            json_encode($generated['visuals']),
            $generated['caption'],
            implode(' ', $generated['hashtags']),
            $campaignGoal
        ]);
        $postId = $this->pdo->lastInsertId();

        $this->updateAction($actionId, 'completed', [
            'post_id' => $postId,
            'generated' => $generated
        ]);

        return [
            'success' => true,
            'action_id' => $actionId,
            'post_id' => $postId,
            'generated' => $generated
        ];
    }

    public function connectPlatform($platform, $accessToken, $refreshToken = null, $expiry = null) {
        $stmt = $this->pdo->prepare("INSERT INTO platform_connections (user_id, platform, access_token, refresh_token, token_expiry) 
                                     VALUES (?, ?, ?, ?, ?) 
                                     ON DUPLICATE KEY UPDATE 
                                     access_token = VALUES(access_token),
                                     refresh_token = VALUES(refresh_token),
                                     token_expiry = VALUES(token_expiry),
                                     updated_at = NOW()");
        $stmt->execute([$this->userId, $platform, $accessToken, $refreshToken, $expiry]);

        $this->logAction('connect_platform', ['platform' => $platform]);

        return ['success' => true, 'platform' => $platform];
    }

    public function analyzePerformance($postId) {
        $metrics = [
            'impressions' => rand(1000, 50000),
            'engagement' => rand(50, 5000),
            'clicks' => rand(10, 1000),
            'conversions' => rand(0, 100),
            'sentiment' => ['positive' => rand(60, 95), 'neutral' => rand(5, 30), 'negative' => rand(0, 10)]
        ];

        $this->logAction('analyze_performance', ['post_id' => $postId]);

        $stmt = $this->pdo->prepare("UPDATE generated_posts SET engagement_data = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([json_encode($metrics), $postId, $this->userId]);

        return ['success' => true, 'metrics' => $metrics];
    }

    public function schedulePost($postId, $scheduledTime, $platforms = null) {
        $stmt = $this->pdo->prepare("UPDATE generated_posts SET scheduled_for = ?, status = 'scheduled' WHERE id = ? AND user_id = ?");
        $stmt->execute([$scheduledTime, $postId, $this->userId]);

        $this->logAction('schedule_post', [
            'post_id' => $postId,
            'scheduled_for' => $scheduledTime,
            'platforms' => $platforms
        ]);

        return ['success' => true, 'post_id' => $postId, 'scheduled_for' => $scheduledTime];
    }

    public function publishNow($postId) {
        $stmt = $this->pdo->prepare("UPDATE generated_posts SET status = 'published', published_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$postId, $this->userId]);

        $this->logAction('publish_now', ['post_id' => $postId]);

        return ['success' => true, 'post_id' => $postId, 'published_at' => date('Y-m-d H:i:s')];
    }

    public function regeneratePost($postId, $variation = 1) {
        $variations = [
            'caption' => "🚀 New variation! Fresh take on our {$variation}th creative direction. #JanjezSocio",
            'hashtags' => ['#JanjezSocio', '#AIContent', '#SocialGrowth', '#DigitalMarketing']
        ];

        $this->logAction('regenerate_post', ['post_id' => $postId, 'variation' => $variation]);

        return [
            'success' => true,
            'post_id' => $postId,
            'regenerated' => $variations
        ];
    }

    public function bulkGenerate($campaignData, $count = 5) {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = $this->generatePost($campaignData);
        }
        $this->logAction('bulk_generate', ['count' => $count, 'campaign' => $campaignData]);
        return ['success' => true, 'results' => $results];
    }

    public function getDashboardData() {
        $stmt = $this->pdo->prepare("SELECT * FROM generated_posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        $stmt->execute([$this->userId]);
        $recentPosts = $stmt->fetchAll();

        $stmt = $this->pdo->prepare("SELECT platform, is_active FROM platform_connections WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $connections = $stmt->fetchAll();

        $stmt = $this->pdo->prepare("SELECT * FROM user_actions WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
        $stmt->execute([$this->userId]);
        $actions = $stmt->fetchAll();

        $stmt = $this->pdo->prepare("SELECT 
            COUNT(*) as total_posts,
            SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled
        FROM generated_posts WHERE user_id = ?");
        $stmt->execute([$this->userId]);
        $stats = $stmt->fetch();

        return [
            'success' => true,
            'stats' => $stats,
            'recent_posts' => $recentPosts,
            'platform_connections' => $connections,
            'recent_actions' => $actions
        ];
    }

    private function logAction($actionType, $data = []) {
        $stmt = $this->pdo->prepare("INSERT INTO user_actions (user_id, action_type, action_data, status) VALUES (?, ?, ?, 'pending')");
        $stmt->execute([$this->userId, $actionType, json_encode($data)]);
        return $this->pdo->lastInsertId();
    }

    private function updateAction($actionId, $status, $resultData = null) {
        $stmt = $this->pdo->prepare("UPDATE user_actions SET status = ?, result_data = ?, completed_at = NOW() WHERE id = ?");
        $stmt->execute([$status, json_encode($resultData), $actionId]);
    }
}

// ============================================================
// 5. API ROUTING HANDLER
// ============================================================
function handleApiRequest($pdo) {
    $auth = new UserAuth($pdo);
    $user = $auth->getCurrentUser();

    $publicEndpoints = ['login', 'signup', 'health'];
    $action = $_GET['action'] ?? '';

    if (in_array($action, $publicEndpoints)) {
        return handlePublicEndpoint($pdo, $action);
    }

    if (!$user) {
        http_response_code(401);
        return json_encode(['success' => false, 'message' => 'Authentication required']);
    }

    $panel = new ActionPanel($pdo, $user['id']);

    switch ($action) {
        case 'dashboard':
            return json_encode($panel->getDashboardData());

        case 'generate':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->generatePost($data));

        case 'bulk_generate':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $count = intval($data['count'] ?? 3);
            return json_encode($panel->bulkGenerate($data, $count));

        case 'schedule':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->schedulePost($data['post_id'], $data['scheduled_time'], $data['platforms'] ?? null));

        case 'publish':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->publishNow($data['post_id']));

        case 'regenerate':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->regeneratePost($data['post_id'], $data['variation'] ?? 1));

        case 'analyze':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->analyzePerformance($data['post_id']));

        case 'connect_platform':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($panel->connectPlatform($data['platform'], $data['access_token'], $data['refresh_token'] ?? null, $data['expiry'] ?? null));

        case 'update_profile':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($auth->updateUser($user['id'], $data));

        case 'update_settings':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($auth->updateSettings($user['id'], $data));

        case 'logout':
            return json_encode($auth->logout());

        default:
            http_response_code(404);
            return json_encode(['success' => false, 'message' => 'Unknown action: ' . $action]);
    }
}

function handlePublicEndpoint($pdo, $action) {
    $auth = new UserAuth($pdo);

    switch ($action) {
        case 'health':
            return json_encode(['success' => true, 'status' => 'healthy', 'timestamp' => date('c')]);

        case 'signup':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            return json_encode($auth->signup($data['email'], $data['username'], $data['password'], $data['full_name'] ?? ''));

        case 'login':
            $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;
            return json_encode($auth->login($data['email'], $data['password'], $ip, $ua));

        default:
            http_response_code(404);
            return json_encode(['success' => false, 'message' => 'Public endpoint not found']);
    }
}

// ============================================================
// 6. EXECUTE API REQUEST
// ============================================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    echo handleApiRequest($pdo);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
