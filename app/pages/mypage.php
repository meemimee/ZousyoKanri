<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'header.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員情報</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>まいぺーじ</h1>      


        <a href="/zoushokanri/app/date/logout_process.php" class="nav-button">ログアウト</a>

    </div>
</body>
</html>
<?php
include 'footer.php'; 
?>