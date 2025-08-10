<?php
require '..\config\config.php';

$sql = "SELECT date, SUM(income) AS daily_total
        FROM income
        GROUP BY date
        ORDER BY date ASC";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $daily_total = $row['daily_total'] ?? 0;
    
    echo "Date: $date - Total Income: " . number_format($daily_total, 2) . " บาท<br>";
}
?>
