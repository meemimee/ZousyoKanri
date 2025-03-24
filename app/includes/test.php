<?php
// connect.phpを読み込む
include 'path/to/connect.php';

// 接続テスト
if ($conn) {
    echo "データベース接続成功！";
} else {
    echo "データベース接続失敗...";
}
?>t