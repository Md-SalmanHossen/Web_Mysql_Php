<!DOCTYPE html>
<html>
<body>

<?php
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) die("Connection failed");

// Create database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS tourism");
mysqli_select_db($conn, "tourism");

// Create table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tourist_spot (
    SpotID INT PRIMARY KEY,
    SpotName VARCHAR(100),
    Region VARCHAR(10),
    Category VARCHAR(50),
    Rating DECIMAL(3,1),
    EntryFee INT,
    VisitorsPerYear INT
)");

// Insert data
mysqli_query($conn, "INSERT IGNORE INTO tourist_spot VALUES
(1, 'Cox\'s Bazar Beach', 'CTG', 'Beach', 4.9, 0, 150000),
(2, 'Sundarbans Forest', 'KHL', 'Nature', 4.3, 150, 80000),
(3, 'Ratargul Forest', 'SYL', 'Nature', 4.8, 100, 60000),
(4, 'Kuakata Beach', 'PTU', 'Beach', 4.5, 0, 90000),
(5, 'Shat Gambuj Mosque', 'BGH', 'Heritage', 4.7, 30, 70000),
(6, 'Paharpur', 'NWG', 'Heritage', 4.1, 30, 40000)");

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

// Query 1: Rating > 4.5 OR SpotName contains Beach
echo "<h3>Query 1 Result</h3>";

showTable($conn, "SELECT SpotName, Region, Rating
FROM tourist_spot
WHERE Rating > 4.5 OR SpotName LIKE '%Beach%'
ORDER BY Rating DESC");

// Query 2: Update Rating and EntryFee
echo "<h3>Query 2 Result</h3>";

if (mysqli_query($conn, "UPDATE tourist_spot
SET Rating = Rating + 0.2,
    EntryFee = EntryFee * 1.10
WHERE Rating BETWEEN 4.0 AND 4.5
AND EntryFee > 0"))
    echo "Data updated successfully!";

// Query 3: Category-wise summary
echo "<h3>Query 3 Result</h3>";

showTable($conn, "SELECT Category,
COUNT(*) AS total_spots,
AVG(Rating) AS avg_rating,
SUM(VisitorsPerYear) AS total_visitors
FROM tourist_spot
GROUP BY Category
HAVING AVG(Rating) > 4.4
ORDER BY avg_rating DESC");

// Query 4: Region-wise revenue
echo "<h3>Query 4 Result</h3>";

showTable($conn, "SELECT Region,
SUM(EntryFee * VisitorsPerYear) AS total_revenue
FROM tourist_spot
GROUP BY Region
HAVING SUM(EntryFee * VisitorsPerYear) > 1000000
ORDER BY total_revenue DESC");

mysqli_close($conn);
?>

</body>
</html>
