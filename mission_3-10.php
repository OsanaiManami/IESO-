<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>ゲーム実況者掲示板</title>
	<head>
	<input type='button' onClick="location.href='mypage.php'" value='マイページ'>
	<input type='button' onClick="location.href='category.php'" value='カテゴリ別表示'>
	</head><br/><br/>

<body>
	自身のゲーム実況動画を宣伝する掲示板です。<br/>
	このサイトの改善点をコメントで教えてください。<br/><br/>

<?php
	//データベースへの接続（2-7）
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password);

	session_start();						//セッションを使用
	//ログインされていなければユーザー登録またはログインの画面へリダイレクト
	if(empty($_SESSION['se_id']) || empty($_SESSION['se_pass']) || empty($_SESSION['se_user']))
	{
		header("Location: mission_3-9.php");
		exit;
	}

	//名前、コメント、パスワード送信時、未入力の項目があれば文を表示
	if(!empty($_POST['パスワード']) || !empty($_POST['ID']))
	{
		if(empty($_POST['名前']) || empty($_POST['カテゴリ']))
			echo '名前とカテゴリは入力必須項目です。<br/>';
	}
?>

	<form  action = "mission_3-10.php" method = "post" enctype = "multipart/form-data">
	名前<br/><input type = 'text' name = '名前' value = "<?php echo $_SESSION['se_user']; ?>"><br/>
	URL<br/><input type = 'text' name = 'URL' value = "<?php echo $_SESSION['se_url']; ?>"><br/>
	コメント<br/><textarea name='コメント' rows='7' cols='50' wrap='soft'></textarea><br/>
	<input type = 'hidden' name = 'パスワード' value = "<?php echo $_SESSION['se_pass']; ?>">
	<input type = 'hidden' name = 'ID' value = "<?php echo $_SESSION['se_id']; ?>">
	画像または動画ファイル<br/>
	<input type = 'hidden' name = "MAX_FILE_SIZE" value = '3000000'>
	<input type = 'file' name = 'ファイル'><br/>
	カテゴリ<br/>
	<input type="radio" name="カテゴリ" value="普通プレイ">普通プレイ
	<input type="radio" name="カテゴリ" value="字幕プレイ">字幕プレイ
	<input type="radio" name="カテゴリ" value="縛りプレイ">縛りプレイ
	<input type="radio" name="カテゴリ" value="検証">検証
	<input type="radio" name="カテゴリ" value="改造・チート">改造・チート
	<input type="radio" name="カテゴリ" value="バグ">バグ
	<input type="radio" name="カテゴリ" value="スーパープレイ">スーパープレイ
	<input type="radio" name="カテゴリ" value="その他プレイ">その他プレイ
	<input type="radio" name="カテゴリ" value="宣伝以外" checked>宣伝以外<br/>
	<input type = 'submit' value = '送信'><br/><br/>
	<form/>

<?php
	if(empty($_POST['hidden']) && !empty($_POST['名前']) && !empty($_POST['カテゴリ']) && !empty($_POST['パスワード']) && !empty($_POST['ID']))		//編集モードでなく、名前とカテゴリのデータが送信されていたら以下の処理
	{
		//formから入力されたデータをPHPで受け取る
		$name_data = $_POST['名前'];
		$comment_data = $_POST['コメント'];
		$pass_data = $_POST['パスワード'];
		$id_data = $_POST['ID'];
		$URL = $_POST['URL'];
		$category = $_POST['カテゴリ'];

		$time = date("Y/m/d H:i:s");					//投稿時間を変数$timeに代入する

		$sql = 'SELECT * FROM BBS7';					//$sql = select文でBBSテーブルを表示
		$results = $pdo->query($sql);					//$sqlの実行結果を$resultsに代入
		$num = 1;
		foreach($results as $row)
			$num++;

		//ファイルが存在するとき
		//エラー確認
		if(!empty($_FILES['ファイル']['name']))
		{
			if($_FILES['ファイル']['error'] == 0)
				echo '';
			else if($_FILES['ファイル']['error'] == 1)
			{
				echo 'エラーコード：1';
			}
			else if($_FILES['ファイル']['error'] == 2)
			{
				echo 'エラーコード：2';
			}
			else if($_FILES['ファイル']['error'] == 3)
			{
				echo 'エラーコード：3';
			}
			else if($_FILES['ファイル']['error'] == 4)
			{
				echo 'エラーコード：4';
			}
			else if($_FILES['ファイル']['error'] == 5)
			{
				echo 'エラーコード：5';
			}
			else if($_FILES['ファイル']['error'] == 6)
			{
				echo 'エラーコード：6';
			}
			else if($_FILES['ファイル']['error'] == 7)
			{
				echo 'エラーコード：7';
			}
			else if($_FILES['ファイル']['error'] == 8)
			{
				echo 'エラーコード：8';
			}
			else echo 'その他のエラーが発生しました<br/><br/>';

			//formから入力されたデータを取得
			//データベースに格納する際のファイル名
			$file_name = pathinfo($_FILES['ファイル']['name'], PATHINFO_BASENAME).$time;
			$user_file = file_get_contents($_FILES['ファイル']['tmp_name']);	//ファイルデータを変数へ代入
			//$user_file = mysql_real_escape_string($user_file);

			//ファイルの拡張子を確認
			$path_parts = pathinfo($_FILES['ファイル']['name']);		//ファイルパスの情報を変数に代入
			$extension = $path_parts['extension'];				//拡張子部分のパスを変数に代入

			//それぞれの拡張子をjpeg/png/gif/mp4表記に統一する
			if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'JPEG')
				$extension = 'jpeg';
			else if($extension == 'png' || $extension == 'PNG')
				$extension = 'png';
			else if($extension == 'gif' || $extension == 'GIF')
				$extension = 'gif';
			else if($extension == 'mp4' || $extension == 'MP4')
				$extension = 'mp4';
			else echo '非対応のファイルです。<br/>';
		}

		//受け取ったデータの書き込み
		$sql = $pdo->prepare("INSERT INTO BBS7 (no, url, name, comment, time, pass, id, del, file_name, extension, user_file, category) VALUES (:no, :url, :name_data, :comment_data, :time, :pass_data, :id_data, :del, :file_name, :extension, :user_file, :category)");

		$sql->bindParam(':no', $num, PDO::PARAM_INT);
		$sql->bindParam(':url', $URL, PDO::PARAM_STR);
		$sql->bindParam(':name_data', $name_data, PDO::PARAM_STR);
		$sql->bindParam(':comment_data', $comment_data, PDO::PARAM_STR);
		$sql->bindParam(':time', $time, PDO::PARAM_STR);
		$sql->bindParam(':pass_data', $pass_data, PDO::PARAM_STR);
		$sql->bindParam(':id_data', $id_data, PDO::PARAM_STR);
		$sql->bindValue(':del', 1, PDO::PARAM_INT);
		$sql->bindParam(':file_name', $file_name, PDO::PARAM_STR);
		$sql->bindParam(':extension', $extension, PDO::PARAM_STR);
		$sql->bindParam(':user_file', $user_file, PDO::PARAM_LOB);
		$sql->bindParam(':category', $category, PDO::PARAM_STR);
		$sql->execute();						//prepareした内容を実行
	}

	//削除
	if(!empty ($_POST['確認']) && !empty($_POST['削除']) && !empty($_POST['削除パスワード']))		//削除は値が送信されたときのみ実行
	{
		$delete = $_POST['削除'];					//削除番号の受け取り
		$delete_pass = $_POST['削除パスワード'];			//削除フォームのパスワードを取得

		$sql = $pdo->query("SELECT pass FROM BBS7 where no = $delete");
		$result = $sql->fetch();
		//echo $result['pass'];

		if($delete_pass == $result['pass'])				//削除パスワードと削除対象番号の投稿のパスワードが一致した時
		{
			//名前を削除し、コメントを「削除しました」にupdate
			$sql = "update BBS7 set url = '', category = '', name = '', comment = '削除しました。', file_name = '', extension = '', user_file = '',  del = '0' where no = $delete";
			$result = $pdo->query($sql);
		}
	}

	//編集
	if(!empty($_POST['hidden']) && !empty($_POST['名前']) && !empty($_POST['コメント']) && !empty($_POST['パスワード']))
	{
		//formから入力されたデータをPHPで受け取る
		$edit = $_POST['hidden'];					//編集番号の受け取り
		$name_data = $_POST['名前'];
		$comment_data = $_POST['コメント'];
		$pass_data = $_POST['パスワード'];
		$url_data = $_POST['URL'];
		$category_data = $_POST['カテゴリ'];

		$time = date("Y/m/d H:i:s");					//投稿時間を変数$timeに代入

		//編集番号の内容をそれぞれ受け取ったデータにupdate。番号はそのまま
		$sql = "update BBS7 set url = '$url_data', category = '$category_data', name = '$name_data', comment = '$comment_data', time = '$time', pass = '$pass_data' where no = $edit";
		$result = $pdo->query($sql);
	}

	//selectでデータ表示
	$sql = 'SELECT * FROM BBS7 ORDER BY no desc';				//$sql = select文でtbtestテーブルを表示
	$results = $pdo->query($sql);						//$sqlの実行結果を$resultsに代入

	foreach ($results as $row)						//$resultsの数（行数）だけ繰り返す
	{
		if($row['del'] == 1)						//delが1のときのみ投稿内容が表示される（論理削除）
		{
			//各値を代入
			$no = $row['no'];					//投稿番号
			$name = $row['name'];					//名前
			$comment = $row['comment'];				//コメント
			$file_name = $row['file_name'];				//ファイル名
			$URL = $row['url'];					//URL
			$category = $row['category'];				//カテゴリ

			//$commentにURLが含まれていたらリンク
			$pat_sub = preg_quote("-._~%:/?#[]@!$&\'()*+,;=", "/");		// 正規表現向けのエスケープ処理
			$pat  = '/((http|https):\/\/[0-9a-z'. $pat_sub. ']+)/i';	// 正規表現パターン
			$rep  = '<a href="\\1" target="_blank">\\1</a>'; 		// \\1が正規表現にマッチした文字列に置き換わる
			$comment = preg_replace ($pat, $rep, $comment);			// 実処理
			$URL = preg_replace ($pat, $rep, $URL);

			//$rowの中にはテーブルのカラム名が入る
			echo $no.'.';					//投稿番号
			echo $name.'<br/>';				//名前
			echo 'URL:'.$URL.'<br/><br/>';				//URL
			echo nl2br($comment).'<br/><br/>';			//コメント

			if($row['extension'] == 'mp4')				//拡張子がmp4のとき
			{
				echo ("<video src = \"display.php?file_name=$file_name&no=$no\" alt = '動画' width=\"426\" controls></video>");	 //videoタグで動画を表示。display.phpのデータが表示される。URLパラメータで$noを渡す
				echo '<br/>';
			}
			else if($row['extension'] == 'jpeg' || $row['extension'] == 'png' || $row['extension'] == 'gif')	//拡張子がjpeg, png, gifのとき
			{
				echo ("<img src = \"display.php?file_name=$file_name&no=$no\" alt='画像' width=\"426\">");	//imgタグで画像を表示。display.phpのデータが表示される。URLパラメータで$noを渡す
				echo '<br/>';
			}
			echo $row['time'].'  '.$category.'<br/>';				//投稿時間・カテゴリ
			echo "<input type='button' onClick=\"location.href='delete.php?no=$no'\" value='削除'>";
			echo "<input type='button' onClick=\"location.href='edit.php?no=$no'\" value='編集'>";
			echo '<hr>';
		}
		else
			echo '削除しました。<br/><hr>';				//delが1以外のときは「削除しました。」と表示（論理削除）
	}
?>