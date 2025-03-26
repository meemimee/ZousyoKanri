<?php
// エラー表示（開発時のみ）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * データベース接続を取得する関数
 * @return mysqli データベース接続オブジェクト
 */
function getDbConnection() {
    static $conn = null; // 接続を保持する静的変数
    
    // 既に接続が確立されている場合はそれを返す
    if ($conn !== null) {
        return $conn;
    }
    
    // .envファイルを読み込む
    $env_file = __DIR__ . '/../../.env';
    if (file_exists($env_file)) {
        $env_vars = parse_ini_file($env_file);
        if ($env_vars) {
            $servername = $env_vars['DB_HOST'] ;
            $username = $env_vars['DB_USER'] ;
            $password = $env_vars['DB_PASS'] ;
            $dbname = $env_vars['DB_NAME'] ;
        }
    } 
    
    // 接続を作成
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 接続チェック
    if ($conn->connect_error) {
        die("接続に失敗しました: " . $conn->connect_error);
    }
    
    // 文字セットをUTF-8に設定
    $conn->set_charset("utf8");
    
    return $conn;
}
?>