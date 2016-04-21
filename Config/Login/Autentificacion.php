<?php

    function login($email, $password, $db) {
        session_start();
        // Using prepared statements means that SQL injection is not possible. 
        if ($stmt = $db->conn->prepare("SELECT * FROM $db->DB_NAME.dbo.SGU_USUARIO WHERE EMAIL = :EMAIL ")) {
            
            $stmt->bindParam(':EMAIL', $email, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!empty($resultado)){
               // If the user exists we check if the account is locked
                // from too many login attempts 
                $user_id = $resultado['ID_USUARIO'];
                $perfil = $resultado['ID_PERFIL'];
                if (checkbrute($user_id, $db) == true) {
                    // Cuenta esta bloqueada 
                    // Envía mensaje a usuario diciendo que cuenta esta bloqueada.
                    return -2;
                } else {
                    
                    // Check if the password in the database matches
                    // the password the user submitted. We are using
                    // the password_verify function to avoid timing attacks.
                    if (password_verify($password, $resultado['CONTRASENA'])) {
                        // Password is correct!
                        // Get the user-agent string of the user.
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        // XSS protection as we might print this value
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['perfil'] = $perfil;
                        $_SESSION['rut_alumno'] = $resultado['RUT_ALUMNO'];
                        
                        $stmt = $db->conn->prepare("SELECT * FROM $db->DB_NAME.dbo.SGU_PERFIL WHERE ID_PERFIL = :ID_PERFIL ");
                        $stmt->bindParam(':ID_PERFIL', $perfil, PDO::PARAM_INT);
                        $stmt->execute();
                        $resultado_perfil = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        $_SESSION['nom_perfil'] = $resultado_perfil['NOMBRE_PERFIL'];
                        
                        // XSS protection as we might print this value
                        $resultado['USUARIO'] = preg_replace("/[^a-zA-Z0-9_\-. ]+/", "", $resultado['USUARIO']);
                        $_SESSION['usuario'] = $resultado['USUARIO'];
                        $_SESSION['login_string'] = hash('sha512', $resultado['CONTRASENA'] . $user_browser);
                        // Login successful.
                        
                        return 0;
                    } else {
                        // Password is not correct
                        // We record this attempt in the database
                        $now = time();
                        $db->conn->query("INSERT INTO $db->DB_NAME.dbo.SGU_INTENTOS_LOGIN(ID_USUARIO, TIEMPO) "
                                . "VALUES ('$user_id', '$now')");
                        return -1;
                    }
                } 
            }else{
                // No user exists.
                return -3;
            }
        }
    }
    
    function checkbrute($user_id, $db) {
        // Get timestamp of current time 
        $now = time();

        // All login attempts are counted from the past 2 hours. 
        $valid_attempts = $now - (2 * 60 * 60);

        if ($stmt = $db->conn->prepare("SELECT TIEMPO FROM $db->DB_NAME.dbo.SGU_INTENTOS_LOGIN "
                . "WHERE ID_USUARIO = :ID_USUARIO AND TIEMPO > :TIEMPO")) {
            
            $stmt->bindParam('ID_USUARIO', $user_id, PDO::PARAM_INT);
            $stmt->bindParam('TIEMPO', $valid_attempts, PDO::PARAM_STR);
            // Execute the prepared query. 
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            // If there have been more than 5 failed logins 
            if (count($resultado) > 5) {
                return true;
            } else {
                return false;
            }
        }
    }

