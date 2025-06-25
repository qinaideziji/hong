<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'url_shortener');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');

// 生成CSRF令牌
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 创建必要的数据库表（如果不存在）
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    // 创建数据库（如果不存在）
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);
    
    // 创建links表
    $pdo->exec("CREATE TABLE IF NOT EXISTS links (
        id VARCHAR(255) PRIMARY KEY,
        original_url TEXT,
        html_path TEXT,
        visits INT DEFAULT 0,
        created_at DATETIME
    )");
    
    // 创建link_visits表
    $pdo->exec("CREATE TABLE IF NOT EXISTS link_visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        link_id VARCHAR(255),
        ip VARCHAR(45),
        user_agent TEXT,
        referer TEXT,
        visited_at DATETIME,
        FOREIGN KEY (link_id) REFERENCES links(id)
    )");
} catch (PDOException $e) {
    die('数据库初始化失败: ' . $e->getMessage());
}
?>    