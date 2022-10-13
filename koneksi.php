<?php

class DB { 

    protected $koneksi; 

    function __construct() {
        $this->koneksi = new PDO("mysql:host=localhost; dbname=facebook_api", "root", "", array(PDO::ATTR_PERSISTENT => True));
    }

}

?>
