<?php
if(!empty($_POST['name'])){
//参考書タイトルを取得
//////$name = $_POST['name'];
//今日の日付取得
$today = time();
//終了予定日の日付取得
$xday = strtotime($_POST['date']);

//差を計算(秒数)
$result = $xday - $today;
//差を日数に変換(小数点以下切り捨て)
$interval = floor($result / (60*60*24));
//差を週の数に変換(小数点以下切り捨て)
$num_week = floor($interval / 7);

//進捗ペース(optionタグのvalueを取得)
///////if(isset($_POST['rest'])){
	$rest = $_POST['rest'];
/////}

//稼働日数計算
$active = $interval - ($rest * $num_week);

//ページ数取得
//$pages = htmlspecialchars($_POST['pages']);

//進捗計算結果
//$disp = $pages / $active;
//残りのページ数
$left = $_POST['pages'] - $_POST['current_page'];

//進捗割合
$racio = $_POST['current_page'] / $_POST['pages'] *100;

 $disp = $left / $active;
 }
 
 //ここからjsonファイルの操作
//特殊文字の変換
function h($str){
	return htmlspecialchars($str, ENT_QUOTES);
}

//jsonファイルを読み込んで配列にして$rowsに格納する
$rows = json_decode(file_get_contents('textbooks_info.json'), true);

//既存データの中で最大のidを取得する
//新規の投稿のidは数字で、既存の記事の中で最大のid+1とする

$max_id= 0; //暫定の最大idを0とする

foreach($rows as $key => $value){
  //記事のidが暫定最大より大きければそれを新しい暫定最大idとする
  if($value['id'] > $max_id){
    $max_id = $value['id'];
    }
 }



?>

<!DOCTYPE html>
<html class="no-js" lang="ja">
<head>
<meta charset="utf-8">
<title>進捗管理アプリ</title>
<link rel="stylesheet" href="progress.css">
<script src="package/dist/Chart.js"></script>
</head>
<body>
<div class="header">
<h1>進捗管理アプリ</h1>
</div>
<!--
<p><?= $today ?></p>
<p><?= htmlspecialchars($xday) ?></p>
<p><?= htmlspecialchars($name) ?></p>
<p><?= "日数残り" .$interval ."日(". $num_week ."週間)";  ?></p>
<p><?= "週休" .$rest; ?></p>
<p><?= "稼働日数" . $active; ?></p>
<p><?= "結果：1日" . $disp. "ページ"; ?></p>
-->
<h2><? printf("結果：1日  %.2f ページ", $disp); ?></h2>

<form method="post" action="add_info.php">
<p>『<?= $_POST['name'] ?>』を進捗グラフに追加しますか？</p>
<!--あとでhiddenにする-->
<input type="hidden" name="add_name" value="<?= $_POST['name'] ?>">
<input type="hidden" name="add_pages" value="<?= $_POST['pages'] ?>">
<input type="hidden" name="add_date" value="<?= $_POST['date'] ?>">
<input type="hidden" name="add_rest" value="<?= $_POST['rest'] ?>">
<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
<input type="hidden" name="pace_result" value="<? printf("%.1f", $disp) ?>">
<input type="hidden" name="racio_result" value="<? printf("%d", $racio) ?>">
<input type="submit" name="add" value="追加">
</form>

<!--描画領域 -->
<canvas id="canvas"></canvas>
<script>
    var ctx = document.getElementById("canvas").getContext("2d");
    var myBar = new Chart(ctx, {
        type: 'horizontalBar',                           //◆棒グラフ
        data: {                                //◆データ
            labels: [
            <?php foreach($rows as $row): ?>
            	 <?php echo "'". h($row['name']). "'" . "," ?>
              <?php endforeach ?>
            ],     //ラベル名
            datasets: [{                       //データ設定
                data: [
                <?php foreach($rows as $row): ?>
                    <?php echo h($row['racio']). ","?>
                <?php endforeach ?>
                ],          //データ内容
                backgroundColor: ['#F97F51', '#25CCF7', '#D6A2E8', '#58B19F', '#FC427B', '#EAB543']   //背景色
            }]
        },
        options: {                             //◆オプション
            responsive: true,                  //グラフ自動設定
            legend: {                          //凡例設定
                display: false                 //表示設定
           },
            title: {                           //タイトル設定
                display: true,                 //表示設定
                fontSize: 18,                  //フォントサイズ
                text: '進捗状況'                //ラベル
            },
            scales: {                          //軸設定
                yAxes: [{                      //y軸設定
                    display: true,             //表示設定
                    barPercentage: 0.4,           //棒グラフ幅
                    categoryPercentage: 0.4,      //棒グラフ幅
                    scaleLabel: {              //軸ラベル設定
                       display: true,          //表示設定
                     //  labelString: '縦軸',  //ラベル
                       fontSize: 18               //フォントサイズ
                    },
                    ticks: {                      
                        fontSize: 15            //フォントサイズ
                    },
                }],
                xAxes: [{                         //x軸設定
                    display: true,                //表示設定
                    
                    scaleLabel: {                 //軸ラベル設定
                       display: true,             //表示設定
                       //labelString: '横軸',  //ラベル
                       fontSize: 18               //フォントサイズ
                    },
                    ticks: {		//最大値最小値設定
                    	min: 0,                   //最小値
                        max: 100,                  //最大値
                        fontSize: 15,           //フォントサイズ
                        stepSize: 10               //軸間隔
                    },
                }],
            },
            layout: {                             //レイアウト
                padding: {                          //余白設定
                    left: 0,
                    right: 50,
                    top: 50,
                    bottom: 50
                }
            }
        }
    });
</script>

</body>
</html>