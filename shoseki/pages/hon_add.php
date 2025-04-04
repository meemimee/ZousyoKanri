<?php
//エラー表示
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//セッション
session_start();

//ヘッダー
include '../../app/pages/header.php';

//ログイン済み？？
require_once '../../app/date/auth.php';

// データベース接続
include '../../app/includes/connect.php';
$conn = getDbConnection();

// CSRFトークンの生成
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// エラーの初期化
$error_message = array();

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRFトークンの検証
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message["csrf"] = "セキュリティエラー：不正なリクエストです。もう一度お試しください。";
    } else {
        // タイトルの検証
        if (empty($_POST["title"])) {
            $error_message["title"] = "タイトルを入力してください";
        } else {
            $title = $_POST["title"];
        }
        
        // 作者名の取得（空でも可）
        $author = !empty($_POST["author"]) ? $_POST["author"] : null;
        
        // 評価の検証
        $star = isset($_POST["star"]) ? intval($_POST["star"]) : 0;
        if ($star < 0 || $star > 5) {
            $error_message["star"] = "評価は0～5の間で選択してください";
        }
        
          // エラーがなければ登録処理
          if (empty($error_message)) {
            // 現在の日時を取得
            $current_time = date('Y-m-d H:i:s');
                
            // データベースに登録
            $sql = "INSERT INTO books (title, author, star, created, updated) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $title, $author, $star, $current_time, $current_time);
            
            if ($stmt->execute()) {
                // 成功メッセージをセッションに保存
                $_SESSION['message'] = "書籍「" . $title . "」を登録しました！";
                     
                 // TOPページにリダイレクト
                 header("Location: hon_top.php");
                 exit;
             } else {
                 $error_message["db"] = "登録に失敗しました: " . $conn->error;
             }
         }
     }
 }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍登録</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>書籍登録</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-messages">
                <?php foreach ($error_message as $message): ?>
                    <p><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <!-- CSRFトークン -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="title">タイトル <span style="color: red;">*</span></label>
                <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="author">著者</label>
                <input type="text" id="author" name="author" value="<?php echo isset($author) ? htmlspecialchars($author) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>評価</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="star" value="5" <?php echo (isset($star) && $star == 5) ? 'checked' : ''; ?>>
                    <label for="star5">★</label>
                    <input type="radio" id="star4" name="star" value="4" <?php echo (isset($star) && $star == 4) ? 'checked' : ''; ?>>
                    <label for="star4">★</label>
                    <input type="radio" id="star3" name="star" value="3" <?php echo (isset($star) && $star == 3) ? 'checked' : ''; ?>>
                    <label for="star3">★</label>
                    <input type="radio" id="star2" name="star" value="2" <?php echo (isset($star) && $star == 2) ? 'checked' : ''; ?>>
                    <label for="star2">★</label>
                    <input type="radio" id="star1" name="star" value="1" <?php echo (isset($star) && $star == 1) ? 'checked' : ''; ?>>
                    <label for="star1">★</label>
                    <input type="radio" id="star0" name="star" value="0" <?php echo (!isset($star) || $star == 0) ? 'checked' : ''; ?> style="display:none;">
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">登録する</button>
                <a href="hon_top.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php
include '../../app/pages/footer.php';
?>