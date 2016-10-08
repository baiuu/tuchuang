<?php
session_start();
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $uploaddir = 'uploads/';
  $filename = sha1(uniqid(mt_rand(), true)) . '.' . pathinfo($_FILES['userfile']['name'])[extension];
  $uploadfile = $uploaddir . $filename ;
  echo $filename;
  echo '<pre>';
  if (exif_imagetype($_FILES['userfile']['tmp_name']) != IMAGETYPE_JPEG && exif_imagetype($_FILES['userfile']['tmp_name']) != IMAGETYPE_PNG){
    echo "What the fuck you have uploaded?\n";
  }
  else if (checkToken() && move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
      echo "File is valid, and was successfully uploaded.\n";
  } else {
      echo "Possible file upload attack!\n";
  }
  print "</pre>";
} else setToken();

function setToken(){
  $token = sha1(uniqid(mt_rand(), true));
  $_SESSION['csrf_token'] = $token;
}

function checkToken(){
  print_r($_POST);
  print_r($_SESSION);
  if(empty($_SESSION['csrf_token']) || ($_SESSION['csrf_token'] != $_POST['csrf_token'])){
    return false;
  } else return true;
}

?>
<form enctype="multipart/form-data" action="tu/" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>" />
    このファイルをアップロード: <input name="userfile" type="file" />
    <input type="submit" value="ファイルを送信" />
</form>
