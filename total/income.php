<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance</title>
</head>

<body>

    <?php
    require '..\config\config.php';

    $sql = "SELECT SUM(income) AS total_income
            FROM income WHERE delete_at is null ";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);
    $income = $row['total_income'] ?? 0;
    // echo $income;

    ?>

</body>

</html>