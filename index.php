<?php
// indexとして一番最初のページにしたいよ
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>ログイン</h1>
        <form action="authenticate.php" method="POST">
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