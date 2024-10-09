
<?php
//1. POSTデータ取得
$name   = $_POST["name"];
$url    = $_POST["url"];
$category  = $_POST["category"];
$rating    = $_POST["rating"];
$memo   = $_POST["memo"];

//2. DB接続します
include("funcs.php"); // 外部ファイル読み込み
$pdo = db_conn();


//３．データ登録SQL作成
$sql ="INSERT INTO gs_lunch_table(id,name,url,category,rating,memo,indate) VALUES (NULL,:name,:url,:category,:rating,:memo,sysdate());";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':url', $url, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':category', $category, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':rating', $rating, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); // true or false 

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  sql_error($stmt);
}else{
  //５．index.phpへリダイレクト
  redirect("index.html");  

}
?>



