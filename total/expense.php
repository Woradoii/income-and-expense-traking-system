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

    $sql = "SELECT SUM(expense) AS total_expense
            FROM expense WHERE delete_at is null ";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);
    $expense = $row['total_expense'] ?? 0;
    // echo $expense;

    ?>

</body>

</html>