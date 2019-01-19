<?php
//id tanaka_tarou
//ps tanaka^tarou[
//uname 田中
//

session_start();
if( isset($_SESSION['user']) != "") {
//	header("Location: sns_login.php");
}
include_once 'sns_dbconnect.php';


if(isset($_POST['login'])) {

  $user_id = $mysqli->real_escape_string($_POST['user_id']);
  $password = $mysqli->real_escape_string($_POST['password']);
  
  // クエリの実行
  $query = "SELECT * FROM user_data WHERE user_id='$user_id'";
  $resul = $mysqli->query($query);
  if (!$result) {
    print('クエリーが失敗しました。' . $mysqli->error);
    $mysqli->close();
  }

  while ($row = $result->fetch_assoc()) {
    $db_hashed_pwd = $row['password'];
    $user_id = $row['user_id'];
  }

  if (password_verify($password, $db_hashed_pwd)) {
    $_SESSION['user'] = $user_id;
    header("Location: sns_index.php");
    exit;
  } else { ?>
    <div>IDとpasswordが一致しません</div>
<?php }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログインページ</title>
</head>

<body>
    <h1>ログイン</h1>
    <form  method="post">
	<fieldset>
	    ユーザーID:
	    <input type="text"     name="user_id" pattern="^[0-9A-Za-z-_]+$" required> ※必須項目<br>
	    <p>半角英数字、ハイフン( _ )のみ使用できます</p>
	    password:
	    <input type="password" name="password" pattern="^[!-~]+$" required> ※必須項目<br>
	    <p>半角英数字、半角記号のみ使用できます</p>
	    <button type="submit" name="login">会員登録</button>
        </fieldset>
    </form>

<a href="sns_register.php">登録がまだの方はこちら</a>
</body>
</html>
