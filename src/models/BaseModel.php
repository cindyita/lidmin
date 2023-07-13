<?php 
class BaseModel {
    private $db;
    private $data;
    public function __construct(){
        $this->data = array();
        $host = DB_HOST;
        $dbname = "theblu48_lidmin";
        $user = "theblu48_lidminadmin";
        $pass = "es5P(ef?Nhrd";
        $this->db = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function executeQuery($query, $params) {
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt;
    }

    public function query($query, $params = array()) {
        try {
            $sanitizedParams = $this->sanitizeData($params);
            $stmt = $this->executeQuery($query, $sanitizedParams);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e;
        }
    }
        
    public function insert($table, $data) {
        $sanitizedData = $this->sanitizeData($data);

        $columns = implode(", ", array_keys($sanitizedData));
        $placeholders = ":" . implode(", :", array_keys($sanitizedData));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($sanitizedData);
            return 1;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function select($table, $condition) {
        $query = "SELECT * FROM $table WHERE $condition";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $this->data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->data = array();
        }
        
        return $this->data;
    }

    public function update($table, $data, $condition) {
        $sanitizedData = $this->sanitizeData($data);

        $setValues = [];
        foreach ($sanitizedData as $key => $value) {
            $setValues[] = $key . " = :" . $key;
        }
        $setClause = implode(", ", $setValues);

        $query = "UPDATE $table SET $setClause WHERE $condition";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($sanitizedData);
            return 1;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function delete($table, $condition) {
        $query = "DELETE FROM $table WHERE $condition";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return 1;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function lastid($table){
        $query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $this->data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->data = array();
        }
        
        return $this->data;
    }

    private function sanitizeData($data) {
        if (is_string($data)) {
            $sanitizedData = preg_replace('/[^a-zA-Z0-9 ]/', '', $data);
        } elseif (is_int($data)) {
            $sanitizedData = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        } elseif (is_float($data)) {
            $sanitizedData = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        } else {
            $sanitizedData = $data;
        }

        return $sanitizedData;
    }

}