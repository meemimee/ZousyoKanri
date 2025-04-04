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
require_once '../../app/data/auth.php';

// データベース接続
require_once '../../app/includes/connect.php';
$conn = getDbConnection();

// CSRFトークンの生成
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// エラーメッセージの初期化
$error_message = array();

// 書籍IDの取得と検証
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: hon_top.php");
    exit;
}

$book_id = intval($_GET['id']);

// 書籍データの取得
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // 書籍が見つからない場合
    $_SESSION['error_message'] = "指定された書籍が見つかりません。";
    header("Location: hon_top.php");
    exit;
}

$book = $result->fetch_assoc();

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRFトークンの検証
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message["csrf"] = "エラーだねもっかいやって。";
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
        
        // エラーがなければ更新処理
        if (empty($error_message)) {
            // 現在の日時を取得（更新日時のみ更新）
            $current_time = date('Y-m-d H:i:s');
            
            // データベースを更新
            $sql = "UPDATE books SET title = ?, author = ?, star = ?, updated = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisi", $title, $author, $star, $current_time, $book_id);
            
            if ($stmt->execute()) {
                // 成功メッセージをセッションに保存
                $_SESSION['message'] = "書籍「" . $title . "」の情報を更新しました！";
                
                // TOPページにリダイレクト
                header("Location: hon_top.php");
                exit;
            } else {
                $error_message["db"] = "更新に失敗しました: " . $conn->error;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍編集 - ワシボン</title>
    <link rel="stylesheet" href="../../css/shoseki.css">
</head>
<body>
    <div class="container">
        <h1>書籍編集</h1>

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
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="author">著者</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            
            <div class="form-group">
                <label>評価</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="star" value="5" <?php echo ($book['star'] == 5) ? 'checked' : ''; ?>>
                    <label for="star5">★</label>
                    <input type="radio" id="star4" name="star" value="4" <?php echo ($book['star'] == 4) ? 'checked' : ''; ?>>
                    <label for="star4">★</label>
                    <input type="radio" id="star3" name="star" value="3" <?php echo ($book['star'] == 3) ? 'checked' : ''; ?>>
                    <label for="star3">★</label>
                    <input type="radio" id="star2" name="star" value="2" <?php echo ($book['star'] == 2) ? 'checked' : ''; ?>>
                    <label for="star2">★</label>
                    <input type="radio" id="star1" name="star" value="1" <?php echo ($book['star'] == 1) ? 'checked' : ''; ?>>
                    <label for="star1">★</label>
                    <input type="radio" id="star0" name="star" value="0" <?php echo ($book['star'] == 0) ? 'checked' : ''; ?> style="display:none;">
                </div>
            </div>
            
            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">更新する</button>
                <a href="hon_top.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php
include '../../app/pages/footer.php';
?>