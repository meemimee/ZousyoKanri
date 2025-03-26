<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// indexとして一番最初のページにしたいよ
session_start();

// メッセージの取得と削除
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // 一度表示したら削除

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>My Books 〜ワシボン〜</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>蔵書管理しまっせ。</h1>

            <!-- メッセージ表示部分を追加 -->
            <?php if (!empty($message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

        <div class="button-container">
            <a href="app/pages/login.php" class="main-button login-button">ログイン</a>
            <a href="app/pages/newregistr.php" class="main-button register-button">会員登録</a>
        </div>

    </div>
</body>
</html>