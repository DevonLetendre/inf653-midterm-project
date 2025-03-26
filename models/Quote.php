<?php
class Quote {
    //Database connection and table name
    private $conn;
    private $table = 'quotes';

    //Object properties
    public $id;
    public $quote;
    public $author;
    public $category;

    //Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM
                    ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id';
    
        $conditions = [];
        $params = [];
    
        //Check for filters and build the WHERE clause dynamically
        if (isset($_GET['category_id'])) {
            $conditions[] = 'q.category_id = :category_id';
            $params[':category_id'] = $_GET['category_id'];
        }
        if (isset($_GET['author_id'])) {
            $conditions[] = 'q.author_id = :author_id';
            $params[':author_id'] = $_GET['author_id'];
        }
    
        //Append WHERE conditions if any exist
        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }
    
        //Prepare and execute the statement
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
    
        $stmt->execute();
    
        //Fetch all results
        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        //Error handling for missing category_id or author_id
        if (isset($_GET['category_id'])) {
            $categoryExists = $this->checkExists('categories', 'id', $_GET['category_id']);
            if (!$categoryExists) {
                return json_encode(['message' => 'category_id Not Found']);
            }
        }
        if (isset($_GET['author_id'])) {
            $authorExists = $this->checkExists('authors', 'id', $_GET['author_id']);
            if (!$authorExists) {
                return json_encode(['message' => 'author_id Not Found']);
            }
        }
    
        //No quotes found for given filters
        if (empty($quotes)) {
            return json_encode(['message' => 'No Quotes Found']);
        }
    
        return json_encode($quotes);
    }
    
    //Utility func to check if a record exists in a given table
    private function checkExists($table, $column, $value) {
        $query = "SELECT COUNT(*) FROM $table WHERE $column = :value";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    //Read single (for when only an id is supplied)
    public function read_single() {
        //Create query to fetch a single quote
        $query = 'SELECT
                    q.id,
                    q.quote,
                    a.author,
                    c.category
                  FROM
                    ' . $this->table . ' q
                  JOIN authors a ON q.author_id = a.id
                  JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id
                  LIMIT 1';
    
        //Prepare the statement
        $stmt = $this->conn->prepare($query);
    
        //Bind the id to the statement
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    
        //Execute the query
        $stmt->execute();
    
        //Fetch the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        //Check if any result is returned
        if ($row) {
            //Set the properties of the object if quote found
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author_id = $row['author'];
            $this->category_id = $row['category'];
        } else {
            //If no quote found, set ID to null
            $this->id = null;
        }
    }

    //Create quote
    public function create() {
        //Query to insert the new quote
        $query = 'INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
    
        //Prepare the statement
        $stmt = $this->conn->prepare($query);
    
        //Clean the data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    
        //Bind the parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
    
        //Check if author_id exists
        $author_check_query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';
        $author_check_stmt = $this->conn->prepare($author_check_query);
        $author_check_stmt->bindParam(':author_id', $this->author_id);
        $author_check_stmt->execute();
        
        if ($author_check_stmt->rowCount() == 0) {
            return array('message' => 'author_id Not Found');
        }
    
        //Check if category_id exists
        $category_check_query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';
        $category_check_stmt = $this->conn->prepare($category_check_query);
        $category_check_stmt->bindParam(':category_id', $this->category_id);
        $category_check_stmt->execute();
        
        if ($category_check_stmt->rowCount() == 0) {
            return array('message' => 'category_id Not Found');
        }
    
        //Execute the query
        if ($stmt->execute()) {
            //Set the id of the new quote and return success response
            $this->id = $this->conn->lastInsertId();
            return array(
                'id' => (int)$this->id,
                'quote' => $this->quote,
                'author_id' => $this->author_id,
                'category_id' => $this->category_id
            );
        }
    
        //If the insert failed, return error message
        return array('message' => 'Quote Not Created');
    }
    
    //Update a quote
    public function update() {
        // Check if the author_id exists
        $query = "SELECT id FROM authors WHERE id = :author_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->execute();
    
        if ($stmt->rowCount() == 0) {
            return array("message" => "author_id Not Found");
        }
    
        //Check if the category_id exists
        $query = "SELECT id FROM categories WHERE id = :category_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->execute();
    
        if ($stmt->rowCount() == 0) {
            return array("message" => "category_id Not Found");
        }
    
        //Check if the quote exists by id
        $query = "SELECT id FROM quotes WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        if ($stmt->rowCount() == 0) {
            return array("message" => "No Quotes Found");
        }
    
        //Prepare the update query
        $query = "UPDATE quotes
                  SET quote = :quote, author_id = :author_id, category_id = :category_id
                  WHERE id = :id";
    
        //Prepare statement
        $stmt = $this->conn->prepare($query);
    
        //Bind the parameters
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        //Execute the query
        if ($stmt->execute()) {
            return array("id" => (int)$this->id, "quote" => $this->quote, "author_id" => $this->author_id, "category_id" => $this->category_id);
        }
    
        return array("message" => "Quote update failed");
    }

    //Delete quote
    public function delete(){

        //Create query (updated for postGre SQL)
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        //Prepare statement
        $stmt = $this->conn->prepare($query);

        //Clean/Sanitize Data
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind data
        $stmt->bindParam(':id', $this->id);

        //Execute query
        if ($stmt->execute()) {
            //Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                return (int)$this->id;  //Return the ID of the deleted quote
            }
        }

        //Return false if no rows were affected (i.e., category not found)
        return false;
    }
    
}
?>



