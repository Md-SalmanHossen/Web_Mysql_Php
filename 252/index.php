<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sales Database Solution</title>
</head>

<body>
   <?php
   $host = 'localhost';
   $user = 'root';
   $password = '';

   // 1. MySQL Server Connection
   $conn = mysqli_connect($host, $user, $password);

   if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
   }

   // 2. Database Creation & Selection
   $sql_db = "CREATE DATABASE IF NOT EXISTS sundarban";
   mysqli_query($conn, $sql_db);
   mysqli_select_db($conn, "sundarban");

   // 3. Table Creation
   $sql_table = "CREATE TABLE IF NOT EXISTS sales_data (
        SaleID INT PRIMARY KEY,
        ProductName VARCHAR(100),
        CategoryID INT,
        CategoryName VARCHAR(100),
        Quantity INT,
        Revenue INT
    )";
   mysqli_query($conn, $sql_table);

   // 4. Data Insertion
   $sql_insert = "INSERT IGNORE INTO sales_data (SaleID, ProductName, CategoryID, CategoryName, Quantity, Revenue) VALUES
    (1, 'Laptop', 301, 'Electronics', 5, 350000),
    (2, 'Mouse', 301, 'Electronics', 15, 45000),
    (3, 'Chair', 302, 'Furniture', 8, 64000),
    (4, 'Desk', 302, 'Furniture', 6, 72000),
    (5, 'Bottle', 303, 'Accessories', 20, 30000),
    (6, 'Pen', 303, 'Accessories', 25, 20000)";

   mysqli_query($conn, $sql_insert);

   // ==========================================
   // QUERY 1: Display total revenue per category
   // ==========================================
   echo "<h3>--- Query 1 Result ---</h3>";
   $sql1 = "SELECT CategoryName, SUM(Revenue) AS total_revenue 
             FROM sales_data 
             GROUP BY CategoryID, CategoryName 
             ORDER BY total_revenue DESC";
   $result1 = mysqli_query($conn, $sql1);

   if (mysqli_num_rows($result1) > 0) {
      echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Category Name</th>
                    <th>Total Revenue</th>
                </tr>";
      while ($row = mysqli_fetch_assoc($result1)) {
         echo "<tr>
                    <td>" . $row['CategoryName'] . "</td>
                    <td>" . number_format($row['total_revenue'], 2) . "</td>
                  </tr>";
      }
      echo "</table>";
   }

   // ==========================================
   // QUERY 2: Update category to 'Low Performing' if revenue < 40,000
   // ==========================================
   echo "<h3>--- Query 2 Result ---</h3>";
   $sql2 = "UPDATE sales_data SET CategoryName = 'Low Performing' WHERE Revenue < 40000";

   if (mysqli_query($conn, $sql2)) {
      echo "<div class='message'>Categories updated successfully for low revenue products!</div>";
   } else {
      echo "<div class='message' style='background-color: #ffccd5; border-color: #d90429;'>Update Error: " . mysqli_error($conn) . "</div>";
   }

   // ==========================================
   // QUERY 3: Add 10% bonus revenue if revenue > 70,000
   // ==========================================
   echo "<h3>--- Query 3 Result ---</h3>";
   $sql3 = "UPDATE sales_data SET Revenue = Revenue * 1.10 WHERE Revenue > 70000";

   if (mysqli_query($conn, $sql3)) {
      echo "<div class='message'>10% bonus revenue successfully applied!</div>";
   } else {
      echo "<div class='message' style='background-color: #ffccd5; border-color: #d90429;'>Update Error: " . mysqli_error($conn) . "</div>";
   }

   // ==========================================
   // QUERY 4: Display product name, category name, and Seller status label
   // ==========================================
   echo "<h3>--- Query 4 Result ---</h3>";
   $sql4 = "SELECT s1.ProductName, s1.CategoryName, 
                    CASE 
                        WHEN s1.Revenue > (SELECT AVG(s2.Revenue) FROM sales_data s2 WHERE s2.CategoryID = s1.CategoryID) 
                        THEN 'Top Seller' 
                        ELSE 'Regular Seller' 
                    END AS SellerLabel 
             FROM sales_data s1";

   $result4 = mysqli_query($conn, $sql4);

   if (mysqli_num_rows($result4) > 0) {
      echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Product Name</th>
                    <th>Category Name</th>
                    <th>Seller Status</th>
                </tr>";
      while ($row = mysqli_fetch_assoc($result4)) {
         echo "<tr>
                    <td>" . $row['ProductName'] . "</td>
                    <td>" . $row['CategoryName'] . "</td>
                    <td>" . $row['SellerLabel'] . "</td>
                  </tr>";
      }
      echo "</table>";
   } else {
      echo "<p>No products found.</p>";
   }

   // Final Connection Close
   mysqli_close($conn);
   ?>
</body>

</html>