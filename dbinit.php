<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "vineel_bookstore_4266";


$conn = new mysqli($servername, $username, $password);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}


$conn->select_db($dbname);

// SQL to create table
$table_sql = "CREATE TABLE IF NOT EXISTS books (
    BookID INT(10) AUTO_INCREMENT PRIMARY KEY,
    BookName VARCHAR(30) NOT NULL,
    Description TEXT NOT NULL,
    QuantityAvailable INT(40) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    ProductAddedBy VARCHAR(255) NOT NULL DEFAULT 'Vineel Bheemanadham'
)";

if ($conn->query($table_sql) === TRUE) {
    echo "Table 'books' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
