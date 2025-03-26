<?php
class Quote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Read quotes (single, multiple etc.)
    public function read() {
    $query = "SELECT 
                q.id, 
                q.quote, 
                q.author_id, 
                q.category_id, 
                a.author, 
                c.category 
              FROM 
                " . $this->table . " q
              LEFT JOIN authors a ON q.author_id = a.id
              LEFT JOIN categories c ON q.category_id = c.id";
    
    // Prepare stmt
    $stmt = $this->conn->prepare($query);

    $stmt->execute();
    return $stmt;
    }

    // Read single quote
    public function read_single() {
        // Creat query
        $query = "SELECT q.id, q.quote, q.author_id, q.category_id, 
                         a.author, c.category 
                  FROM 
                        " . $this->table . " q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id
                  LIMIT 1";
    
        // Prepare statement
        $stmt = $this->conn->prepare($query);
    
        // Bind ID
        $stmt->bindParam(':id', $this->id);
    
        // Execute query
        $stmt->execute();
    
        // Fetch result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            // Set properties of the object if quote found
            $this->quote = $row['quote'];
            $this->author = $row['author'];
            $this->category = $row['category'];
        }
    }
    
    // Create quote
    public function create() {
        // Create query
        $query = "INSERT INTO quotes (quote, author_id, category_id) 
                    VALUES (:quote, :author_id, :category_id)";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);
        
        /*
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = intval($this->authorId);
        $this->categoryId = intval($this->categoryId);
        */
        // Clean & sanitize data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind the parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        return $stmt->execute();
    }

    // Helper method
    public function create3() {
        $query = "INSERT INTO quotes (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Return the newly inserted quote ID
        }
        
        return false;
    }

    // Update a quote
    public function update() {
        // Create query
        $query = "UPDATE " . $this->table . " 
                  SET 
                    quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE 
                    id = :id";
    
        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Clean & sanitize data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind the parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }

    // Delete a quote
    public function delete() {
        // Create query
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        //Clean/Sanitize Data
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind data
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Read quotes by author_id
    public function read_by_author() {
        // Create query
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    a.author AS author_name, 
                    c.category AS category_name
                FROM " . $this->table . " q
                LEFT JOIN authors a ON q.author_id = a.id
                LEFT JOIN categories c ON q.category_id = c.id
                WHERE q.author_id = :author_id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);

        $stmt->execute();
        
        return $stmt;
    }

    // Read quotes by category_id
    public function read_by_category() {
        // Create query
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    a.author AS author_name, 
                    c.category AS category_name
                FROM " . $this->table . " q
                    LEFT JOIN authors a ON q.author_id = a.id
                    LEFT JOIN categories c ON q.category_id = c.id
                WHERE 
                    q.category_id = :category_id";

        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt;
    }

    // Get quote by category and author id
    public function read_by_category_author() {
        // Create query
        $query = "SELECT 
                    q.id, q.quote, 
                    a.author as author_name, 
                    c.category as category_name 
                  FROM " . $this->table . " q
                    LEFT JOIN authors a ON q.author_id = a.id
                    LEFT JOIN categories c ON q.category_id = c.id
                  WHERE 
                    q.category_id = :categoryId AND q.author_id = :authorId";
    
        // Prepare stmt
        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':categoryId', $this->category_id);
        $stmt->bindParam(':authorId', $this->author_id);

        $stmt->execute();
    
        return $stmt;
    }

    // Helper method
    public function exists() {
        $query = 'SELECT id FROM quotes WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt->rowCount() > 0; // Returns true if the category exists, otherwise false
    }
}
?>