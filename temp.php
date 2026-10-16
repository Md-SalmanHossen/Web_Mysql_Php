<?php
$conn = mysqli_connect("localhost", "root, "");
if (!$conn) die("Connection failed");

// Create database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS sundarban");
mysqli_select_db($conn, "sundarban");

// Create table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS sales_data (
    SaleID INT PRIMARY KEY,
    ProductName VARCHAR(100),
    CategoryID INT,
    CategoryName VARCHAR(100),
    Quantity INT,
    Revenue INT
)");

// Insert data
mysqli_query($conn, "INSERT IGNORE INTO sales_data VALUES
(1,'Laptop',301,'Electronics',5,350000),
(2,'Mouse',301,'Electronics',15,45000),
(3,'Chair',302,'Furniture',8,64000),
(4,'Desk',302,'Furniture',6,72000),
(5,'Bottle',303,'Accessories',20,30000),
(6,'Pen',303,'Accessories',25,20000)");

// Function to display table
function showTable($conn, $sql) {
    $result = mysqli_query($conn, $sql);

    echo "<table border='1' cellpadding='8'><tr>";
    while ($field = mysqli_fetch_field($result))
        echo "<th>{$field->name}</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value)
            echo "<td>$value</td>";
        echo "</tr>";
    }
    echo "</table><br>";
}

// Query 1: Total revenue by category
echo "<h3>Query 1 Result</h3>";
showTable($conn, "SELECT CategoryName, SUM(Revenue) AS TotalRevenue
FROM sales_data GROUP BY CategoryName ORDER BY TotalRevenue DESC");

// Query 2: Update low revenue products
echo "<h3>Query 2 Result</h3>";
if (mysqli_query($conn, "UPDATE sales_data
SET CategoryName='Low Performing' WHERE Revenue < 40000"))
    echo "Categories updated successfully!";

// Query 3: Add 10% bonus
echo "<h3>Query 3 Result</h3>";
if (mysqli_query($conn, "UPDATE sales_data
SET Revenue=Revenue*1.10 WHERE Revenue > 70000"))
    echo "10% bonus revenue successfully applied!";

// Query 4: Seller status
echo "<h3>Query 4 Result</h3>";
showTable($conn, "SELECT ProductName, CategoryName,
CASE WHEN Revenue > 60000 THEN 'Top Seller'
ELSE 'Regular Seller' END AS SellerStatus
FROM sales_data");

mysqli_close($conn);
?>

