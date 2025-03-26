<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Properties
        public $id;
        public $author;

        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        // Read all authors
        public function read() {
            // Create query
            $query = 'SELECT
                        id,
                        author
                    FROM
                        ' . $this->table . '
                    ORDER BY 
                        id DESC';
    
            // Prepare stmt
            $stmt = $this->conn->prepare($query);
            // Execute stmt
            $stmt->execute();
            //Return the statement object so it can be handled in read.php
            return $stmt;
        }

        // Read single author
        public function read_single() {
            // Create query
            $query = "SELECT 
                            id, 
                            author 
                    FROM
                        " . $this->table . " 
                    WHERE 
                        id = ? LIMIT 1";

            // Prepare stmt
            $stmt = $this->conn->prepare($query);
            // Bind ID
            $stmt->bindParam(1, $this->id);
            // Execute Query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Set object properties
                $this->id = $row['id'];
                $this->author = $row['author'];
            }
        }

        // Create author
        public function create() {
            // Create query
            $query = "INSERT INTO " . $this->table . " 
                        (author) 
                        VALUES 
                        (:author)";

            // Prepare stmt
            $stmt = $this->conn->prepare($query);

            // Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind author
            $stmt->bindParam(':author', $this->author);

            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }

            return false;
        }

        // Update author
        public function update() {

            // Create query
            $query = "UPDATE " . $this->table . " 
                    SET 
                        author = :author 
                    WHERE 
                        id = :id";
            
            // Prepare stmt
            $stmt = $this->conn->prepare($query);

            // Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind author & ID
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            return $stmt->execute();
        }

        // Delete author
        public function delete() {

            // Create query
            $query = "DELETE FROM " . $this->table . " 
                    WHERE 
                        id = :id";

            // Prepare stmt
            $stmt = $this->conn->prepare($query);

            // Clean & Sanitize data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind id
            $stmt->bindParam(':id', $this->id);

            return $stmt->execute();
        }

        // Helper function 
        public function exists($author_id = null) {
            $id = $author_id ?? $this->id; // Use parameter if provided, otherwise default to instance property
            $query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        }
       
       /*
        // Helper method
        public function exists() {
            $query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        
            return $stmt->rowCount() > 0; // Returns true if the author exists, otherwise false
        }

        public function exists2() {
            $query = "SELECT id FROM authors WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        }
        
        // Helper method
        public function exists3($author_id) {
            $query = 'SELECT id FROM authors WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $author_id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        }
        */
        
    }
?>