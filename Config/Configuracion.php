<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuracion
 *
 * @author joshe.onate
 */
class Configuracion {
    
    public static $DB_NAME = 'FAM';
    public static $DB_USER = 'sa';
    public static $DB_PASS = "informatica.2014*";
    public static $DB_SERVER = "localhost";
    
    function getDB_NAME() {
        return $this->DB_NAME;
    }

    function getDB_USER() {
        return $this->DB_USER;
    }

    function getDB_PASS() {
        return $this->DB_PASS;
    }

    function getDB_SERVER() {
        return $this->DB_SERVER;
    }


}
