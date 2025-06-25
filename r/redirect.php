<?php
require_once '../config.php';
require_once '../functions.php';

// 获取短链接ID
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    header('HTTP/1.0 404 Not Found');
    exit('无效的链接');
}

// 构建数据库连接
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('数据库连接失败: ' . $e->getMessage());
}

// 查询链接信息
try {
    $stmt = $pdo->prepare("SELECT * FROM links WHERE id = ?");
    $stmt->execute([$id]);
    $link = $stmt->fetch();
    
    if (!$link) {
        header('HTTP/1.0 404 Not Found');
        exit('链接不存在或已过期');
    }
    
    // 更新访问统计
    $stmt = $pdo->prepare("UPDATE links SET visits = visits + 1 WHERE id = ?");
    $stmt->execute([$id]);
    
    // 记录访问日志
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $timestamp = date('Y-m-d H:i:s');
    
    $stmt = $pdo->prepare("INSERT INTO link_visits (link_id, ip, user_agent, referer, visited_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $ip, $userAgent, $referer, $timestamp]);
    
    // 检查是否有自定义HTML
    if (!empty($link['html_path']) && file_exists($link['html_path'])) {
        // 读取并输出HTML内容
        $htmlContent = file_get_contents($link['html_path']);
        
        // 替换占位符（如果有）
        $htmlContent = str_replace('{{ORIGINAL_URL}}', htmlspecialchars($link['original_url']), $htmlContent);
        $htmlContent = str_replace('{{SHORT_URL}}', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/r/$id", $htmlContent);
        
        echo $htmlContent;
        exit;
    }
    
    // 如果没有自定义HTML，使用默认iframe页面
    $originalUrl = $link['original_url'];
    $encodedUrl = base64_encode($originalUrl);
    
    // 重定向到iframe页面
    header("Location: /iframe.php?u=$encodedUrl");
} catch (PDOException $e) {
    die('数据库操作失败: ' . $e->getMessage());
}
?>    
