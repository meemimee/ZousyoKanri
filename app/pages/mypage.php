<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// ログインチェック
require_once '../date/auth.php';

// データベース接続
include '../includes/connect.php';

// ユーザーデータを取得
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
} else {
    // ユーザーが見つからない場合の処理
    session_destroy();
    header("Location: /zoushokanri/app/date/login_processe.php");
    exit;
}

//これはへっだー
include 'header.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員情報</title>
    <link rel="stylesheet" href="/zoushokanri/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>まいぺーじ</h1>     
        <div class="mypage-content">
            <div class="user-info">
                <h2>会員情報</h2>
                <p><strong>お名前：</strong> <?php echo htmlspecialchars($user_data['username']); ?></p>
                <p><strong>メールアドレス：</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
                <a href="edit_profile.php" class="nav-button" >会員情報を編集</a>
            </div>
        </div>

        <a href="/zoushokanri/app/date/logout_process.php" class="nav-button">ログアウト</a>
    </div>
</body>
</html>
<?php
include 'footer.php'; 
?>