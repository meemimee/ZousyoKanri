<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "zoushokanri"; // 作成したデータベース名

// 接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("接続に失敗しました: " . $conn->connect_error);
}

echo "データベース接続成功！";
// 実際のアプリでは上記のecho文は削除してください

// 文字セットをUTF-8に設定
$conn->set_charset("utf8");
?>