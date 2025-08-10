<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    require '..\config\config.php';
    require 'expense.php';
    require 'income.php';

    $balance = $income - $expense;

    // $color = $balance < 0 ? 'red' : 'green';

    ?>

</body>

</html>