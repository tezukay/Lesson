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
        $user_name = $row['user_name'];
        $profile_data = $row['profile_data'];
        $icon_data = $row['icon_data'];
    }
    if(isset($_POST['change'])){
        if(!empty($_POST['re_user_name'])){
            $user_name = $mysqli->real_escape_string($_POST['re_user_name']);
        }else{
            $user_name;
        }
        if(!empty($_POST['re_profile_data'])){
            $profile_data = $mysqli->real_escape_string($_POST['re_profile_data']);
        }else{
            $profile_data;
        }
        $query = "UPDATE user_data SET user_name='$user_name', profile_data='$profil
    e_data' WHERE user_id='$user_id'";
        $result = $mysqli->query($query);
        if (!$result) {
            print('内容変更に失敗しました。' . $mysqli->error);
            $mysqli->close();
    }
}

?>

<html ="ja">
<head>
<style>
.modal {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  z-index: 2;
  text-align: center;
  background: #fff;
  padding: 30px;
}

.modal-overlay{
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 120%;
  background-color: rgba(0,0,0,0.65);
  z-index: 1;
}

.modal-open,
.modal-close {
  cursor: pointer;
}
</style>
</head>
<body>
<?php
$query = "select icon_data from user_data WHERE user_id='$user_id'";
$result = $mysqli->query($query);


if(isset($_POST['upload'])){
    $storeDir = 'img/';
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        exit('アップロードが失敗しました');
    }
    $filename = uniqid().'jpg';
    $icon_data = $storeDir.$filename;

    $query = "UPDATE user_data SET icon_data='$icon_data' WHERE user_id='$user_i
d'";
    $result = $mysqli->query($query);

    move_uploaded_file($_FILES['image']['tmp_name'], $storeDir.$filename);
}
if(isset($_POST['ulist'])){
header("Location: sns_user_list.php");
}

?>
<p>アイコンを設定する</p>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit" name="upload" value="upload">
</form>
<img src="<?php echo $icon_data; ?>" />
<p>ユーザー名:<?php echo $user_name; ?></p>
<p>自己紹介文:<?php echo $profile_data; ?></p>
<p><button class="modal-open">プロフィールを変更</button></p>
<div class="modal">
  <form method="post" action="sns_index.php">
  <div class="modal-cont">
      <p>ユーザー名</p><input type="text" name="re_user_name"  required>
      <p>自己紹介文</p><input type="text" name="re_profile_data" maxlength="50"
required>
  </div>
  <div class="btn-modal-close"><button type="submit" class="modal-close" name="c
hange">内容を変更</button></div>
  <div class="btn-modal-close"><a class="modal-close">閉じる</a></div>
  </form>
</div>

<?php
if(isset($_POST['foll'])){
header("Location: sns_follow_list.php");
}
?>
<form method="post">
<button type="submit" name="foll">フォローしてるユーザー</button>
</form>

<form  method="post">
  <button type="submit" name="ulist">ユーザーを探す</button>
</form>

<?php
if(isset($_POST['tw'])){
$user_id_z = $mysqli->real_escape_string($user_id);
$tweet = $mysqli->real_escape_string($_POST['tweet']);

$query = "INSERT INTO tweet(user_id_z, tweet) VALUES('$user_id_z','$tweet')";

if($mysqli->query($query)) { ?>
            <div>つぶやけました</div>
            <?php } else { ?>
            <div>エラーが発生しました</div>
        <?php
}

}
?>

<form method="post">
<input type="text" name="tweet" maxlength="150" required>
<button type="submit" name="tw">つぶやく</button>
</form>
</div>
<?php
if(isset($_POST['logout'])) {
  session_destroy();
  unset($_SESSION['user']);
  header("Location: sns_login.php");
}
?>
<form method="post">
<button type="submit" name="logout">ログアウト</button>
</form>

<h2>ツイート一覧</h2>
<?php

//$query = "SELECT * FROM tweet WHERE user_id_z='$user_id'";
$query = "select c.follower, a.user_id_z, a.tweet
from user_follow AS c, tweet AS a
where c.my_id=a.user_id_z;
";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
        $user_id_z = $row['user_id_z'];
        $tweet = $row['tweet'];
        ?><div><?php
                if($user_id_z == $user_id){
                        echo $user_id_z; ?> : <?php echo $tweet; ?></div> <?php
                }
}

?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(function(){
  //オーバーレイとコンテンツの表示
  $(".modal-open").click(function(){
    $(this).blur() ; //ボタンからフォーカスを外す
    if($( ".modal-overlay")[0]) return false; //新たにオーバーレイが追加される>のを防ぐ
    $("body").append('<div class="modal-overlay"></div>'); //オーバーレイ用のHTMLをbody内に追加
    $(".modal-overlay").fadeIn("slow"); //オーバーレイの表示
    $(".modal").fadeIn("slow"); //モーダルウインドウの表示

    //モーダルウインドウの終了
    $(".modal-overlay,.modal-close").unbind().click(function(){
      $( ".modal,.modal-overlay" ).fadeOut( "slow" , function(){ //閉じるボタン
かオーバーレイ部分クリックでフェードアウト
        $('.modal-overlay').remove(); //フェードアウト後、オーバーレイをHTMLか>
ら削除
      });
    });
  });
});
</script>
</body>
</html>





