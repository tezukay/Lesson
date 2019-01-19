<?php
session_start();

include_once 'sns_dbconnect.php';
if(!isset($_SESSION['user'])) {
        header("Location: sns_login.php");
}
$user_id = $_SESSION['user'];

$my_id = $mysqli->real_escape_string($user_id);

$query = "select * from user_follow where my_id='$user_id'";
$result = $mysqli->query($query);
?>

<html>
<head></head>
<body>
<p>フォロー一覧</p>

<?php
foreach ($result as $row){
    echo $row['follower'];?> <br><?php
   }
   
   if(isset($_POST['back'])){
   header("Location: sns_index.php");
   }
   ?>
   <form  method="post">
     <button type="submit" name="back">戻る</button>
   </form>
   </body></html>