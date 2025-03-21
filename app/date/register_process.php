<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// データベース接続
include '../includes/connect.php';

// CSRFトークンの検証
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message']['csrf'] = "不正なリクエストです";
    header("Location: ../pages/newregistr.php");
    exit;
}

// エラーメッセージ配列
$error_message = array();
$escaped = array();

// 入力値の検証とエスケープ処理
// 名前入力チェック
if (empty($_POST["username"])) {
    $error_message["username"] = "お名前を入力してください";
} else {
    // エスケープ処理
    $escaped["username"] = htmlspecialchars($_POST["username"], ENT_QUOTES, "UTF-8");
}

// メールアドレス入力チェック
if (empty($_POST["email"])) {
    $error_message["email"] = "メールアドレスを入力してください";
} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $error_message["email"] = "有効なメールアドレスを入力してください";
} else {
    // エスケープ処理
    $escaped["email"] = htmlspecialchars($_POST["email"], ENT_QUOTES, "UTF-8");
    
    // メールアドレスの重複チェック
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error_message["email"] = "このメールアドレスは既に登録されています";
    }
}

// パスワード入力チェック
if (empty($_POST["password"])) {
    $error_message["password"] = "パスワードを設定してください";
} else if (strlen($_POST["password"]) < 8) {
    $error_message["password"] = "パスワードは8文字以上にしてください";
} else {
    // エスケープ処理
    $escaped["password"] = htmlspecialchars($_POST["password"], ENT_QUOTES, "UTF-8");
}

// エラーがある場合は登録ページに戻る
if (!empty($error_message)) {
    $_SESSION['error_message'] = $error_message;
    $_SESSION['old_input'] = array(
        'username' => $_POST['username'],
        'email' => $_POST['email']
    );
    header("Location: ../pages/newregistr.php");
    exit;
}

// エラーがなければデータベースに登録
$username = $escaped["username"];
$email = $escaped["email"];
$password = password_hash($escaped["password"], PASSWORD_DEFAULT);

// データベースに登録
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    // 登録成功
    $_SESSION['message'] = "登録が完了しました。ログインしてください。";
    header("Location: ../../index.php"); // トップページにリダイレクト
    exit;
} else {
    // 登録失敗
    $_SESSION['error_message']['db'] = "登録に失敗しました: " . $conn->error;
    $_SESSION['old_input'] = array(
        'username' => $_POST['username'],
        'email' => $_POST['email']
    );
    header("Location: ../pages/newregistr.php");
    exit;
}

$stmt->close();
$conn->close();
?>