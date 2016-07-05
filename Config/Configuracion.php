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
    public static $DB_PASS = "informatica.2015*";
    public static $DB_SERVER = "172.16.170.9";
    
//    public static $DB_NAME = 'FAM';
//    public static $DB_USER = 'valentys_sql';
//    public static $DB_PASS = "valentys.2012*";
//    public static $DB_SERVER = "172.16.39.64";
    
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
