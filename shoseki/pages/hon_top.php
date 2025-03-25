<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//せっしょん
session_start();

//へっだー
include '../../app/pages/header.php';

//ログイン済み？？
require_once '../../app/date/auth.php';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ほんのかんり</title>
    <!--<link rel="stylesheet" href="css/style.css">いまはあとまわし-->
</head>
<body>
    <div class="login-container">
        <h1>書籍ページ</h1>


        <p>書籍を検索するよ</p>
        <input type="text" id="bookserch" name="bookserch"><button >検索</button>

        <p>一覧はこっからみれるよ</p>
        <a href="hon_list.php">書籍の一覧</a>

    </div>
</body>
</html>
<?php
include '../../app/pages/footer.php'; 
?>