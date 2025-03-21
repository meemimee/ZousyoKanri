<?php
session_start();
include '../includes/connect.php';

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
        
        // マイページへリダイレクト
        header("Location: ../pages/mypage.php");
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