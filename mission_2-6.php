<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>ゲームブログ</title>
	<head>ゲームブログ</head><br/><br/>
<body>
	オススメのお菓子を教えてください。<br/><br/>
	<?php
		$filename = 'kadai6.txt';					//テキストファイル名を変数$filenameに代入する

		if(file_exists($filename) == false)
		{
			touch($filename);
		}

		//未入力の表示
		if(!empty($_POST['有無']) && ((!empty($_POST['名前']) || !empty($_POST['コメント']) || !empty($_POST['パスワード']))))
		{
			if(empty($_POST['名前']) || empty($_POST['コメント']) || empty($_POST['パスワード']))
				echo "未入力の項目があります。<br>";
		}

		//削除パスワードが間違っているとき
		if(!empty($_POST['削除確認前']) && !empty($_POST['削除パスワード確認前']))
		{
			$data_array = file($filename);
			$delete = $_POST['削除確認前'];
			$delete_pass = $_POST['削除パスワード確認前']."\n";	//削除フォームのパスワードを取得。$pass_dataとバイト数をあわせるため\nを足す
			$pass = explode("<>", $data_array[$delete - 1]);	//削除対象番号の投稿のパスワードを取得
			if($delete_pass != $pass[4])				//削除パスワードと削除対象番号の投稿のパスワードが一致した時
			{
				echo "パスワードが間違っています。<br><br>";	//パスワードが間違っていることを表示
				$num = 0;
			}
			else							//削除パスワードがあっているとき$numは1
				$num = 1;
		}

		//編集モード
		if(!empty($_POST['編集']))							//編集は値が送信されたときのみ実行
			{
				$filename = 'kadai6.txt';					//テキストファイル名を変数$filenameに代入する
				$data_array = file($filename);					//テキストファイルを1行ずつ配列にいれる
				$count = count($data_array);					//配列の要素数をカウント
				$edit_num = $_POST['編集'];					//編集番号の受け取り
				$edit_pass = $_POST['編集パスワード']."\n";			//編集フォームのパスワードを取得。$pass_dataとバイト数をあわせるため\nを足す

				$pass = explode("<>", $data_array[$edit_num - 1]);		//編集対象番号の投稿のパスワードを取得
				//var_dump($edit_pass);						//$edit_passに何が入っているか確認
				//var_dump($pass[4]);						//$pass[4]に何が入っているか確認

				if($edit_pass === $pass[4])					//編集パスワードと編集対象番号の投稿のパスワードが一致した時
				{

					for($i = 0; $i < $count; $i++)
					{
						$post_array = explode("<>", $data_array[$i]);	//配列ごとに"<>"で文字列を分割
						if($post_array[0] == $edit_num)			//投稿番号が編集番号が一致する時
						{
							$edit_name = $post_array[1];		//編集前の名前を変数に代入
							$edit_comment = $post_array[2];		//編集前のコメントを変数に代入
						}
					}

					//編集モードの場合のフォーム
					echo "<form  action = mission_2-6.php method = post>";
					echo "名前<br/><input type = 'text' name = '名前' value = '".$edit_name."'><br/>";			//編集前の名前をフォームに表示
					echo "コメント<br/><input type = 'text' name = 'コメント' value = '".$edit_comment."'><br/>";		//編集前のコメントをフォームに表示
					echo "パスワード<br/><input type = 'text' name = 'パスワード'><br/>";					//パスワードの入力
					echo "<input type = 'hidden' name = 'edit' value = '".$edit_num."'>";					//編集番号をhiddenで送信
					echo "<input type = 'hidden' name = '有無' value = '有'>";
					echo "<input type = 'submit' value = '送信'><br/><br/>";
				}
				else								//編集モードでない時のフォーム
				{
					echo "パスワードが間違っています。<br><br/>";		//パスワードが間違っていることを表示
					echo "<form  action = mission_2-6.php method = post>";
					echo "名前<br/><input type = 'text' name = '名前'><br/>";						//名前の入力
					echo "コメント<br/><input type = 'text' name = 'コメント'><br/>";					//コメントの入力
					echo "パスワード<br/><input type = 'text' name = 'パスワード'><br/>";					//パスワードの入力
					echo "<input type = 'hidden' name = '有無' value = '有'>";
					echo "<input type = 'submit' value = '送信'><br/><br/>";
				}
			}
		else										//編集モードでない時のフォーム
		{
			echo "<form  action = mission_2-6.php method = post>";
			echo "名前<br/><input type = 'text' name = '名前'><br/>";		//名前の入力
			echo "コメント<br/><input type = 'text' name = 'コメント'><br/>";	//コメントの入力
			echo "パスワード<br/><input type = 'text' name = 'パスワード'><br/>";	//パスワードの入力
			echo "<input type = 'hidden' name = '有無' value = '有'>";
			echo "<input type = 'submit' value = '送信'><br/><br/>";
		}

		//削除モード
		if(!empty($_POST['削除確認前']) && !empty($_POST['削除パスワード確認前']) && $num == 1)							//削除番号が送信されたとき
		{
			$delete = $_POST['削除確認前'];						//$deleteに削除番号を代入
			$delete_pass = $_POST['削除パスワード確認前'];				//$delete_passにパスワードを代入

			echo "<form  action = mission_2-6.php method = post>";
			echo "削除対象番号<br/>";
			echo "<input type = 'text' name = '削除' value = '".$delete."'><br/>";	//送信された削除番号をフォームに表示
			echo "パスワード<br/>";
			echo "<input type = 'text' name = '削除パスワード' value = '".$delete_pass."'><br/>";	//送信されたパスワードをフォームに表示
			echo "本当に削除しますか？<br/>";
			echo "<input type = 'hidden' name = 'enter' value = '1'>";		//確認済みであるかをhideenで送信
			echo "<input type = 'submit' value = 'はい'><br/><br/>";
		}

		else										//削除番号が送信されていない時
		{
			echo "削除対象番号<br/>";
			echo "<form  action = mission_2-6.php method = post>";
			echo "<input type = 'text' name = '削除確認前'><br/>";			//削除番号の入力
			echo "パスワード<br/>";
			echo "<input type = 'text' name = '削除パスワード確認前'><br/>";		//パスワードの入力
			echo "<input type = 'submit' value = '削除'><br/><br/>";
		}
	?>

	編集対象番号<br/>
	<form  action = "mission_2-6.php" method = "post">
	<input type = "text" name = "編集"><br/>
	パスワード<br/>
	<input type = "text" name = "編集パスワード"><br/>
	<input type = "submit" value = "編集"><br/>

	</form>

	<?php
		//formから入力されたデータをPHPで受け取る
		$name_data = $_POST['名前'];
		$comment_data = $_POST['コメント'];
		$pass_data = $_POST['パスワード'];

		$time = date("Y/m/d H:i:s");					//投稿時間を変数$timeに代入する

		$data_array = file($filename);					//テキストファイルを1行ずつ配列にいれる
		$next_count = count($data_array) + 1;				//配列の要素数（投稿数）＋１が次の投稿番号

		$str = $next_count.'<>'.$name_data.'<>'.$comment_data.'<>'.$time.'<>'.$pass_data;			//表示するデータを結合して変数$strに代入する

		//各データを表示する
		//echo $name_data, $comment_data, $time."<br/>";
		//echo $str;

		if(empty($_POST['edit']) && !empty($name_data) && !empty($comment_data) && !empty($pass_data))		//編集モードでなく、名前とコメントのデータが送信されていたら以下の処理
		{
		//テキストファイルに受け取った値を書き込む
		$fp = fopen($filename, 'a');					//まずはfopenのaモード（追記モード）でファイルを開く
		fwrite($fp, $str."\n");						//fopenで開いたテキストファイルに番号、名前、コメント、投稿時間を書き込む
		fclose($fp);							//fopenで開いたテキストファイルを閉じる
		}

		$ret_array = file($filename);					//ファイルを全て配列に入れる


		//削除
		if(!empty($_POST['削除']) && !empty($_POST['削除パスワード']) && !empty($_POST['enter']))		//削除は値が送信されたときのみ実行
		{
			$data_array = file($filename);				//テキストファイルを1行ずつ配列にいれる
			$count = count($data_array);				//配列の要素数をカウント
			$delete = $_POST['削除'];				//削除番号の受け取り
			$delete_pass = $_POST['削除パスワード']."\n";		//削除フォームのパスワードを取得。$pass_dataとバイト数をあわせるため\nを足す
			//var_dump($delete_pass);				//$delete_passに何が入っているか確認

			$pass = explode("<>", $data_array[$delete - 1]);	//削除対象番号の投稿のパスワードを取得
			//var_dump($pass[4]);					//$pass[4]に何が入っているか確認

			if($delete_pass === $pass[4])				//削除パスワードと削除対象番号の投稿のパスワードが一致した時
			{
				$fp = fopen($filename, 'w');			//まずはfopenのwモード（書き込みモード）でファイルを開く
				for($i = 0; $i < $count; $i++)
				{
				$post_array = explode("<>", $data_array[$i]);	//配列ごとに"<>"で文字列を分割
				if($post_array[0] != $delete)			//投稿番号と削除番号が一致しない時
					fwrite($fp, $data_array[$i]);		//fopenで開いたテキストファイルに番号、名前、コメント、投稿時間を書き込む
				else
					fwrite($fp, '削除しました。'."<br/>\n");//削除番号と一致した時は「削除しました。」と書き込む
				}
				fclose($fp);					//fopenで開いたテキストファイルを閉じる
			}
		}


		//編集
		if(!empty($_POST['edit']) && !empty($name_data) && !empty($comment_data) && !empty($pass_data))					//編集モードの時（hiddenから値が送信されているかで判断）
		{
			$data_array = file($filename);				//テキストファイルを1行ずつ配列にいれる
			$count = count($data_array);				//配列の要素数をカウント
			$edit_num = $_POST['edit'];				//編集番号の受け取り

			$fp = fopen($filename, 'w');				//fopenのwモード（書き込みモード）でファイルを開く
			for($i = 0; $i < $count; $i++)
			{
				$post_array = explode("<>", $data_array[$i]);	//配列ごとに"<>"で文字列を分割
				if($post_array[0] == $edit_num)			//投稿番号と編集番号が一致する時
				{
					$data_array[$i] = $edit_num.'<>'.$name_data.'<>'.$comment_data.'<>'.$time.'<>'.$pass_data."\n";		//内容の変更
					//echo $data_array[$i];			//入力データの確認
				}
				fwrite($fp, $data_array[$i]);			//fopenで開いたテキストファイルに内容を上書き
			}
			fclose($fp);						//fopenで開いたテキストファイルを閉じる
		}

		$ret_array = file($filename);					//ファイルを全て配列に入れる

		//取得したファイルデータ（配列）を全て表示する
		for($i = 0; $i < count($ret_array); $i++)
		{
			$pieces = explode("<>", $ret_array[$i]);		//文字列の分割
			for($j = 0; $j < 4; $j++)
				echo($pieces[$j]."<br/>");			//投稿番号ごとの分割した文字列を表示
		}


	?>


</body>
</html>