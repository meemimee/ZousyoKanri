<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// セッション変数を全て解除
$_SESSION = array();

// セッションクッキーを削除
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// セッションを破棄
session_destroy();

// キャッシュ制御ヘッダーを送信
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ログインページにリダイレクト
header("Location: ../../index.php");
exit;
?>