<?php
$zipcode = (isset($_POST['zipcode'])) ? $_POST['zipcode'] : "";
 
// 住所の一部が指定されていたときの処理
if ($zipcode != "")
{
    // 郵便番号データベースに接続する
    $dbh = new PDO("sqlite:zipdata.db");
 
    // 問合せのためのSQL文
    $sql = "SELECT zipcode, addr1||addr2||addr3 AS addr FROM ken_all
            WHERE zipcode like :zipcode";
    // SQL文実行の準備
    $stmt = $dbh->prepare($sql);
    $pattern = "%{$zipcode}%";
    // プレースホルダと変数の対応付け
    $stmt->bindParam(':zipcode', $pattern);
 
    // 問合せの実行
    $startTime = microtime(TRUE);
    $stmt->execute();
    $endTime = microtime(TRUE);    // 実行終了時刻
    $time = $endTime - $startTime; // かかった時間（単位は秒）
}
?>
 
<html>
<head>
<meta charset="UTF-8" />
<title>zip code search</title>
</head>
<body>
<h1>住所の検索</h1>
 
<form method="post" action="addrSearch.php">
    <p>郵便番号をハイフンなしで入力してください：</p>
    <input type="text" name="zipcode" value="<?php echo $zipcode; ?>">
    <input type="submit" value="住所を検索">
    <br>
    <input type='radio' name='index' value='on_index' checked>インデックスあり&nbsp;
　　<input type='radio' name='index' value='off_index' >インデックスなし&nbsp;
</form>
 
<?php
if ($zipcode != "") {
    echo "<hr>\n";
    if ($row = $stmt->fetch()) {
        // 問合せ結果の表示
        echo "<p>検索結果</p>";
        echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<td>" . $row[0] . "</td>\n";
        echo "<td>" . $row[1]. "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
 
        // データベースとの接続を解除する
        $dbh = null;
    
    }
    else {
        // 行が１つもなかったとき
        echo "<p>該当する住所がありません</p>\n";
    }
    echo"<a>実行時間:{$time}秒</a>";
}
?>