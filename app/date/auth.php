<?php
// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: /zoushokanri/app/pages/login.php");
    exit;
}
?>