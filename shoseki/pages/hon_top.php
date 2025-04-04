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
include '../../app/includes/connect.php';
$conn = getDbConnection();

// メッセージの取得と削除
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';


// メッセージの取得方法を変更
$show_success = isset($_SESSION['show_success']) ? $_SESSION['show_success'] : false;
$raw_title = isset($_SESSION['raw_title']) ? $_SESSION['raw_title'] : '';

// メッセージをいっかいだけ表示！
unset($_SESSION['show_success']);
unset($_SESSION['raw_title']);
unset($_SESSION['message']); // 既存のメッセージも削除



// 検索条件の初期化
$search_term = '';
$where_clause = '';
$params = [];
$param_types = '';

// 検索処理
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $where_clause = "WHERE title LIKE ? OR author LIKE ?";
    $search_param = "%" . $search_term . "%";
    $params = [$search_param, $search_param];
    $param_types = "ss"; // 2つの文字列パラメータ
}

// 書籍データの取得
$sql = "SELECT id, title, author, star, created, updated FROM books $where_clause ORDER BY updated DESC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ほんのかんり</title>
    <link rel="stylesheet" href="../../css/shoseki.css">
</head>
<body>
    <h1>書籍管理</h1>
    <div class="topcontiner">
    <!-- メッセージ表示 -->
    <?php if (!empty($message)): ?>
        <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="error-message" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>


        <!-- 検索フォーム -->
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="タイトルまたは著者名で検索">
                <button type="submit">検索</button>
                <button type="button" onclick="location.href='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'">クリア</button>
            </form>
        </div>
    </div>
        <!-- 書籍一覧 -->
        <div class="container">
        <h2>書籍の一覧</h2>
        
        <?php if (empty($books)): ?>
            <p class="no-books">書籍が見つかりません。</p>
        <?php else: ?>
            <table class="book-list">
                <thead>
                    <tr>
                        <th>タイトル</th>
                        <th>著者</th>
                        <th>評価</th>
                        <th>登録日</th>
                        <th>更新日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author'] ?? '不明'); ?></td>
                            <td>
                                <div class="star-rating">
                                    <?php 
                                    $rating = intval($book['star'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $rating) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars(date('Y/m/d', strtotime($book['created']))); ?></td>
                            <td><?php echo htmlspecialchars(date('Y/m/d', strtotime($book['updated']))); ?></td>
                            <td>
                                <a href="hon_edit.php?id=<?php echo $book['id']; ?>">編集</a> | 
                                <a href="hon_delete.php?id=<?php echo $book['id']; ?>" onclick="return confirm('本当に削除しますか？');">削除</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- 新規登録ボタン -->
        <div style="margin-top: 20px;">
            <a href="hon_add.php" style="padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">新しい書籍を追加</a>
        </div>
    </div>
</body>
</html>
<?php
include '../../app/pages/footer.php'; 
$conn->close();
?>