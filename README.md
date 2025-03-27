# INF653 Back End Web Development - Midterm Project

## Project Title: PHP OOP REST API for Quotations

### Author: Devon Letendre

### REST API Directory URL: https://inf653-midterm-project-osa4.onrender.com/api/
### Public Webpage URL: https://inf653-midterm-project-osa4.onrender.com

## Description
This project is a PHP-based REST API that manages famous and user-submitted quotations. The API follows an Object-Oriented Programming (OOP) approach and supports CRUD operations for quotes, authors, and categories using MySQL or PostgreSQL.

## Features
- RESTful API built with PHP OOP.
- Supports JSON responses for all endpoints.
- Provides CRUD operations for:
  - Quotes (id, quote, author, category)
  - Authors (id, author)
  - Categories (id, category)
- Handles appropriate error responses for missing or incorrect parameters.
- Includes an optional random quote retrieval feature.
- Can be tested using Postman.

## Database Schema
The project uses a relational database named `quotesdb` with three tables:

### `quotes`
| Column      | Type        | Constraints            |
|------------|------------|-----------------------|
| id         | INT        | PRIMARY KEY, AUTO_INCREMENT |
| quote      | TEXT       | NOT NULL             |
| author_id  | INT        | FOREIGN KEY (authors.id), NOT NULL |
| category_id| INT        | FOREIGN KEY (categories.id), NOT NULL |

### `authors`
| Column  | Type   | Constraints            |
|---------|--------|-----------------------|
| id      | INT    | PRIMARY KEY, AUTO_INCREMENT |
| author  | TEXT   | NOT NULL             |

### `categories`
| Column   | Type   | Constraints            |
|----------|--------|-----------------------|
| id       | INT    | PRIMARY KEY, AUTO_INCREMENT |
| category | TEXT   | NOT NULL             |

## API Endpoints

### **GET Requests**
| Endpoint | Description |
|----------|-------------|
| `/quotes/` | Returns all quotes. |
| `/quotes/?id=4` | Returns a specific quote by ID. |
| `/quotes/?author_id=10` | Returns all quotes from a specific author. |
| `/quotes/?category_id=8` | Returns all quotes in a specific category. |
| `/quotes/?author_id=3&category_id=4` | Returns all quotes matching an author and category. |
| `/authors/` | Returns all authors. |
| `/authors/?id=5` | Returns a specific author by ID. |
| `/categories/` | Returns all categories. |
| `/categories/?id=7` | Returns a specific category by ID. |

### **POST Requests**
| Endpoint | Required Fields | Response |
|----------|----------------|----------|
| `/quotes/` | quote, author_id, category_id | Created quote (id, quote, author_id, category_id) |
| `/authors/` | author | Created author (id, author) |
| `/categories/` | category | Created category (id, category) |

### **PUT Requests**
| Endpoint | Required Fields | Response |
|----------|----------------|----------|
| `/quotes/` | id, quote, author_id, category_id | Updated quote (id, quote, author_id, category_id) |
| `/authors/` | id, author | Updated author (id, author) |
| `/categories/` | id, category | Updated category (id, category) |

### **DELETE Requests**
| Endpoint | Required Fields | Response |
|----------|----------------|----------|
| `/quotes/` | id | Deleted quote ID |
| `/authors/` | id | Deleted author ID |
| `/categories/` | id | Deleted category ID |

### **Error Messages**
| Condition | Response |
|-----------|----------|
| No quotes found | `{ message: 'No Quotes Found' }` |
| No authors found | `{ message: 'author_id Not Found' }` |
| No categories found | `{ message: 'category_id Not Found' }` |
| Missing parameters | `{ message: 'Missing Required Parameters' }` |

## Installation & Setup
1. Clone the repository:
   ```sh
   git clone https://github.com/your-username/your-repo.git
   cd your-repo
   ```
2. Configure the database:
   - Create a database named `quotesdb`.
   - Import the provided SQL file to create tables.
3. Configure environment settings in `config.php`:
   ```php
   define('DB_HOST', 'your-database-host');
   define('DB_USER', 'your-database-username');
   define('DB_PASS', 'your-database-password');
   define('DB_NAME', 'quotesdb');
   ```
4. Start the server:
   ```sh
   php -S localhost:8000 -t public
   ```
5. Test the API using Postman or a browser.

## Testing
- Use [Postman](https://www.postman.com/downloads/) to test API endpoints.
- Run sample requests to ensure the expected JSON responses.

