<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// データベース接続
require_once '../includes/connect.php';
$conn = getDbConnection();

// CSRFトークンの検証
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message']['csrf'] = "不正なリクエストです";
    header("Location: ../pages/newregistr.php");
    exit;
}

// エラーメッセージ配列
$error_message = array();

// 入力値の検証
// 名前入力チェック
if (empty($_POST["username"])) {
    $error_message["username"] = "お名前を入力してください";
}

// メールアドレス入力チェック
if (empty($_POST["email"])) {
    $error_message["email"] = "メールアドレスを入力してください";
} else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $error_message["email"] = "有効なメールアドレスを入力してください";
} else {
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
$username = $_POST["username"];  // エスケープ不要
$email = $_POST["email"];        // エスケープ不要
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);  // パスワードはハッシュ化

// データベースに登録
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    // 登録成功
    $_SESSION['message'] = "会員登録が完了しました！ようこそ〜！";
    header("Location: ../../index.php"); // トップページにリダイレクト
    exit;
} else {
    // 登録失敗
    $_SESSION['error_message']['db'] = "登録に失敗しました: " . $conn->error;
    $_SESSION['old_input'] = array(
        'username' => $_POST['username'],
        'email' => $_POST['email']
    );
    header("Location: ../pages/newregistr.php"); // 登録ページに戻る
    exit;
}

$stmt->close();
$conn->close();
?>