<?php
// エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//ログイン済み？？
require_once '../date/auth.php';

// データベース接続
include '../includes/connect.php';

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
    // 名前の検証
    if (empty($_POST["username"])) {
        $error_message["username"] = "お名前を入力してください";
    } else {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES, "UTF-8");
    }
    
    // メールアドレスの検証
    if (empty($_POST["email"])) {
        $error_message["email"] = "メールアドレスを入力してください";
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error_message["email"] = "有効なメールアドレスを入力してください";
    } else {
        $email = htmlspecialchars($_POST["email"], ENT_QUOTES, "UTF-8");
        
        // 他のユーザーと重複していないか確認（自分自身は除く）
        $check_email = "SELECT * FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("si", $email, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error_message["email"] = "このメールアドレスは既に使用されています";
        }
    }
    
    // パスワードの検証（入力された場合のみ）
    $password_updated = false;
    if (!empty($_POST["password"])) {
        if (strlen($_POST["password"]) < 8) {
            $error_message["password"] = "パスワードは8文字以上にしてください";
        } else {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $password_updated = true;
        }
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
                $stmt->bind_param("sssi", $username, $email, $password, $_SESSION['user_id']);
            } else {
                // パスワードは更新しない場合
                $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("ssi", $username, $email, $_SESSION['user_id']);
            }
            $stmt->execute();
            
            // 更新履歴の記録
            //$update_type = "profile_update";
            //$log_sql = "INSERT INTO user_updates (user_id, update_type) VALUES (?, ?)";
            //$stmt = $conn->prepare($log_sql);
            //$stmt->bind_param("is", $_SESSION['user_id'], $update_type);
            //$stmt->execute();
            
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
                <div class="form-group">
                    <label for="username">お名前</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">パスワード（変更する場合のみ入力）</label>
                    <input type="password" id="password" name="password">
                    <small>※8文字以上で入力してください</small>
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
?>