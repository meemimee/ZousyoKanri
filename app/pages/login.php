<?php
session_start();
include 'connect.php'; // パスは実際の配置に合わせて調整

$error = "";

// ログイン済みの場合はマイページへリダイレクト
if (isset($_SESSION['user_id'])) {
    header("Location: mypage.php");
    exit;
}

// フォームが送信された場合
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // ユーザー検索
    $query = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // パスワード検証
        if (password_verify($password, $user['password'])) {
            // セッションにユーザー情報を保存
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            // マイページへリダイレクト
            header("Location: mypage.php");
            exit;
        } else {
            $error = "メールアドレスまたはパスワードが正しくありません。";
        }
    } else {
        $error = "メールアドレスまたはパスワードが正しくありません。";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ログイン - 蔵書管理システム</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>ログイン</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="email">メールアドレス:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">ログイン</button>
        </form>
        
        <p>アカウントをお持ちでない方は <a href="register.php">こちら</a> から登録</p>
    </div>
</body>
</html>