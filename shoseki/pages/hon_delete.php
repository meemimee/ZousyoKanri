<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//セッション
session_start();

//ログイン済み？？
require_once '../../app/data/auth.php';

// データベース接続
require_once '../../app/includes/connect.php';
$conn = getDbConnection();

// 書籍IDの取得と検証
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "無効なリクエストです。";
    header("Location: hon_top.php");
    exit;
}

$book_id = intval($_GET['id']);

// 書籍の存在確認
$sql = "SELECT title FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "指定された書籍が見つかりません。";
    header("Location: hon_top.php");
    exit;
}

$book = $result->fetch_assoc();
$book_title = $book['title'];

// 削除処理
$sql = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "書籍「" . $book_title . "」を削除しました。";
} else {
    $_SESSION['error_message'] = "削除に失敗しました: " . $conn->error;
}

// 一覧ページにリダイレクト
header("Location: hon_top.php");
exit;
?>