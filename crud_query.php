<?php

require_once "koneksi.php";

class Query extends DB {

    private $stmt_query;
    private $trigger_code;
    private static $tb;
    private static $klausa = [];
    private $key;
    private $key_execute;
    private $update_param = [];
    private $insert_update_trigger = 0;

    public static function table(string $arg) {
        self::$tb = $arg;
        return new static;
    }

    public function setQuery(string $query) {
        $this->stmt_query .= $query;
    }

    public function select($kolom) {
        $nama_kolom = "";
        if(is_array($kolom)) {
            foreach($kolom as $q) {
                if($q != $kolom[array_key_last($kolom)]) {
                    $nama_kolom .= $q.",";
                } else {
                    $nama_kolom .= $q;
                }
            }
            self::setQuery("SELECT $nama_kolom FROM ".self::$tb);
        } else if($kolom == "*") {
            self::setQuery("SELECT * FROM ".self::$tb);
        }
        $this->trigger_code = "__SELECT__";
        return $this;
    }

    public function insert(array $column, array $value) {
        $tanda = "";
        $prepare = "";
        $this->iu_update = $value;
        if(is_array($column) && is_array($value)) {
            foreach($column as $clm) {
                if($clm != $column[array_key_last($column)]) {
                    $tanda .= $clm.",";
                } else {
                    $tanda .= $clm;
                }
            }   
            for($i=1; $i<count($value)+1; $i++) {
                if($i != count($value)) {
                    $prepare .= "?,";
                } else {
                    $prepare .= "?";
                }
            }
            self::setQuery("INSERT INTO ".self::$tb." (".$tanda.") VALUES (".$prepare.")");
            $this->insert_update_trigger = 1;
            return $this;
        }
    }

    public function update(array $column, array $value) {
        $tanda = "";
        $prepare = "";
        $loop = -1;
        if(is_array($column) && is_array($value)) {
            foreach($column as $clm) {
                $loop += 1;
                if($clm != $column[array_key_last($column)]) {
                    $tanda .= $clm." = ? ,";
                } else {
                    $tanda .= $clm." = ?";
                }
                $this->update_param[] .= $value[$loop];
            }   
            self::setQuery("UPDATE ".self::$tb." SET ".$tanda);
            $this->insert_update_trigger = 2;
            return $this;
        }
    }

    public function where(array $arg) {
        if(is_array($arg)) {        
            $this->key = $arg[array_key_first($arg)];
            self::setQuery(" WHERE ".array_key_first($arg). " = ?");
            self::$klausa[] = $arg;
            if($this->insert_update_trigger == 0) {
                $this->trigger_code = "__WHERE__";
            } else if($this->insert_update_trigger == 1){
                $this->trigger_code = "__INSERT__";
            } else if($this->insert_update_trigger == 2){
                $this->trigger_code = "__UPDATE__";
                $this->update_param[] = $this->key;
            }
        }
        return $this;
    }

    public function push() {
        $stmt = $this->koneksi->prepare($this->stmt_query);
        if($this->trigger_code == "__INSERT__") {
            $stmt->execute($this->update_param); 
        } else if($this->trigger_code == "__UPDATE__") {
            $stmt->execute($this->update_param); 
        }
    }

    public function or(array $arg) {
        if(is_array($arg)) {
            self::setQuery(" OR ".array_key_first($arg). " = ?");
            self::$klausa[] = $arg;
            $this->trigger_code = "__OR__";
        }
        return $this;
    }

    public function like(array $arg) {
        if(is_array($arg)) {
            $this->key = $arg[array_key_first($arg)];
            self::setQuery(" WHERE ".array_key_first($arg). " LIKE ?");
            $this->trigger_code = "__LIKE__";
            return $this;
        }
    }

    public function get() {
        $stmt = $this->koneksi->prepare($this->stmt_query);
        if($this->trigger_code == "__SELECT__") {
            $stmt->execute();
        } else if($this->trigger_code == "__WHERE__") {
            $stmt->execute([$this->key]);
        } else if($this->trigger_code == "__OR__") {
            foreach(self::$klausa as $clause_key) {
                $this->key_execute[] .= implode($clause_key)." ";
            }
            $stmt->execute($this->key_execute);
        } else if($this->trigger_code == "__LIKE__") {
            return $this->getall();
        }
        if($stmt->rowcount() != 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return json_encode(["data" => ["status" => 400, "message" => "data kosong"]], JSON_PRETTY_PRINT);
        }
    }

    public function getall() {
        $stmt = $this->koneksi->prepare($this->stmt_query);
        if($this->trigger_code == "__SELECT__") {
            $stmt->execute();
        } else if($this->trigger_code == "__WHERE__") {
            $stmt->execute([$this->key]);
        } else if($this->trigger_code == "__OR__") {
            foreach(self::$klausa as $clause_key) {
                $this->key_execute[] .= implode($clause_key)." ";
            }
            $stmt->execute($this->key_execute);
        } else if($this->trigger_code == "__LIKE__") {
            $stmt->execute(["%".$this->key."%"]);
        } 
        if($stmt->rowcount() != 0) {
            return $stmt->fetchall(PDO::FETCH_ASSOC);
        } else {
            return json_encode(["data" => ["status" => 400, "message" => "data kosong"]], JSON_PRETTY_PRINT);
        }
    }

    public function numrows() {
        $stmt = $this->koneksi->prepare($this->stmt_query);
        if($this->trigger_code == "__SELECT__") {
            $stmt->execute();
        } else if($this->trigger_code == "__WHERE__") {
            $stmt->execute([$this->key]);
        } else if($this->trigger_code == "__OR__") {
            foreach(self::$klausa as $clause_key) {
                $this->key_execute[] .= implode($clause_key)." ";
            }
            $stmt->execute($this->key_execute);
        } else if($this->trigger_code == "__LIKE__") {
            $stmt->execute(["%".$this->key."%"]);
        } 
        return $stmt->rowCount();
    }
}

?>
