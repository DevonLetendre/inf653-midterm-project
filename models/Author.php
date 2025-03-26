<?php
    class Author {
        //DB stuff
        private $conn;
        private $table = 'authors';

        //Properties
        public $id;
        public $author;

        //Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        //Get authors
        public function read(){

            //Create query
            $query = 'SELECT
                        id,
                        author
                    FROM
                        ' . $this->table . '
                    ORDER BY 
                        id DESC';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Execute query
            $stmt->execute();

            //Return the statement object so it can be handled in read.php
            return $stmt;
        }

        //Get single author
        public function read_single(){
            // Create query
            $query = 'SELECT id, author FROM authors WHERE id = :id LIMIT 1';
        
            // Prepare statement
            $stmt = $this->conn->prepare($query);
        
            // Bind ID
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        
            // Execute query
            $stmt->execute();
        
            // Fetch
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($row) {
                // Set properties if found
                $this->id = $row['id'];
                $this->author = $row['author'];
            } else {
                // If not found, set ID to null
                $this->id = null;
            }
        }

        //Create author
        public function create() {

            //Create query (corrected for postGre SQL)
            $query = 'INSERT INTO ' . $this->table . '
                    (author)
                    VALUES
                    (:author)';
                    
            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->author));

            //Bind data
            $stmt->bindParam(':author', $this->author);

            /*OLD
            //Execute query
            if($stmt->execute()){
                return true;
            }
            */

            //Execute query
            if($stmt->execute()){
                // Retrieve the last inserted ID
                $this->id = $this->conn->lastInsertId();  // This will give the ID of the new author
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        //Update post
        public function update() {

            //Create query (corrected for postGre SQL)
            $query = 'UPDATE ' . $this->table . ' 
                    SET 
                        author = :author
                    WHERE
                        id = :id';
                        
            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean & sanitize data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute query
            if($stmt->execute()){
                // Check if any rows were updated
                if ($stmt->rowCount() > 0){
                    return true; // Successful update
                } 
                else{
                    // No rows updated, likely the author doesn't exist or the new value is the same
                    return false;
                }
            }

            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        //Delete author
        public function delete(){

            //Create query (updated for postGre SQL)
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //Clean/Sanitize Data
            $this->id = htmlspecialchars(strip_tags($this->id));

            //Bind data
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            // Execute query
            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->rowCount() > 0) {
                    return $this->id;  // Return the ID of the deleted author
                }
            }

            // Return false if no rows were affected (i.e., author not found)
            return false;
        }
    }
?>
