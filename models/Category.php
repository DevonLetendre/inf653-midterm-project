<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read all categories
    public function read() {
        $query = "SELECT id, category FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single category by ID
    public function read_single() {
        $query = "SELECT id, category FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->category = $row['category'];
        }
    }


    // Create a category
    public function create() {
        $query = "INSERT INTO " . $this->table . " (category) VALUES (:category)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Update a category
    public function update() {
        $query = "UPDATE " . $this->table . " SET category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a category
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function exists() {
        $query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt->rowCount() > 0; // Returns true if the category exists, otherwise false
    }

/*
    public function exists2() {
        $query = "SELECT id FROM categories WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    */

    public function exists3($category_id) {
        $query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
}
?>