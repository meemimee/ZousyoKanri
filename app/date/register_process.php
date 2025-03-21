<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// データベース接続
include '../includes/connect.php';

// POSTデータの取得
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // パスワードのハッシュ化

// データベースに登録
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    // 登録成功
    $_SESSION['message'] = "登録が完了しました。ログインしてください。";
    header("Location: ../../index.php"); // トップページにリダイレクト
    exit;
} else {
    // 登録失敗
    $_SESSION['error'] = "登録に失敗しました: " . $conn->error;
    header("Location: ../pages/newregister.php"); // 登録ページに戻る
    exit;
}

$stmt->close();
$conn->close();
?>