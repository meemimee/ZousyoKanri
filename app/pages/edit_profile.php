<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//ログイン済み？？
require_once '../date/auth.php';

// CSRFトークンの生成
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// データベース接続
require_once '../includes/connect.php';
$conn = getDbConnection();

// エラーと成功メッセージの初期化
$error_message = array();
$success_message = "";

// ユーザーデータを取得
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
} else {
    // ユーザーが見つからない場合の処理
    session_destroy();
    header("Location: login.php");
    exit;
}

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRFトークンの検証
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message["csrf"] = "なんか失敗。もう一度お試しください。";
    } else {
        // 入力値を取得
        $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        
        // 変更があるか確認
        $has_changes = false;
        
        // ユーザー名の変更確認
        if (!empty($username) && $username !== $user_data['username']) {
            $has_changes = true;
        } else {
            // 入力がない場合は現在の値を使用
            $username = $user_data['username'];
        }
        
        // メールアドレスの変更確認
        if (!empty($email) && $email !== $user_data['email']) {
            $has_changes = true;
            
            // メールアドレスの形式チェック
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message["email"] = "有効なメールアドレスを入力してください";
            } else {
                // 他のユーザーと重複していないか確認
                $check_email = "SELECT 1 FROM users WHERE email = ? AND id != ? LIMIT 1";
                $stmt = $conn->prepare($check_email);
                $stmt->bind_param("si", $email, $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $error_message["email"] = "このメールアドレスは既に使用されています";
                }
            }
        } else {
            // 入力がない場合は現在の値を使用
            $email = $user_data['email'];
        }
        
        // パスワードの変更確認
        $password_updated = false;
        if (!empty($password)) {
            $has_changes = true;
            
            // パスワードの長さチェック
            if (strlen($password) < 8) {
                $error_message["password"] = "パスワードは8文字以上にしてください";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $password_updated = true;
            }
        }
        
        // 変更がない場合はエラー
        if (!$has_changes) {
            $error_message["general"] = "変更内容を入力してください";
        }
        
        // エラーがなければ更新処理
        if (empty($error_message)) {
            // トランザクション開始
            $conn->begin_transaction();
            
            try {
                // ユーザー情報の更新
                if ($password_updated) {
                    // パスワードも更新する場合
                    $update_sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("sssi", $username, $email, $hashed_password, $_SESSION['user_id']);
                } else {
                    // パスワードは更新しない場合
                    $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ssi", $username, $email, $_SESSION['user_id']);
                }
                $stmt->execute();
                
                // コミット
                $conn->commit();
                
                // セッション情報も更新
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                $success_message = "会員情報を更新しました";
                
                // 最新のユーザーデータを再取得
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $user_data = $result->fetch_assoc();
                
            } catch (Exception $e) {
                // エラー発生時はロールバック
                $conn->rollback();
                $error_message["db"] = "更新に失敗しました: " . $e->getMessage();
            }
        }
    }
}

// ヘッダーを読み込む
include 'header.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員情報編集 - ワシボン</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>会員情報編集</h1>
        
        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-messages">
                <?php foreach ($error_message as $message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="edit-profile-form">
        <form method="POST" action="">
                <!-- 隠してCSRFトークンを追加 -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-group">
                    <label for="username">お名前 <span class="current-value">: <?php echo htmlspecialchars($user_data['username']); ?></span></label>
                    <input type="text" id="username" name="username" placeholder="変更する場合は新しい名前を入力">
                    <small>※変更がなければ空欄のままにしてください</small>
                </div>
                
                <div class="form-group">
                    <label for="email">メールアドレス <span class="current-value">: <?php echo htmlspecialchars($user_data['email']); ?></span></label>
                    <input type="email" id="email" name="email" placeholder="変更する場合は新しいメールアドレスを入力">
                    <small>※変更がなければ空欄のままにしてください</small>
                </div>
                
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" placeholder="変更する場合は新しいパスワードを入力">
                    <small>※変更がなければ空欄のままにしてください</small>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">更新する</button>
                    <a href="mypage.php" class="btn btn-secondary">キャンセル</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
// フッターを読み込む
include 'footer.php';
$conn->close();
?>