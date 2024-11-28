<?php
class Crud {
    private $conn;
    private $table;

    public function __construct($db, $table) {
        $this->conn = $db;
        $this->table = $table;
    }

    public function create($data) {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function read($conditions = []) {
        $query = "SELECT * FROM $this->table";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", array_map(fn($k) => "$k = :$k", array_keys($conditions)));
        }

        $stmt = $this->conn->prepare($query);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($data, $conditions) {
        $fields = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($data)));
        $where = implode(" AND ", array_map(fn($k) => "$k = :cond_$k", array_keys($conditions)));

        $query = "UPDATE $this->table SET $fields WHERE $where";
        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":cond_$key", $value);
        }

        return $stmt->execute();
    }

    public function delete($conditions) {
        $where = implode(" AND ", array_map(fn($k) => "$k = :$k", array_keys($conditions)));

        $query = "DELETE FROM $this->table WHERE $where";
        $stmt = $this->conn->prepare($query);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
}
?>
