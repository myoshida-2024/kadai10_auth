<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunch memo</title>
    <style>div{padding: 10px;font-size:16px;}
            td{border: 1px solid;}
</style>
</head>
<body>
    <!-- Show screen -->
    </div>
<section id="Filter" class="text-gray-600 body-font">
            <div class="container px-5 py-4 mx-auto mt-6 ">
                <div class="lg:w-full md:w-full mx-auto ">
                    <form action="#" method="post" class="space-y-8 ">
                        <!-- フィルター -->
                        <div class="flex items-center border-b border-gray-300 pb-4">
                            <label for="selectCategory" class="w-1/3 small-text">カテゴリー</label>
                            <select id="selectCategory" name="selectCategory" class="w-2/3 ml-4 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <option value="すべて">すべて</option>
                                <option value="和食">和食</option>
                                <option value="中華">中華</option>
                                <option value="イタリアン">イタリアン</option>
                                <option value="フレンチ">フレンチ</option>
                                <option value="エスニック">エスニック</option>
                                <option value="その他">その他</option>
                            </select>
                        </div>

                        <div class="button-container" style="display: flex; justify-content: left; align-items: center; margin-top: -10px; margin-left: 80px; margin-bottom: 0px;">
                        <button type="submit" id="Filter">カテゴリー選択</button>
                        </div>

                <!-- 表示順 -->
                <div class="flex items-center border-b border-gray-300 pb-4">
                            <label for="order" class="w-1/3 small-text">表示順</label>
                            <select id="order" name="order" class="w-2/3 ml-4 bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <option value="indate">日付</option>
                                <option value="rating">評価</option>
                            </select>
                        </div>

                        <div class="button-container" style="display: flex; justify-content: left; align-items: center; margin-top: -10px; margin-left: 80px; margin-bottom: 20px;">
                            <button type="submit" id="Filter">表示順選択</button>
                        </div>




                    </form>
                </div>
            </div>
        </section>



<div>





    <?php
//1.  DB接続します
// try {
//   $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
// } catch (PDOException $e) {
//   exit('DB_CONNECT:'.$e->getMessage());
// }

include("funcs.php"); // 外部ファイル読み込み
$pdo = db_conn();

// フォームから選択されたカテゴリー、表示順を取得
$selectedCategory = isset($_POST['selectCategory']) ? $_POST['selectCategory'] : 'すべて；';
$selectedOrder = isset($_POST['order']) ? $_POST['order'] : 'indate';

//２．データ登録SQL作成
// すべてが選択された場合は条件をつけない


if ($selectedCategory == "すべて"){
    $sql = "SELECT * FROM gs_lunch_table ORDER BY $selectedOrder DESC;";
    $stmt = $pdo->prepare($sql);
}else {
    $sql = "SELECT * FROM gs_lunch_table WHERE category = :category ORDER BY $selectedOrder DESC;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':category',$selectedCategory, PDO::PARAM_STR);
}

$status = $stmt->execute(); // true or false

//３．データ表示
// $view="";  //無視
if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("SQL_ERROR:".$error[2]);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
//JSONい値を渡す場合に使う
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);

?>

    <div class="container jumbotron"></div>
    <table>
        <th>サムネイル</th>
        <th>レストラン名</th>
        <th>カテゴリー</th>
        <th>評価</th>
        <th>感想メモ</th>
        <th>日付</th>

        <tr>  
     <?php foreach($values as $value){  
        $og_image = get_og_image($value["url"]);
          
        echo "<td><a href='" . htmlspecialchars($value["url"]) . "' target='_blank'>";
        echo "<img src='" . htmlspecialchars($og_image) . "' alt='Thumbnail' width='100'>";
        echo "</a></td>";

        // echo "<p>URL: " . htmlspecialchars($value["url"]) . "</p>";
        // echo "<p>Image: " . htmlspecialchars($og_image) . "</p>";

        ?> <!--この中にhtmlが直接書ける -->
     
      <td><?=$value["name"]?></td>
      <td><?=$value["category"]?></td>
      <td><?=$value["rating"]?></td>
      <td><?=$value["memo"]?></td>
      <td><?=$value["indate"]?></td>
      <td><a href="./detail.php?id=<?=h($value["id"])?>">更新</a></td>
      <td><a href="./delete.php?id=<?=h($value["id"])?>">削除</a></td>
                                     </tr>
      <?php } ?>
    </table>
</div>

<div id="show-screen" >
    <div id="show-screen" >
                    <?php
                    // get_og_image関数を定義
                    function get_og_image($url) {
                        // 空のURLの場合はnullを返す
                        if (empty($url)) {
                            return null;
                        }

                        // 外部URLのHTMLを取得
                        $html = @file_get_contents($url);

                        // 取得できなかった場合
                        if ($html === FALSE) {
                            return null;
                        }

                        // og:imageの正規表現パターンを使って画像URLを抽出
                        if (preg_match('/<meta property="og:image" content="([^"]+)"/i', $html, $matches)) {
                            return $matches[1]; // 画像URLを返す
                        }

                        return null; // 画像が見つからなかった場合
                    }
                
                    ?>
    
       
    </div>







    
</body>
</html>
