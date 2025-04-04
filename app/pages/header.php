<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// セッション開始（必要かわからんけどイランきがする）
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ワシボン'; ?></title>
    <link rel="stylesheet" href="/zoushokanri/css/style.css">
    <style>
        /* ヘッダースタイル */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
            box-sizing: border-box;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        /* ヘッダー内のボタン専用スタイル */
        .header-button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            margin: 0; /* マージンをリセット */
        }
        
        .header-button:hover {
            background-color: #45a049;
        }
        
        /* ヘッダー内のログアウトボタン */
        .header-logout {
            background-color: #d37919;
        }
        
        .header-logout:hover {
            background-color:rgb(230, 62, 16);
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/zoushokanri/app/pages/top.php" class="logo">蔵書管理</a>
        <div class="nav-buttons">
            <a href="/zoushokanri/app/pages/mypage.php" class="header-button">マイページ</a>
            <a href="/zoushokanri/app/data/logout_process.php" class="header-button header-logout">ログアウト</a>
        </div>
    </header>
    <div class="content">
        <!-- こっからコンテンツ -->