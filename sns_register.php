<?php
session_start();

if(isset($_SESSION['user']) != ""){
	//ログイン済みの場合は飛ばす
	//header("Location: sns_login.php");
}

include_once 'sns_dbconnect.php';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規登録画面</title>
</head>

<body>

<?php

$options = [
	'cost' => 12,
];

if(isset($_POST['register'])){
        $user_id = $mysqli->real_escape_string($_POST['user_id']);
        $password = $mysqli->real_escape_string($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT, $options);
        $user_name = $mysqli->real_escape_string($_POST['user_name']);

        $query = "INSERT INTO user_data(user_id, password, user_name) VALUES('$user_id','$password','$user_name')";

        if($mysqli->query($query)) { ?>
            <div>登録しました</div>
        <?php } else { ?>
            <div>エラーが発生しました</div>
        <?php
        }
}

?>

    <h1>新規登録</h1>
    <form method="post">
	<fieldset>
	    ユーザーID:
	    <input type="text"     name="user_id" pattern="^[0-9A-Za-z-_]+$" required> ※必須項目<br>
	    <p>半角英数字、ハイフン( _ )のみ使用できます</p>
	    password:
	    <input type="password" name="password" pattern="^[!-~]+$" required> ※必須項目<br>
            <p>半角英数字、半角記号のみ使用できます</p>
	    ユーザー名:
	    <input type="text" name="user_name" required> ※必須項目<br>
	    <button type="submit" name="register">会員登録</button>
	    <a href="sns_login.php">ログインページはこちら</a>
        </fieldset>
    </form>

<h2>使用可能な文字</h2>
<ul>
	<li>英大文字：[A-Z]（26 文字）</li>
	<li>英小文字：[a-z]（26 文字）</li>
	<li>　　数字：[0-9]（10 文字）</li>
	<li>　　記号：!"#$%&'()*+,-./:;<=>?@[]^_`{|}~ (33 文字）</li>
</ul>

</body>
</html>
