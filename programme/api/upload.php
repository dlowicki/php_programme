<?php
if(isset($_POST['uploaded'])){
  if($_POST['uploaded'] == "12345"){
    $name = $_POST['title'];

    if(fileExists($name)){
      echo "Das Bild befindet sich bereits in der Datenbank";
      unlink("../img/temp.jpg");
      return;
    }

    if(renameUpload($name)){
      echo "Das Bild wurde hochgeladen";
      return;
    }
    echo "Das Bild konnte nicht hochgeladen werden!";
    return;
  }
}

if(isset($_FILES)){
  if($_FILES['file']['type'] != "image/jpeg"){
    echo "format";
    return;
  }
  if ( 0 < $_FILES['file']['error'] ) {
      echo 'error';
  } else {
      move_uploaded_file($_FILES['file']['tmp_name'], "../img/temp.jpg");
      echo "uploaded";
      return true;
  }
}

function fileExists($d) {
  if(file_exists("../img/".$d.".jpg")){
    return true;
  }
  return false;
}

function renameUpload($name) {
    $img = '../img/' . $name . '.jpg';
    if(rename("../img/temp.jpg", $img)){
      return true;
    }
    return false;
  }

  ?>
