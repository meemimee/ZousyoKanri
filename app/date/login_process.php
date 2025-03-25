<?php
session_start();
include '../includes/connect.php';

// CSRFトークンの検証
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "セキュリティエラー：不正なリクエストです。もう一度お試しください。";
    header("Location: ../pages/login.php");
    exit;
}

// POSTデータの取得
$email = $_POST['email'];
$password = $_POST['password'];

// ユーザー検索
$sql = "SELECT id, username, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // パスワード検証
    if (password_verify($password, $user['password'])) {
        // ログイン成功
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // CSRFトークンを再生成
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        // マイページへリダイレクト
        header("Location: ../pages/top.php");
        exit;
    } else {
        // パスワード不一致
        $_SESSION['error'] = "メールアドレスまたはパスワードが正しくありません。";
    }
} else {
    // ユーザーが見つからない
    $_SESSION['error'] = "メールアドレスまたはパスワードが正しくありません。";
}

// ログイン失敗時はログインページに戻る
header("Location: ../pages/login.php");
exit;
?>