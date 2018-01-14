<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>ゲームブログ</title>
	<head>ゲームブログ</head><br/><br/>
<body>
	オススメのゲームを教えてください。<br/><br/>

<?php
	//データベースへの接続（2-7）
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password);

	//編集モード
	if(!empty($_POST['編集']) && !empty($_POST['編集パスワード']))	//編集は値が送信されたときのみ実行
	{
		$edit = $_POST['編集'];					//編集番号の受け取り
		$edit_pass = $_POST['編集パスワード'];			//編集フォームのパスワードを取得

		$sql = $pdo->query("SELECT * FROM BBS3 where no = $edit");
		$result = $sql->fetch();

		if($edit_pass == $result['pass'])			//編集パスワードと編集対象番号の投稿のパスワードが一致した時
		{
			//変数に編集番号、名前、コメント、パスワードが間違っていないかの確認の値を代入
			$edit_num = $edit;
			$edit_name = $result['name'];
			$edit_comment = $result['comment'];
			$pass_mis = 0;
		}
		else $pass_mis = 1;					//パスワードが間違っていたら$pass_misは1
	}

	//名前、コメント、パスワード送信時、未入力の項目があれば文を表示
	if(!empty($_POST['名前']) || !empty($_POST['コメント']) || !empty($_POST['パスワード']))
	{
		if(empty($_POST['名前']) || empty($_POST['コメント']) || empty($_POST['パスワード']))
			echo '未入力の項目があります。<br/>';
	}
?>

	<form  action = mission_2-15.php method = post>
	名前<br/><input type = 'text' name = '名前' value = "<?php echo $edit_name; ?>"><br/>
	コメント<br/><input type = 'text' name = 'コメント' value = "<?php echo $edit_comment; ?>"><br/>
	パスワード<br/><input type = 'text' name = 'パスワード'><br/>
	<input type = 'hidden' name = 'hidden' value = "<?php echo $edit_num; ?>">
	<input type = 'submit' value = '送信'><br/><br/>


<?php
	if(empty ($_POST['確認']) && !empty($_POST['削除']) && !empty($_POST['削除パスワード']))	//削除は値が送信されたときのみ実行
	{
		$delete = $_POST['削除'];				//削除番号の受け取り
		$delete_pass = $_POST['削除パスワード'];		//削除フォームのパスワードを取得

		//削除番号の投稿のパスワードを取得
		$sql = $pdo->query("SELECT pass FROM BBS3 where no = $delete");
		$result = $sql->fetch();
		//echo $result['pass'];

		if($delete_pass == $result['pass'])			//削除パスワードと削除対象番号の投稿のパスワードが一致した時
		{
			echo '本当に削除しますか？<br/>';		//確認文

			//変数に削除番号、パスワード、確認後であることを示すexistの値を代入
			$pre_del = $delete;
			$pre_del_pass = $delete_pass;
			$exist = 1;
		}
		else echo 'パスワードが違います。<br/>';		//パスワードが間違っていれば文を表示
	}

	//削除番号または削除パスワードに未入力の項目があれば文を表示
	if(!empty($_POST['削除']) && empty($_POST['削除パスワード']))
		echo 'パスワードが未入力です。<br/>';
	if(empty($_POST['削除']) && !empty($_POST['削除パスワード']))
		echo '削除対象番号が未入力です。<br/>';
?>

	削除対象番号<br/>
	<form  action = mission_2-15.php method = post>
	<input type = 'text' name = '削除' value = "<?php echo $pre_del; ?>"><br/>
	パスワード<br/>
	<input type = 'text' name = '削除パスワード' value = "<?php echo $pre_del_pass; ?>"><br/>
	<input type = 'hidden' name = '確認' value = "<?php echo $exist; ?>"><br/>
	<input type = 'submit' value = '削除'><br/><br/>

<?php
	//パスワードが間違っているときは文を表示
	if($pass_mis == 1)
		echo 'パスワードが違います。<br/>';

	//編集番号または編集パスワードに未入力の項目があれば文を表示
	if(!empty($_POST['編集']) && empty($_POST['編集パスワード']))
		echo 'パスワードが未入力です。<br/>';
	if(empty($_POST['編集']) && !empty($_POST['編集パスワード']))
		echo '編集対象番号が未入力です。<br/>';
?>

	編集対象番号<br/>
	<form  action = mission_2-15.php method = post>
	<input type = 'text' name = '編集'><br/>
	パスワード<br/>
	<input type = 'text' name = '編集パスワード'><br/>
	<input type = 'submit' value = '編集'><br/>

<?php
	if(empty($_POST['hidden']) && !empty($_POST['名前']) && !empty($_POST['コメント']) && !empty($_POST['パスワード']))		//編集モードでなく、名前とコメントのデータが送信されていたら以下の処理
	{
		//formから入力されたデータをPHPで受け取る
		$name_data = $_POST['名前'];
		$comment_data = $_POST['コメント'];
		$pass_data = $_POST['パスワード'];

		$time = date("Y/m/d H:i:s");					//投稿時間を変数$timeに代入する

		$sql = 'SELECT * FROM BBS3';					//$sql = select文でtbtestテーブルを表示
		$results = $pdo->query($sql);					//$sqlの実行結果を$resultsに代入
		$num = 1;
		foreach($results as $row)
			$num++;

		//受け取ったデータの書き込み
		$sql = $pdo->prepare("INSERT INTO BBS3 (no, name, comment, time, pass, del) VALUES (:no, :name_data, :comment_data, :time, :pass_data, :del)");	//insert intoで列に値を入れる

		$sql->bindParam(':no', $num, PDO::PARAM_INT);
		$sql->bindParam(':name_data', $name_data, PDO::PARAM_STR);	//:nameのパラメータに変数$nameを代入
		$sql->bindParam(':comment_data', $comment_data, PDO::PARAM_STR);//:commentのパラメータに変数$commentを代入
		$sql->bindParam(':time', $time, PDO::PARAM_STR);
		$sql->bindParam(':pass_data', $pass_data, PDO::PARAM_STR);
		$sql->bindValue(':del', 1, PDO::PARAM_INT);
		$sql->execute();						//prepareした内容を実行
	}


	//削除
	if(!empty ($_POST['確認']) && !empty($_POST['削除']) && !empty($_POST['削除パスワード']))		//削除は値が送信されたときのみ実行
	{
		$delete = $_POST['削除'];					//削除番号の受け取り
		$delete_pass = $_POST['削除パスワード'];			//削除フォームのパスワードを取得

		$sql = $pdo->query("SELECT pass FROM BBS3 where no = $delete");
		$result = $sql->fetch();
		//echo $result['pass'];

		if($delete_pass == $result['pass'])				//削除パスワードと削除対象番号の投稿のパスワードが一致した時
		{
			//名前を削除し、コメントを「削除しました」にupdate
			$sql = "update BBS3 set name = '', comment = '削除しました。', del = '0' where no = $delete";
			$result = $pdo->query($sql);
		}
	}


	//編集
	if(!empty($_POST['hidden']) && !empty($_POST['名前']) && !empty($_POST['コメント']) && !empty($_POST['パスワード']))
	{
		//formから入力されたデータをPHPで受け取る
		$edit = $_POST['hidden'];				//編集番号の受け取り
		$name_data = $_POST['名前'];
		$comment_data = $_POST['コメント'];
		$pass_data = $_POST['パスワード'];

		$time = date("Y/m/d H:i:s");				//投稿時間を変数$timeに代入する

		//編集番号の内容をそれぞれ受け取ったデータにupdate。番号はそのまま
		$sql = "update BBS3 set name = '$name_data', comment = '$comment_data', time = '$time', pass = '$pass_data' where no = $edit";
		$result = $pdo->query($sql);
	}


	//selectでデータ表示
	$sql = 'SELECT * FROM BBS3';					//$sql = select文でtbtestテーブルを表示
	$results = $pdo->query($sql);					//$sqlの実行結果を$resultsに代入
	
	foreach ($results as $row)					//$resultsの数（行数）だけ繰り返す
	{
		if($row['del'] == 1)
		{
			//$rowの中にはテーブルのカラム名が入る
			echo $row['no'].'.';
			echo $row['name'].'<br/>';
			echo $row['comment'].'<br/>';
			echo $row['time'].'<hr>';
		}
		else
			echo '削除しました。<br/><hr>';
	}



?>