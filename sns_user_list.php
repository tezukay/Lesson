<?php
session_start();

include_once 'sns_dbconnect.php';
if(!isset($_SESSION['user'])) {
        header("Location: sns_login.php");
}
$user_id = $_SESSION['user'];

$query = "SELECT user_id from user_data";
$result = $mysqli->query($query);


if(isset($_POST['follow'])){
  $my_id = $mysqli->real_escape_string($user_id);
  $follower = $mysqli->real_escape_string($_POST['uniid']);
$query = "INSERT INTO user_follow( my_id, follower) VALUES('$my_id','$follower')";
if($mysqli->query($query)) { ?>
            <div>フォローしました</div>
        <?php } else { ?>
            <div>エラーが発生しました</div>
        <?php
}
}
?>
<?php

foreach ($result as $row){
        if($row['user_id'] != $user_id){
        ?> <form method="post">
              <div class="ulist">
              <?php echo $row['user_id'];?>
              <input type="hidden" value="<?php echo $row['user_id'];?>" name="
uniid"  required>
              <?php
                $result = $mysqli->query("select * from user_follow where my_id
='$my_id' AND follower='$follower';");
                if(!mysqli_num_rows($result)){?>
                <button type="submit" name="follow">フォロー</button>
                <?php } ?>
                </div>
          </form><?php
}
}

if(isset($_POST['back'])){
header("Location: sns_index.php");
}
?>


<html>
<head>
</head>
<body>
<form  method="post">
  <button type="submit" name="back">戻る</button>
</form>
</body>
</html>