<?php
// エラー表示（開発中のみ有効化）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッションを開始
session_start();

// セッション変数を全て解除
$_SESSION = array();

// セッションクッキーも削除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 最終的に、セッションを破棄
session_destroy();

// ログインページにリダイレクト
header("Location: ../../index.php");
exit();
?>