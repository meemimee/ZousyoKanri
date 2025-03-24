<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//これはへっだー
include 'header.php';

// ユーザーデータ取ってくる
//include '../functions/user_get.php';

// ユーザーデータを取得
//$user_data = getUserData($_SESSION['user_id'], $conn);

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
        <div class="mypage-content">
            <div class="user-info">
                <h2>会員情報</h2>
                <p><strong>お名前：</strong> 
                <p><strong>メールアドレス：</strong> 
                <a href="edit_profile.php" class="btn btn-secondary">会員情報を編集</a>
            </div>


        <a href="/zoushokanri/app/date/logout_process.php" class="nav-button">ログアウト</a>

    </div>
</body>
</html>
<?php
include 'footer.php'; 
?>