<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// CSRFトークンを常に再生成
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// エラーメッセージの取得
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); // エラーメッセージをセッションから削除
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
        
        <?php if (!empty($error)): ?> <!-- $errorを使用する -->
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="../date/login_process.php" method="POST">
            <!-- CSRFトークンを追加 -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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