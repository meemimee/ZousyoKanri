<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// indexとして一番最初のページにしたいよ
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログインページ</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- JavaScriptの追加 -->
    <script>
    function showRegisterForm() {
        document.getElementById('registerForm').style.display = 'block';
        document.getElementById('registerButton').style.display = 'none';
    }
    </script>
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
        <!-- 会員登録ボタン -->
        <button id="registerButton"><a href="./app/pages/newregistr.php">会員登録</a></button>
        <!-- register.php をインクルード -->

    </div>
</body>
</html>