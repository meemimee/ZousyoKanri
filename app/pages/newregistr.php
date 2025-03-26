<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// セッション開始
session_start();
require_once '../includes/connect.php';
$conn = getDbConnection();

// CSRFトークンの生成
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// エラーメッセージの取得
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : array();
$old_input = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : array();

// セッションから一度取り出したら削除
unset($_SESSION['error_message']);
unset($_SESSION['old_input']);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録する</title>
    <link rel="stylesheet" href="/zoushokanri/css/style.css">
</head>
<body>
    <div class="register-container">
        <h1>登録所</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-messages">
                <?php foreach ($error_message as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="/zoushokanri/app/date/register_process.php" method="POST" id="registerForm">
            <!-- CSRFトークンを追加 -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="input-group">
                <label for="username">お名前</label>
                <input type="text" id="username" name="username" value="<?php echo isset($old_input['username']) ? htmlspecialchars($old_input['username']) : ''; ?>" required>
            </div>
            <div class="input-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="<?php echo isset($old_input['email']) ? htmlspecialchars($old_input['email']) : ''; ?>" required>
            </div>
            <div class="input-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">会員登録</button>
        </form>
    </div>
</body>
</html>

<?php
include 'footer.php'; 
$conn->close();
?>