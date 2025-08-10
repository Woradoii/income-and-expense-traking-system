<?php
require '..\config\config.php';

$sql = "SELECT * FROM expense WHERE delete_at is null ";

if (isset($_GET['filter_type'])) {
    $filter_type = $_GET['filter_type'];
    if ($filter_type === 'day' && !empty($_GET['date'])) {
        $date = mysqli_real_escape_string($connect, $_GET['date']);
        $sql .= " AND DATE(`date`) = '$date'";
    }

    if ($filter_type === 'monthly' && !empty($_GET['month'])) {
        $month = mysqli_real_escape_string($connect, $_GET['month']);
        $sql .= " AND DATE_FORMAT(`date`,'%Y-%m') = '$month'";
    }
    if ($filter_type === 'weekly' && !empty($_GET['week'])) {
        $week = mysqli_real_escape_string($connect, $_GET['week']);

        // Split the week string into year and week number
        list($year, $week_number) = explode('-W', $week);

        // Sanitize the week number to avoid any issues
        $week_number = intval($week_number);

        // Add a condition to the SQL query for the year and week
        $sql .= " AND YEAR(`date`) = '$year' AND WEEK(`date`, 1) = $week_number";  // 1 indicates Monday as the start of the week
    }
}

$sql .= " ORDER BY ID ASC";
$result = mysqli_query($connect, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php
$i = 1;




?>


