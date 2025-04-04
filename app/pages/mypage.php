<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// ログインチェック
require_once '../data/auth.php';

// データベース接続
require_once '../includes/connect.php';
$conn = getDbConnection();

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
    header("Location: /zoushokanri/app/data/login_processe.php");
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
                <div class="button-container">
                    <a href="edit_profile.php" class="nav-button">会員情報を編集</a>
                    <a href="/zoushokanri/shoseki/pages/hon_top.php" class="nav-button">書籍管理ページ</a>
                </div>
            </div>
        </div>
        <div class="logout-container">
            <a href="/zoushokanri/app/data/logout_process.php" class="nav-button logout-button">ログアウト</a>
        </div>
    </div>
</body>
</html>
<?php
include 'footer.php'; 
$conn->close();
?>