<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//せっしょん
session_start();

//へっだー
include 'header.php';

//ログイン済み？？
require_once '../date/auth.php';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ほんのかんり</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>書籍ページ</h1>

    </div>
</body>
</html>
<?php
include 'footer.php'; 
?>