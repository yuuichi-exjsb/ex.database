<?php
$addr = (isset($_POST['addr'])) ? $_POST['addr'] : "";
 
// 住所の一部が指定されていたときの処理
if ($addr != "")
{
    // 郵便番号データベースに接続する
    $dbh = new PDO("sqlite:zipdata.db");
 
    // 問合せのためのSQL文
    $sql = "SELECT zipcode, addr1||addr2||addr3 AS addr FROM ken_all
            WHERE addr LIKE :addr";
    // SQL文実行の準備
    $stmt = $dbh->prepare($sql);
    $pattern = "%{$addr}%";
    // プレースホルダと変数の対応付け
    $stmt->bindParam(':addr', $pattern);
 
    // 問合せの実行
    $stmt->execute();
}
?>
 
<html>
<head>
<meta charset="UTF-8" />
<title>zip code search</title>
</head>
<body>
<h1>郵便番号の検索</h1>
 
<form method="post" action="locNumber.php">
    <p>住所の一部を入力してください：</p>
    <input type="text" name="addr" value="<?php echo $addr; ?>">
    <input type="submit" value="郵便番号を検索">
</form>
 
<?php
if ($addr != "") {
    echo "<hr>\n";
    if ($row = $stmt->fetch()) {
        // 問合せ結果の表示
        echo "<p>検索結果</p>";
        echo "<table border='1'>\n";
        echo "<tr>\n";
        echo "<td>" . $row[0] . "</td>\n";
        echo "<td>" . $row[1]. "</td>\n";
        echo "</tr>\n";
        // 結果を1行ずつ取り出して表示する
    while ($row = $stmt->fetch())
    {
        echo "<tr>\n";
        echo "<td>" . $row[0] . "</td>\n";
        echo "<td>" . $row[1]. "</td>\n";
        echo "</tr>\n";
    }
        echo "</table>\n";
 
        // データベースとの接続を解除する
        $dbh = null;
    
    }
    else {
        // 行が１つもなかったとき
        echo "<p>該当する住所がありません</p>\n";
    }
}
