<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Employee Database Solution</title>
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

   // 2. Database Creation & Selection (Using matching lowercase names to avoid OS-level case issues)
   $sql_db = "CREATE DATABASE IF NOT EXISTS uuiTech_final";
   mysqli_query($conn, $sql_db);
   mysqli_select_db($conn, "uuiTech_final");

   // 3. Table Creation
   $sql_table = "CREATE TABLE IF NOT EXISTS employee_final (
        EmployeeID INT PRIMARY KEY,
        EmployeeName VARCHAR(100),
        DepartmentID INT,
        DepartmentName VARCHAR(100),
        Salary INT,
        PerformanceRating VARCHAR(5)
    )";
   mysqli_query($conn, $sql_table);

   // 4. Data Insertion
   $sql_insert = "INSERT IGNORE INTO employee_final (EmployeeID, EmployeeName, DepartmentID, DepartmentName, Salary, PerformanceRating) VALUES
    (1, 'Arif Rahman', 201, 'Software Development', 45000, 'B'),
    (2, 'Marium Khan', 201, 'Software Development', 52000, 'A'),
    (3, 'Sabbir Hossain', 202, 'Quality Assurance', 38000, 'C'),
    (4, 'Samira Begum', 203, 'UI/UX Design', 42000, 'B')";

   mysqli_query($conn, $sql_insert);

   // ==========================================
   // QUERY 1: Total employees per performance rating
   // ==========================================
   echo "<h3>--- Query 1 Result ---</h3>";
   $sql1 = "SELECT PerformanceRating, COUNT(*) AS total_employees 
             FROM employee_final 
             GROUP BY PerformanceRating 
             ORDER BY PerformanceRating ASC";
   $result1 = mysqli_query($conn, $sql1);

   if (mysqli_num_rows($result1) > 0) {
      echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Performance Rating</th>
                    <th>Total Employees</th>
                </tr>";
      while ($row = mysqli_fetch_assoc($result1)) {
         echo "<tr>
                    <td>" . $row['PerformanceRating'] . "</td>
                    <td>" . $row['total_employees'] . "</td>
                  </tr>";
      }
      echo "</table>";
   }

   // ==========================================
   // QUERY 2: Update rating to 'C' if salary < 40000 and rating != 'D'
   // ==========================================
   echo "<h3>--- Query 2 Result ---</h3>";
   $sql2 = "UPDATE employee_final SET PerformanceRating = 'C' WHERE Salary < 40000 AND PerformanceRating != 'D'";

   if (mysqli_query($conn, $sql2)) {
      echo "<div class='message'>Performance ratings successfully updated for low salary employees!</div>";
   } else {
      echo "<div class='message' style='background-color: #ffccd5; border-color: #d90429;'>Update Error: " . mysqli_error($conn) . "</div>";
   }

   // ==========================================
   // QUERY 3: Add 5000 BDT bonus if salary > 50000 and new salary <= 60000
   // ==========================================
   echo "<h3>--- Query 3 Result ---</h3>";
   $sql3 = "UPDATE employee_final SET Salary = Salary + 5000 WHERE Salary > 50000 AND (Salary + 5000) <= 60000";

   if (mysqli_query($conn, $sql3)) {
      echo "<div class='message'>Bonuses successfully applied!</div>";
   } else {
      echo "<div class='message' style='background-color: #ffccd5; border-color: #d90429;'>Update Error: " . mysqli_error($conn) . "</div>";
   }

   // ==========================================
   // QUERY 4: Department-wise employee count (largest first)
   // ==========================================
   echo "<h3>--- Query 4 Result ---</h3>";
   $sql4 = "SELECT DepartmentName, COUNT(*) AS total_employees 
             FROM employee_final 
             GROUP BY DepartmentID, DepartmentName 
             ORDER BY total_employees DESC";

   $result4 = mysqli_query($conn, $sql4);

   if (mysqli_num_rows($result4) > 0) {
      echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Department Name</th>
                    <th>Total Employees</th>
                </tr>";
      while ($row = mysqli_fetch_assoc($result4)) {
         echo "<tr>
                    <td>" . $row['DepartmentName'] . "</td>
                    <td>" . $row['total_employees'] . "</td>
                  </tr>";
      }
      echo "</table>";
   } else {
      echo "<p>No departments found.</p>";
   }

   // Final Connection Close
   mysqli_close($conn);
   ?>
</body>

</html>