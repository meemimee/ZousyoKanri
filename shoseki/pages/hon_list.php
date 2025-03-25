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

<a href="hon_top.php">書籍管理TOPへ戻る</a>
    <div class="list_top">
        <p>書籍を検索するよ</p>
        <input type="text" id="bookserch" name="bookserch"><button >検索</button>
    </div>
    <div class="list">
        <h2>一覧</h2>


    </div>
</body>
</html>
<?php
include '../../app/pages/footer.php'; 
?>