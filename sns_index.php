<?php
session_start();

include_once 'sns_dbconnect.php';
if(!isset($_SESSION['user'])) {
	header("Location: sns_login.php");
}
$user_id = $_SESSION['user'];

$query = "SELECT * FROM user_data WHERE user_id='$user_id'";
$result = $mysqli->query($query);

$result = $mysqli->query($query);
if (!$result) {
	print('クエリーが失敗しました。' . $mysqli->error);
	$mysqli->close();
	exit();
}

while ($row = $result->fetch_assoc()) {
	var_dump($row['user_id']);
	$user_name = $row['user_name'];
}

$result->close();
?>

<html ="ja">
<head>
<head>
<body>
<p>ユーザー名:<?php echo $user_name; ?></p>
<p>自己紹介文:</p>
</body>
</html>
