<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// 处理跨域请求
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// 检查请求方法
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => '非法请求方式']));
}

// 验证CSRF令牌
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die(json_encode(['error' => '安全令牌验证失败']));
}

// 获取URL或上传的HTML文件
$originalUrl = $_POST['url'] ?? '';
$customHtml = $_FILES['custom_html'] ?? null;

// 验证输入
if (empty($originalUrl) && ($customHtml === null || $customHtml['error'] !== UPLOAD_ERR_OK)) {
    die(json_encode(['error' => '请输入URL或上传自定义HTML文件']));
}

// 处理上传的HTML文件
$htmlPath = '';
if ($customHtml && $customHtml['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/';
    $fileName = uniqid() . '_' . basename($customHtml['name']);
    $htmlPath = $uploadDir . $fileName;
    
    // 检查文件类型
    $fileType = pathinfo($htmlPath, PATHINFO_EXTENSION);
    if ($fileType !== 'html' && $fileType !== 'htm') {
        die(json_encode(['error' => '请上传有效的HTML文件']));
    }
    
    // 移动上传的文件
    if (!move_uploaded_file($customHtml['tmp_name'], $htmlPath)) {
        die(json_encode(['error' => '文件上传失败']));
    }
}

// 生成唯一ID
$uniqueId = generateUniqueId();

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
    die(json_encode(['error' => '数据库连接失败: ' . $e->getMessage()]));
}

// 准备SQL语句
try {
    $stmt = $pdo->prepare("INSERT INTO links (id, original_url, html_path, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$uniqueId, $originalUrl, $htmlPath]);
    
    // 返回结果
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $shortUrl = $baseUrl . '/r/' . $uniqueId;
    
    echo json_encode([
        'id' => $uniqueId,
        'short_url' => $shortUrl
    ]);
} catch (PDOException $e) {
    // 删除已上传的文件
    if (!empty($htmlPath) && file_exists($htmlPath)) {
        unlink($htmlPath);
    }
    
    die(json_encode(['error' => '数据库操作失败: ' . $e->getMessage()]));
}
?>    