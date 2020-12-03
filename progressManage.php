<!DOCTYPE html>
<html class="no-js" lang="ja">
<head>
<meta charset="utf-8">
<title>進捗管理アプリ</title>
<link rel="stylesheet" href="progress.css">
</head>
<body>
<div class="header">
    <div class="navbar">
    <h1>進捗管理アプリ</h1>
        <ul>
            <li><a href="add_info.php">進捗グラフをみる(ログイン)</a></li>
        </ul>
    </div>
</div>

<div class="first_form">
    <div class="content">
    <form action="post_info.php" method="post">
        <ul>
            <li>
                <label for="name">書籍タイトル</label>
                <input type="text" name="name"><br >
            </li>
            <li>
                <label for="date">終了予定日 </label>
                <input type="date" name="date"><br>
            </li>
            <li>
                <label for="pages">総ページ数 </label>
                <input type="number" name="pages"><br>
            </li>
            <li>
                <label for="pages">現在のページ数 </label>
                <input type="number" name="current_page"><br>
            </li>
            <li>
                <label for="rest">ペース </label>
                <select name="rest">
                <option value="">--選択してください--</option>
                <option value="0">毎日</option>
                <option value="1">週6日</option>
                <option value="2">週5日</option>
                <option value="3">週4日</option>
                <option value="4">週3日</option>
                <option value="5">週2日</option>
                <option value="6">週1日</option>
                </select>
            </li>
            <li>
                <input type="submit" value="送信">
            </li>
        </ul>
    </form>
    </div>
</div>

</body>
</html>