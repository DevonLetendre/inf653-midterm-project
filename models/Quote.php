<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
    $query = "SELECT 
                q.id, 
                q.quote, 
                q.author_id, 
                q.category_id, 
                a.author, 
                c.category 
              FROM " . $this->table . " q
              LEFT JOIN authors a ON q.author_id = a.id
              LEFT JOIN categories c ON q.category_id = c.id"; // <--- Missing quote and semicolon were added

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
    }


    public function read_single() {
        $query = "SELECT q.id, q.quote, q.author_id, q.category_id, 
                         a.author, c.category 
                  FROM " . $this->table . " q
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
    
        // Fetch row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            // Assign properties
            $this->quote = $row['quote'];
            $this->author = $row['author'];
            $this->category = $row['category'];
        }
    }
    

    public function create() {
        $query = "INSERT INTO quotes (quote, author_id, category_id) 
          VALUES (:quote, :author_id, :category_id)";

        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->authorId = intval($this->authorId);
        $this->categoryId = intval($this->categoryId);

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);


        return $stmt->execute();
    }

    public function create2() {
        $query = 'INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Return inserted quote ID
        }
        return false;
    }


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
    
    


    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET quote = :quote, author_id = :author_id, category_id = :category_id 
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        return $stmt->execute();
    }
    
    
    

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Read quotes by author_id
    public function read_by_author() {
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    a.author AS author_name, 
                    c.category AS category_name
                FROM " . $this->table . " q
                LEFT JOIN authors a ON q.author_id = a.id
                LEFT JOIN categories c ON q.category_id = c.id
                WHERE q.author_id = :author_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $this->author_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    public function read_by_category() {
        $query = "SELECT 
                    q.id, 
                    q.quote, 
                    a.author AS author_name, 
                    c.category AS category_name
                FROM " . $this->table . " q
                LEFT JOIN authors a ON q.author_id = a.id
                LEFT JOIN categories c ON q.category_id = c.id
                WHERE q.category_id = :category_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }


    public function read_by_category_author() {
        $query = "SELECT 
                    q.id, q.quote, 
                    a.author as author_name, 
                    c.category as category_name 
                  FROM " . $this->table . " q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.category_id = :categoryId AND q.author_id = :authorId";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryId', $this->category_id);
        $stmt->bindParam(':authorId', $this->author_id);
        $stmt->execute();
    
        return $stmt;
    }

    public function exists() {
        $query = 'SELECT id FROM quotes WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        return $stmt->rowCount() > 0; // Returns true if the category exists, otherwise false
    }
    
    
    
}
?>