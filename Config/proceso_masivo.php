<?php

    include_once '../clases/Database.php';

    $db = new Database();



    $alumnos = $db->FAM_SELECT_ALUMNOS();
    $usuarios = $db->FAM_SELECT_USUARIOS();
    
    
    foreach($alumnos as $alumno){
        $existe = false;
        foreach($usuarios as $usuario){
            if($usuario['RUT_ALUMNO'] == $alumno['RUT']){
                $existe = true;
            }
        }
        if(!$existe){
            
            $nombres = explode(", ",$alumno['NOMBRES']);
            $apellidos = explode(" ",$nombres[0]);
            $usuarioM = $nombres[1].'.'.$apellidos[0].substr($apellidos[1],0,1);
            $emailM = $usuarioM."@mayor.cl";
            $passwordM = 'umayor2016';
            $rutM = $alumno['RUT'];
            $perfilM = 2;
            
            $passwordM = password_hash($passwordM, PASSWORD_DEFAULT);
            // Insert the new user into the database 
            try{
                $insert_stmt = $db->conn->prepare("INSERT INTO $db->DB_NAME.dbo.SGU_USUARIO (USUARIO, EMAIL, CONTRASENA, RUT_ALUMNO, ID_PERFIL)"
                    . " VALUES (:USUARIO, :EMAIL, :CONTRASENA, :RUT_ALUMNO, :ID_PERFIL)");

                $insert_stmt->bindParam(':USUARIO', $usuarioM, PDO::PARAM_STR);
                $insert_stmt->bindParam(':EMAIL', $emailM, PDO::PARAM_STR);
                $insert_stmt->bindParam(':CONTRASENA', $passwordM, PDO::PARAM_STR);
                $insert_stmt->bindParam(':RUT_ALUMNO', $rutM, PDO::PARAM_STR);
                $insert_stmt->bindParam(':ID_PERFIL', $perfilM, PDO::PARAM_INT);

                if (!$insert_stmt->execute()) {
                    $error_msg = '<div class="container alert alert-danger"><strong>Error!</strong> Registro de usuario fallido. RUT: '.$alumno['RUT'].'</div>';
                    echo $error_msg."<br>";
                }else{
                    $error_msg = '<div class="container alert alert-success">Usuario registrado exitosamente. RUT: '.$alumno['RUT'].'</div>';
                    echo $error_msg."<br>";
                }
            }catch(PDOException $ex){
                echo $ex->getMessage();
            }
        }
    }
