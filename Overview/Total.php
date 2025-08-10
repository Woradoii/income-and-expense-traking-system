<?php
require '..\config\config.php';
require '..\total\income.php';
require '..\total\expense.php';
require '..\total\balance.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\style\style.css">
    <title>ระบบรายรับ-รายจ่าย</title>


    <script>
        function resetFilter() {
            window.location.href = "total.php";
        }

        function weekly_income() {
            document.getElementById("filter_type").value = "weekly";
        };

        function monthly_income() {
            document.getElementById("filter_type").value = "monthly";
        };
    </script>


</head>

<body>

    <?php

    $sql_income = "
    SELECT id, income AS amount, date, description, 'income' AS type
    FROM income
    WHERE delete_at IS NULL
    ";

    $sql_expense = "
    SELECT id, expense AS amount, date, description, 'expense' AS type
    FROM expense
    WHERE delete_at IS NULL
    ";

    $income = "SELECT * FROM income WHERE delete_at is null ";
    $expense = "SELECT * FROM expense WHERE delete_at is null ";

    if (isset($_GET['filter_type'])) {
        $filter_type = $_GET['filter_type'];


        if ($filter_type === 'monthly' && !empty($_GET['month'])) {
            $month = mysqli_real_escape_string($connect, $_GET['month']);
            $income .= " AND DATE_FORMAT(`date`,'%Y-%m') = '$month'";
            $expense .= " AND DATE_FORMAT(`date`,'%Y-%m') = '$month'";
            $sql_income .= " AND DATE_FORMAT(`date`,'%Y-%m') = '$month'";
            $sql_expense .= " AND DATE_FORMAT(`date`,'%Y-%m') = '$month'";
        }
        if ($filter_type === 'weekly' && !empty($_GET['week'])) {
            $week = mysqli_real_escape_string($connect, $_GET['week']);

            // Split the week string into year and week number
            list($year, $week_number) = explode('-W', $week);

            // Sanitize the week number to avoid any issues
            $week_number = intval($week_number);

            // Add a condition to the SQL query for the year and week
            $income .= " AND YEAR(`date`) = '$year' AND WEEK(`date`, 1) = $week_number";  // 1 indicates Monday as the start of the week
            $expense .= " AND YEAR(`date`) = '$year' AND WEEK(`date`, 1) = $week_number";
            $sql_income .= " AND YEAR(`date`) = '$year' AND WEEK(`date`, 1) = $week_number";
            $sql_expense .= " AND YEAR(`date`) = '$year' AND WEEK(`date`, 1) = $week_number";
        }
    }

    $sql = "$sql_income UNION ALL $sql_expense ORDER BY date ASC";
    $income .= " ORDER BY ID ASC";
    $expense .= " ORDER BY ID ASC";
    $result_income = mysqli_query($connect, $income);
    $result_expense = mysqli_query($connect, $expense);
    $rows_income = mysqli_fetch_all($result_income, MYSQLI_ASSOC);
    $rows_expense = mysqli_fetch_all($result_expense, MYSQLI_ASSOC);
    ?>

    <?php
    $i = 1;
    $total_income = 0;
    $total_expense = 0;
    foreach ($rows_income as $row_income) :
        $total_income += $row_income['income'];
    ?>
    <?php endforeach; ?>
    <?php
    foreach ($rows_expense as $row_expense) :
        $total_expense += $row_expense['expense'];
    ?>
    <?php endforeach; ?>
    <?php
    $formatted_income = number_format($total_income, 2);
    $formatted_expense = number_format($total_expense, 2);
    $formatted_balance = number_format($balance, 2);

    // Output the total income
    // echo "Total income: " . $formatted_income . " TH ฿";
    // echo "Total expense: " . $formatted_expense . " TH ฿";
    ?>

    <div class="top-bar">
        <a href="Total.php" id="overview"><b>Overview</b></a>
        <a href="..\income\balance.php">Balance</a>
        <a href="..\income\income.php">Income</a>
        <a href="..\expense\expense.php">Expenses</a>
    </div>

    <?php
    $head = 'Total Overview';
    if (isset($_GET['filter_type'])) {
        $filter_type = $_GET['filter_type'];

        if ($filter_type === 'monthly' && !empty($_GET['month'])) {
            $head = mysqli_real_escape_string($connect, $_GET['month']);
        }
        if ($filter_type === 'weekly' && !empty($_GET['week'])) {
            $head = mysqli_real_escape_string($connect, $_GET['week']);
        }
    } else {
        $head = 'Total Overview';
    }

    ?>

    <div class="header">
        <?php echo $head; ?>
    </div>


    <div class="filter-tab">

        <div class="option">

            <button id="week_btn" onclick="weekly_income()">weekly</button>
            <button id="month_btn" onclick="monthly_income()">monthly</button>
            <button type="button" class="btn-secondary" onclick="resetFilter()">Reset</button>

        </div>

        <form method="GET" id="filterform">
            <input type="hidden" name="filter_type" id="filter_type">



            <input type="week" name="week" id="weekpicker" style="display:none;">
            <input type="month" name="month" id="monthpicker" style="display:none;">

            <button type="submit" id="view" style="display:none;">View</button>
        </form>

        <p></p>

        <script>
            const week_btn = document.getElementById('week_btn');
            const month_btn = document.getElementById('month_btn');


            const weekpicker = document.getElementById('weekpicker');
            const monthpicker = document.getElementById('monthpicker');

            const view = document.getElementById('view');


            week_btn.addEventListener('click', () => {
                if (weekpicker.style.display === 'none') {
                    weekpicker.style.display = 'inline-block';
                    view.style.display = 'inline-block';
                    monthpicker.style.display = 'none';
                } else {
                    weekpicker.style.display = 'none';
                    view.style.display = 'none';
                }
            });

            month_btn.addEventListener('click', () => {
                if (monthpicker.style.display === 'none') {
                    monthpicker.style.display = 'inline-block';
                    view.style.display = 'inline-block';
                    weekpicker.style.display = 'none';
                } else {
                    monthpicker.style.display = 'none';
                    view.style.display = 'none';
                }
            });
        </script>

    </div>





    <div class="content">
        <div class="top-box">

            <div class="box" id="income-box" onclick="window.location.href='../income/income.php'"
                style="cursor: pointer;">
                <h3>Total Income</h3>
                <h1>
                    <?php echo $formatted_income; ?>
                </h1>
                <h5>TH ฿</h5>
            </div>

            <div class="box" id="expense-box" onclick="window.location.href='../expense/expense.php'"
                style="cursor: pointer;">
                <h3>Total Expense</h3>
                <h1>
                    <?php echo $formatted_expense; ?>
                </h1>
                <h5>TH ฿</h5>
            </div>

        </div>

        <?php
        if (
            empty($_GET['date']) &&
            empty($_GET['week']) &&
            empty($_GET['month'])
        ) :
        ?>

        <div class="second-box">
            <div class="box" id="balance-box" onclick="window.location.href='../income/balance.php'"
                style="cursor: pointer;">
                <h3> Total Balance</h3>
                <h1>
                    <?php echo number_format($balance, 2); ?>
                </h1>
                <h5>TH ฿</h5>
            </div>
            <div class="box" id="pie-chart"
                style="height: 200px; display: flex; justify-content: center; align-items: center;">
                <canvas id="piechart"></canvas>

            </div>
        </div>
        <?php endif; ?>

        <div class="chart-box">
            <div class="chart" style="height: 350px;">
                <h3>Chart</h3>
                <div style="height: 300px; display: flex; justify-content: center; align-items: center;">
                    <canvas id="barchart"></canvas>
                </div>

            </div>
        </div>




        <?php if (
            (!empty($_GET['date']) && $_GET['date'] !== 'null') ||
            (!empty($_GET['week']) && $_GET['week'] !== 'null') ||
            (!empty($_GET['month']) && $_GET['month'] !== 'null')
        ): ?>
        <div class="table">

            <table>
                <tr>
                    <th>No.</th>
                    <th>Type</th>
                    <th>Amount (TH ฿)</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>

                <?php
                    $i = 1;
                    $result = mysqli_query($connect, $sql);
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    foreach ($rows as $row):
                    ?>
                <tr style="color: <?php echo $row['type'] === 'income' ? 'green' : 'red'; ?>">
                    <td>
                        <?php echo $i++; ?>
                    </td>
                    <td>
                        <?php echo ucfirst($row['type']); ?>
                    </td>
                    <td>
                        <?php
                                echo $row['type'] === 'income' ? '+' : '-';
                                echo number_format($row['amount'], 2);
                                ?>
                    </td>
                    <td>
                        <?php echo $row['date']; ?>
                    </td>
                    <td>
                        <?php echo $row['description']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const chart1 = document.getElementById('barchart').getContext('2d');

            // Pass PHP data into JavaScript using json_encode
            var income = <?php echo json_encode($total_income); ?>;
            var expense = <?php echo json_encode($total_expense); ?>;


            // Create the bar chart
            new Chart(chart1, {
                type: 'bar',
                data: {
                    labels: ['Income', 'Expense'], // X-axis labels
                    datasets: [{
                        label: 'Amount (THB)', // Y-axis label
                        data: [income, expense], // Chart data
                        backgroundColor: [
                            'rgba(19, 255, 86, 0.718)', // Color for income
                            'rgba(255, 62, 48, 0.706)', // Color for expense
                        ],
                        borderColor: [
                            'rgba(19, 255, 86, 0.718)', // Border color for income
                            'rgba(255, 62, 48, 0.706)', // Border color for expense
                        ],
                        borderWidth: 1 // Border width for each bar
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true, // Ensure the Y-axis starts at zero
                            ticks: {
                                // Adding step size for better readability on the Y-axis
                                stepSize: 5000 // Customize based on the range of your data
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top', // Positioning the legend at the top
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    // Formatting tooltip to show values with THB
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + ' THB';
                                }
                            }
                        }
                    }
                }
            });

            const chart2 = document.getElementById('piechart').getContext('2d');

            new Chart(chart2, {
                type: 'doughnut', // you can also use 'pie'
                data: {
                    labels: ['Income', 'Expense'],
                    datasets: [{
                        data: [income, expense],
                        backgroundColor: [
                            'rgba(19, 255, 86, 0.718)', // greenish for income
                            'rgba(255, 62, 48, 0.706)' // red for expense
                        ],
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    return `${label}: ฿${value.toLocaleString()}`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Income vs Expense'
                        }
                    }
                }
            });
        </script>

</body>

</html>