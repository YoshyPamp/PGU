<?php

    function sec_session_start() {
        
        $session_name = 'sec_session_id';   // Set a custom session name
        $secure = true;
        // This stops JavaScript being able to access the session id.
        $httponly = true;
        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"],
            $cookieParams["path"], 
            $cookieParams["domain"], 
            $secure,
            $httponly);
        // Sets the session name to the one set above.
        session_name($session_name);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }// Start the PHP session 
        session_regenerate_id(true);    // regenerated the session, delete the old one. 
    }

    function login($email, $password, $db) {
        
        // Using prepared statements means that SQL injection is not possible. 
        if ($stmt = $db->conn->prepare("SELECT * FROM $db->DB_NAME.dbo.SGU_USUARIO WHERE EMAIL = :EMAIL LIMIT 1")) {
            
            $stmt->bind_param(':EMAIL', $email, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            
            if(count($resultado) > 0){
               // If the user exists we check if the account is locked
                // from too many login attempts 

                if (checkbrute($resultado[0]['ID_USUARIO'], $db) == true) {
                    // Cuenta esta bloqueada 
                    // Envía correo a usuario diciendo que cuenta esta bloqueada.
                    return false;
                } else {
                    // Check if the password in the database matches
                    // the password the user submitted. We are using
                    // the password_verify function to avoid timing attacks.
                    if (password_verify($password, $resultado[0]['CONTRASEÑA'])) {
                        // Password is correct!
                        // Get the user-agent string of the user.
                        $user_browser = $_SERVER['HTTP_USER_AGENT'];
                        // XSS protection as we might print this value
                        $user_id = $resultado[0]['ID_USUARIO'];
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                        $_SESSION['user_id'] = $user_id;
                        // XSS protection as we might print this value
                        $resultado[0]['USUARIO'] = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                    "", 
                                                                    $resultado[0]['USUARIO']);
                        $_SESSION['username'] = $resultado[0]['USUARIO'];
                        $_SESSION['login_string'] = hash('sha512', 
                                  $resultado[0]['CONTRASEÑA'] . $user_browser);
                        // Login successful.
                        return true;
                    } else {
                        // Password is not correct
                        // We record this attempt in the database
                        $now = time();
                        $db->conn->query("INSERT INTO $db->DB_NAME.dbo.SGU_INTENTOS_LOGIN(ID_USUARIO, TIEMPO) "
                                . "VALUES ('$user_id', '$now')");
                        return false;
                    }
                } 
            }else{
                // No user exists.
                return false;
            }
        }
    }
    
    function checkbrute($user_id, $db) {
        // Get timestamp of current time 
        $now = time();

        // All login attempts are counted from the past 2 hours. 
        $valid_attempts = $now - (2 * 60 * 60);

        if ($stmt = $mysqli->prepare("SELECT TIEMPO FROM $db->DB_NAME.dbo.SGU_INTENTOS_LOGIN "
                . "WHERE ID_USUARIO = :ID_USUARIO AND TIEMPO > :TIEMPO")) {
            
            $stmt->bind_param('ID_USUARIO', $user_id, PDO::PARAM_INT);
            $stmt->bind_param('TIEMPO', $valid_attempts, PDO::PARAM_STR);
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

