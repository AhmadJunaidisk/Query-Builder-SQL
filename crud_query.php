<?php

require_once "koneksi.php";

class Query extends DB {

    /**
     * variabel static ini berisi nama tabel
     * @return string
     */
    private static $tb;

    /**
     * variabel ini berisi statement query yang akan
     * dieksekusi di fungsi exec()
     * @return void
     */
    private $query_stmt;

    /** 
     * variabel ini berisi key dari array
     * @return string
     */
    private $key;

    /**
     * variabel ini berisi kode untuk "mentrigger" fungsi exec()
     * @return int
     */
    private $trigger_code;

    public static function table(string $arg) {
        self::$tb = $arg;
        return new static;
    }

    public function insert(array $column, array $value) {
        $column_stmt = "";
        $prepare = "";
        if(is_array($column) && is_array($value)) {
            foreach($column as $clm) {
                if($clm != $column[array_key_last($column)]) {
                    $column_stmt .= $clm.",";
                } else {
                    $column_stmt .= $clm;
                }
            }   
            for($i=1; $i<count($value)+1; $i++) {
                if($i != count($value)) {
                    $prepare .= "?,";
                } else {
                    $prepare .= "?";
                }
            }
            $query = "INSERT INTO ".self::$tb." (".$column_stmt.") VALUES (".$prepare.")";
            $stmt = $this->koneksi->prepare($query);
            $stmt->execute($value); 
        }
    }

    public function setQuery(string $query) {
        $this->query_stmt .= $query;
    }

    public function select(array $column) {
        $column_stmt = "";
        if(is_array($column)) {
            foreach($column as $q) {
                if($q != $column[array_key_last($column)]) {
                    $column_stmt .= $q.",";
                } else {
                    $column_stmt .= $q;
                }
            }
            $this->trigger_code = 0;
            self::setQuery("SELECT $column_stmt FROM ".self::$tb);
            return $this;
        }
    }

    public function where(array $arg) {
        if(is_array($arg)) {
            $this->key = $arg[array_key_first($arg)];
            self::setQuery(" WHERE ".array_key_first($arg). " = ?");
            $this->trigger_code = 1;
            return $this;
        }
    }

    public function like(array $arg) {
        if(is_array($arg)) {
            $this->key = $arg[array_key_first($arg)];
            self::setQuery(" WHERE ".array_key_first($arg). " LIKE ?");
            $this->trigger_code = 2;
            return $this;
        }
    }

    public function exec() {
        $stmt = $this->koneksi->prepare($this->query_stmt);
        if($this->trigger_code == 1) {
            $stmt->execute([$this->key]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else if($this->trigger_code == 2) {
            $stmt->execute(["%".$this->key."%"]);
            return $stmt->fetchall(PDO::FETCH_ASSOC);
        } else if($this->trigger_code == 0) {
            $stmt = $this->koneksi->prepare($this->query_stmt);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function numrows() {
        $stmt = $this->koneksi->prepare($this->query_stmt);
        if($this->trigger_code == 1) {
            $stmt->execute([$this->key]);
        } else if($this->trigger_code == 2) {
            $stmt->execute(["%".$this->key."%"]);
        }
        return $stmt->rowCount();
    }
 
}
