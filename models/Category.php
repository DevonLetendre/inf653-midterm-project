<?php
class Category {
    // DB stuff
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all categories
    public function read() {
        // Create Query
        $query = "SELECT 
                    id, 
                    category 
                FROM " . $this->table . "
                ORDER BY
                    id DESC";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);
        // Execute query & return
        $stmt->execute();
        return $stmt;
    }

    // Read single category via ID
    public function read_single() {
        // Create query
        $query = "SELECT id, 
                        category 
                FROM 
                    " . $this->table . " 
                WHERE 
                    id = ? LIMIT 1";
        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        // Fetch
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->category = $row['category'];
        }
    }

    // Create a category
    public function create() {
        // Create query
        $query = "INSERT INTO " . $this->table . " 
                    (category) 
                    VALUES 
                    (:category)";
        
        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        //Clean & sanitize data
        //$this->author = htmlspecialchars(strip_tags($this->category));

        // Bind category
        $stmt->bindParam(':category', $this->category);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // Update a category
    public function update() {
        // Create query
        $query = "UPDATE " . $this->table . " 
                    SET 
                        category = :category 
                    WHERE 
                        id = :id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        //Clean & sanitize data
        //$this->author = htmlspecialchars(strip_tags($this->category));
        //$this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a category
    public function delete() {
        // Create query
        $query = "DELETE FROM " . $this->table . " 
                    WHERE 
                        id = :id";
        
        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        //Clean/Sanitize Data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Helper method
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

    // Helper method
    public function exists3($category_id) {
        $query = 'SELECT id FROM categories WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $category_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
}
?>