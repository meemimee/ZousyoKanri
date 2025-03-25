<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// .envファイルよみこみ
$env_file = __DIR__ . '/../../.env';
if (file_exists($env_file)) {
    $env_vars = parse_ini_file($env_file);
    if ($env_vars) {
        // .envから値とってくる
        $servername = $env_vars['DB_HOST'] ;
        $username = $env_vars['DB_USER'] ;
        $password = $env_vars['DB_PASS'] ;
        $dbname = $env_vars['DB_NAME'] ;
    }
} 

// 接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続に失敗しました: " . $conn->connect_error);
}

// 文字セットをUTF-8に設定
$conn->set_charset("utf8");
?>