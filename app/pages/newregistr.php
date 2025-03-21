<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// セッション開始
session_start();
include '../includes/connect.php'; 



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員登録ページ</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="register-container">
        <h1>会員登録</h1>
        <form action="/zoushokanri/app/date/register_process.php" method="POST" id="registerForm">
            <div class="username">
                <label for="username">お名前</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">会員登録</button>
        </form>
    </div>
</body>
</html>