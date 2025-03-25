<?php
// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: /zoushokanri/index.php");
    exit;
}
?>