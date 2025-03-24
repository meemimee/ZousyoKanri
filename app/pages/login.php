<?php
session_start();
// ログイン済みの場合はTOPリダイレクト(いらんかな〜迷い中)
//if (isset($_SESSION['user_id'])) {
    //header("Location: TOP.php");
    //exit;
//}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン - 蔵書へいきたいか〜！</title>
    <!--<link rel="stylesheet" href="../../css/styles.css">-->
</head>
<body>
    <div class="login-container">
        <h1>ログイン</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form action="../date/login_process.php" method="POST">
            <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントをお持ちでない方は <a href="newregistr.php">こちら</a> から登録</p>
    </div>
</body>
</html>