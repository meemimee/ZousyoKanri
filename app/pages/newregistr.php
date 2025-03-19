<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// 会員登録用だね
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規会員登録</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="login-container">
        <h1>ログイン</h1>
        <form action="authenticate.php" method="POST">
            <div class="username">
                <label for="username">お名前</label>
                <input type="text" id ="username" name="なまえ" required>
            </div>
            <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="メールアドレス" required>
            </div>
            <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="パスワード" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <a href="＊＊" class="register-link">会員登録</a>
    </div>
</body>
</html>