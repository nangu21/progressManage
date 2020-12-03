<?php
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
 
 
 if (!empty($_POST['add_name'])) {
//$_POST['add_name']が空でないとき+form送信されたとき
//入力された name+pages+intervalで連想配列$rowを組み立てる
    $row = array(
      'id' => $max_id + 1,
      'name' => $_POST['add_name'],
      'pages' => $_POST['add_pages'],
      'rest' => $_POST['add_rest'],
      'pace' => $_POST['pace_result'],
      'racio' => $_POST['racio_result']
    );
    //$rowを配列$rowsの最後に加える
    //最初に加える場合はarray_unshift()
    array_unshift($rows, $row);
    //json形式に変換してtextbooks_info.jsonとして保存
    file_put_contents('textbooks_info.json', json_encode($rows));
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
    <div class="navbar">
        <h1><a href="progressManage.php" style="text-decoration: none;">進捗管理アプリ</a></h1><br>
    </div>
</div>
<!--全ての投稿を表示する-->
<div class="wrap">
<?php foreach($rows as $row): ?>
    <div class="item">
        <strong><?php echo h($row['name']) ?></strong>
        <p><?php echo h("1日".$row['pace']). "ページ" ?></p>
        <p>進捗度： <?= $row['racio'] ?>％</p>
    </div>
<?php endforeach ?>
</div>

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
            datasets: [{                      //データ設定
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
                    //  labelString: '横軸',  //ラベル
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