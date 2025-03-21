<?php
// エラー表示（開発中のみ有効化）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// データベース接続情報
$host = 'localhost';
$db   = 'your_database_name';
$user = 'your_database_user';
$pass = 'your_database_password';

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // PDOのエラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームデータの取得
    $name     = $_POST['なまえ'];
    $email    = $_POST['メールアドレス'];
    $password = $_POST['パスワード'];

    // ここでデータの検証とサニタイズを行う
    // 例: 空白チェック、メールアドレスの形式チェックなど
    if (empty($name) || empty($email) || empty($password)) {
        // エラーメッセージを表示し、登録ページに戻す
        echo '全ての項目を入力してください。';
        exit;
    }

    // メールアドレスの形式チェック
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'メールアドレスの形式が正しくありません。';
        exit;
    }

    // パスワードの長さチェック（例として最低8文字以上）
    if (strlen($password) < 8) {
        echo 'パスワードは8文字以上で入力してください。';
        exit;
    }

    // パスワードのハッシュ化
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // メールアドレスの重複チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        echo 'このメールアドレスは既に登録されています。';
        exit;
    }

    // データベースにユーザー情報を挿入
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $passwordHash);
    $stmt->execute();

    // 登録完了メッセージを表示またはリダイレクト
    echo "会員登録が完了しました。ログインしてください。";
    // ログインページにリダイレクトする場合
    // header('Location: ../../index.php');
    // exit();

} catch (PDOException $e) {
    // エラーメッセージを表示
    echo "データベースエラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    // 必要に応じてエラーログを記録し、ユーザーには一般的なエラーメッセージを表示する
}
?>