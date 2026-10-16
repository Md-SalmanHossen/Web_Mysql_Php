```php
<!DOCTYPE html>
<html>
<body>

<?php
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) die("Connection failed");

// Create database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS uuiTech_final");
mysqli_select_db($conn, "uuiTech_final");

// Create table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS employee_final (
    EmployeeID INT PRIMARY KEY,
    EmployeeName VARCHAR(100),
    DepartmentID INT,
    DepartmentName VARCHAR(100),
    Salary INT,
    PerformanceRating VARCHAR(5)
)");

// Insert data
mysqli_query($conn, "INSERT IGNORE INTO employee_final VALUES
(1, 'Arif Rahman', 201, 'Software Development', 45000, 'B'),
(2, 'Marium Khan', 201, 'Software Development', 52000, 'A'),
(3, 'Sabbir Hossain', 202, 'Quality Assurance', 38000, 'C'),
(4, 'Samira Begum', 203, 'UI/UX Design', 42000, 'B')");

// Function to display table
function showTable($conn, $sql) {
    $result = mysqli_query($conn, $sql);

    echo "<table border='1' cellpadding='8' cellspacing='0'><tr>";

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

// Query 1: Total employees per performance rating
echo "<h3>Query 1 Result</h3>";

showTable($conn, "SELECT PerformanceRating,
COUNT(*) AS total_employees
FROM employee_final
GROUP BY PerformanceRating
ORDER BY PerformanceRating ASC");

// Query 2: Update rating for low salary employees
echo "<h3>Query 2 Result</h3>";

if (mysqli_query($conn, "UPDATE employee_final
SET PerformanceRating = 'C'
WHERE Salary < 40000
AND PerformanceRating != 'D'"))
    echo "Performance ratings updated successfully!";

// Query 3: Add 5000 bonus
echo "<h3>Query 3 Result</h3>";

if (mysqli_query($conn, "UPDATE employee_final
SET Salary = Salary + 5000
WHERE Salary > 50000
AND Salary + 5000 <= 60000"))
    echo "Bonus applied successfully!";

// Query 4: Department-wise employee count
echo "<h3>Query 4 Result</h3>";

showTable($conn, "SELECT DepartmentName,
COUNT(*) AS total_employees
FROM employee_final
GROUP BY DepartmentID, DepartmentName
ORDER BY total_employees DESC");

mysqli_close($conn);
?>

</body>
</html>
```