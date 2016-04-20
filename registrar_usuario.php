<?php 

include_once 'clases/Database.php';
include_once 'Config/login/Autentificacion.php';
include_once 'Config/login/funciones.php';
 
$error_msg = "";
$db = new Database();

if (isset($_POST['dominio'], $_POST['email'], $_POST['p'], $_POST['tipo'] )) {
    
    $password = $_POST['p'];
    $usuario = $_POST['email'];
    $email = $_POST['email'].$_POST['dominio'];
    $tipo = $_POST['tipo'];
    
    if($tipo == 'ALUMNO'){
        $id_perfil = 2;
        $rut_alum = $_POST['rut'];
    }else{
        //ES PROFESOR
        $id_perfil = 3;
        $rut_alum = null;
    }
    
    
    if (strlen($password) < 6) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<div class="alert alert-danger"><strong>Error!</strong> Configuración de contraseña incorrecta.</div>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
    
    $sql = "SELECT * FROM $db->DB_NAME.dbo.SGU_USUARIO WHERE EMAIL = :EMAIL";
    $stmt = $db->conn->prepare($sql);
    $stmt->bindParam(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
 
    // check existing email  
    if (!empty($result)) {
        // A user with this email address already exists
        $error_msg .= '<div class="alert alert-danger"><strong>Error!</strong> Usuario con ese correo ya existe.</div>';
    }
    
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) {
        
        // Create hashed password using the password_hash function.
        // This function salts it with a random salt and can be verified with
        // the password_verify function.
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Insert the new user into the database 
        try{
            $insert_stmt = $db->conn->prepare("INSERT INTO $db->DB_NAME.dbo.SGU_USUARIO (USUARIO, EMAIL, CONTRASENA, RUT_ALUMNO, ID_PERFIL)"
                . " VALUES (:USUARIO, :EMAIL, :CONTRASENA, :RUT_ALUMNO, :ID_PERFIL)");
        
            $insert_stmt->bindParam(':USUARIO', $usuario, PDO::PARAM_STR);
            $insert_stmt->bindParam(':EMAIL', $email, PDO::PARAM_STR);
            $insert_stmt->bindParam(':CONTRASENA', $password, PDO::PARAM_STR);
            $insert_stmt->bindParam(':RUT_ALUMNO', $rut_alum, PDO::PARAM_STR);
            $insert_stmt->bindParam(':ID_PERFIL', $id_perfil, PDO::PARAM_INT);

            if (!$insert_stmt->execute()) {
                $error_msg = '<div class="alert alert-danger"><strong>Error!</strong> Registro de usuario fallido.</div>';
            }else{
                $error_msg = '<div class="alert alert-success">Usuario registrado exitosamente.</div>';
            }
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
        
    }

    echo "<script>window.location='login.php?msg=".$error_msg."'</script>";
    
}else{
    header("Location: login.php");
}

