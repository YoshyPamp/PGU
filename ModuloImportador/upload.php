<?php
date_default_timezone_set("America/Santiago");

//$target_dir = "C:/inetpub/wwwroot/PGU/ModuloImportador/Documentos/";

$target_dir = "D:/Users/joshe.onate/Documents/NetBeansProjects/ProyectoPractica/ProyectoPractica/PGU/ModuloImportador/Documentos/";
$temp = explode(".",$_FILES["fileToUpload"]["name"]);
$nameFile = $temp[0]."_".date("Y_m_d_H_i").".".$temp[1];
$target_file = $target_dir . basename($nameFile);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

//Guarda variable 1 = acta o 2 = decreto
$tipo_file = $_POST['tipo'];

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
echo $_FILES["fileToUpload"]["tmp_name"];
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<script>alert('The file '". basename($nameFile). " 'has been uploaded.);</script>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

//echo "<script>window.location='index.php?upload=true&name=".$nameFile."&tipo=".$tipo_file."';</script>";
?>