<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourism Database Practice</title>
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
    $sql_db = "CREATE DATABASE IF NOT EXISTS tourism";
    mysqli_query($conn, $sql_db);
    mysqli_select_db($conn, "tourism");

    // 3. Table Creation
    $sql_table = "CREATE TABLE IF NOT EXISTS tourist_spot (
        SpotID INT PRIMARY KEY,
        SpotName VARCHAR(100),
        Region VARCHAR(10),
        Category VARCHAR(50),
        Rating DECIMAL(3,1),
        EntryFee INT,
        VisitorsPerYear INT
    )";
    mysqli_query($conn, $sql_table);

    // 4. Data Insertion
    $sql_insert = "INSERT IGNORE INTO tourist_spot (SpotID, SpotName, Region, Category, Rating, EntryFee, VisitorsPerYear) VALUES
    (1, 'Cox\'s Bazar Beach', 'CTG', 'Beach', 4.9, 0, 150000),
    (2, 'Sundarbans Forest', 'KHL', 'Nature', 4.3, 150, 80000),
    (3, 'Ratargul Forest', 'SYL', 'Nature', 4.8, 100, 60000),
    (4, 'Kuakata Beach', 'PTU', 'Beach', 4.5, 0, 90000),
    (5, 'Shat Gambuj Mosque', 'BGH', 'Heritage', 4.7, 30, 70000),
    (6, 'Paharpur', 'NWG', 'Heritage', 4.1, 30, 40000)";

    mysqli_query($conn, $sql_insert);

    // ==========================================
    // QUERY 1: Rating > 4.5 ba 'Beach'
    // ==========================================
    echo "<h3>--- Query 1 Result ---</h3>";
    $sql1 = "SELECT SpotName, Region, Rating FROM tourist_spot WHERE Rating > 4.5 OR SpotName LIKE '%Beach%' ORDER BY Rating DESC";
    $result1 = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result1) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Spot Name</th>
                    <th>Region</th>
                    <th>Rating</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result1)) {
            echo "<tr>
                    <td>" . $row['SpotName'] . "</td>
                    <td>" . $row['Region'] . "</td>
                    <td>" . $row['Rating'] . "</td>
                  </tr>";
        }
        echo "</table>";
    }

    // ==========================================
    // QUERY 2: Update Data (Rating & EntryFee)
    // ==========================================
    echo "<h3>--- Query 2 Result ---</h3>";
    $sql2 = "UPDATE tourist_spot SET Rating = Rating + 0.2, EntryFee = EntryFee * 1.10 WHERE Rating BETWEEN 4.0 AND 4.5 AND EntryFee > 0";

    if (mysqli_query($conn, $sql2)) {
        echo "<div class='message'>Data successfully updated!</div>";
    } else {
        echo "<div class='message' style='background-color: #ffccd5; border-color: #d90429;'>Update Error: " . mysqli_error($conn) . "</div>";
    }

    // ==========================================
    // QUERY 3: Category wise summary
    // ==========================================
    echo "<h3>--- Query 3 Result ---</h3>";
    $sql3 = "SELECT Category, COUNT(*) AS total_spots, AVG(Rating) AS avg_rating, SUM(VisitorsPerYear) AS total_visitors 
             FROM tourist_spot 
             GROUP BY Category 
             HAVING AVG(Rating) > 4.4 
             ORDER BY avg_rating DESC";

    $result3 = mysqli_query($conn, $sql3);

    if (mysqli_num_rows($result3) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Category</th>
                    <th>Total Spots</th>
                    <th>Avg Rating</th>
                    <th>Total Visitors</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result3)) {
            echo "<tr>
                    <td>" . $row['Category'] . "</td>
                    <td>" . $row['total_spots'] . "</td>
                    <td>" . round($row['avg_rating'], 2) . "</td>
                    <td>" . $row['total_visitors'] . "</td>
                  </tr>";
        }
        echo "</table>";
    }

    // ==========================================
    // QUERY 4: Region wise revenue
    // ==========================================
    echo "<h3>--- Query 4 Result ---</h3>";
    $sql4 = "SELECT Region, SUM(EntryFee * VisitorsPerYear) AS total_revenue 
             FROM tourist_spot 
             GROUP BY Region 
             HAVING SUM(EntryFee * VisitorsPerYear) > 1000000 
             ORDER BY total_revenue DESC";

    $result4 = mysqli_query($conn, $sql4);

    if (mysqli_num_rows($result4) > 0) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>
                <tr>
                    <th>Region</th>
                    <th>Total Revenue</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result4)) {
            echo "<tr>
                    <td>" . $row['Region'] . "</td>
                    <td>" . number_format($row['total_revenue'], 2) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No regions found matching the condition.</p>";
    }

    // Final Connection Close
    mysqli_close($conn);
    ?>
</body>

</html>