<?php
require '..\config\config.php';
require '..\total\income.php';
require '..\total\expense.php';
require '..\total\balance.php';
require '..\config\framework.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\style\style.css">
    <title>ระบบรายรับ-รายจ่าย</title>

    <!-- function DELETE -->
    <script>
        function b_delete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_income.php?id=' + id; // ส่ง id ไปว่า id=ตัวแปรid ที่รับมา
                }
            });
        }
    </script>

    <!-- function UPDATE -->
    <script>
        // function ส่ง id ไป query ข้อมูลออกมา
        function send_id(id) {
            console.log("ID received from button:", id);

            $.ajax({
                url: "update_query.php",
                method: "POST",
                data: {
                    id: id,
                },
                success: function (result) {
                    console.log("Data received :", result.income);
                    // jquery syntax
                    $('#updateform #update_id').val(result.id);
                    $('#updateform #update_income').val(result.income);
                    $('#updateform #update_date').val(result.date);
                    $('#updateform #update_description').val(result.description);

                    // document.getElementById("income").value = result.income; // javascript syntax
                }
            })
        }

        // function เช็คความแน่ใจก่อนส่ง
        function check() {
            Swal.fire({
                title: "Do you want to save the changes?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("update-form").submit(); //js submit
                } else if (result.isDenied) {
                    window.location.href = '../income/income.php';
                }
            });
        }

        function resetFilter() {
            window.location.href = "income.php";
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

    $sql = "SELECT * FROM income WHERE delete_at is null ";

    if (isset($_GET['filter_type'])) {
        $filter_type = $_GET['filter_type'];


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

    $sql .= " ORDER BY date ASC";
    $result = mysqli_query($connect, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>

    <?php
    $i = 1;
    $total_income = 0;
    foreach ($rows as $row) :
        $total_income += $row['income'];

    ?>
    <?php endforeach; ?>
    <?php
    $formatted_income = number_format($total_income, 2);

    // Output the total income
    // echo "Total income: " . $formatted_income . " TH ฿";
    ?>


    <div class="top-bar">
        <a href="..\Overview\Total.php" id="overview">Overview</a>
        <a href="..\income\balance.php">Balance</a>
        <a href="income.php"><b>Income</b></a>
        <a href="..\expense\expense.php">Expense</a>
    </div>


    <?php
    $head='Total Income';
    if (isset($_GET['filter_type'])) {
        $filter_type = $_GET['filter_type'];
   
        if ($filter_type === 'monthly' && !empty($_GET['month'])) {
            $head = mysqli_real_escape_string($connect, $_GET['month']);
        }
        if ($filter_type === 'weekly' && !empty($_GET['week'])) {
            $head = mysqli_real_escape_string($connect, $_GET['week']);
        }
    }else{
        $head = 'Total Income';
    }

    ?>



    <div class="content">

        <div class="header">
            <?php echo  $head ;?>
        </div>

        <button id="back" onclick="window.location.href='../Overview/Total.php'" style="cursor: pointer;">back</button>

        <div class="income-box">
            <div class="income">
                <h3>Income</h3>
                <h1>
                    <?php echo $formatted_income; ?>
                </h1>
                <h5>TH ฿</h5>
            </div>
            <div class="add">

                <button data-toggle="modal" data-target="#addform" style="cursor:pointer" class="circle-btn">+</button>
            </div>

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





        <div class="table">
            <table>
                <!-- table header -->
                <th>No.</th>
                <th>Cost (TH ฿)</th>
                <th>Date</th>
                <th>Description</th>
                <th></th>

                <?php
                foreach ($rows as $row) :
                ?>

                <tr>
                    <td>
                        <?php echo $i++ ?>
                    </td>
                    <td>
                        <?php echo $row['income'] ?>
                    </td>
                    <td>
                        <?php echo $row['date'] ?>
                    </td>
                    <td>
                        <?php echo $row['description'] ?>
                    </td>

                    <td>
                        <div class="command">
                            <button class="btn" id="update" data-toggle="modal" data-target="#updateform"
                                onclick="send_id(<?php echo $row['id'] ?>)">Update</button>
                            <button class="btn" id="delete" onclick="b_delete(<?php echo $row['id'] ?>)">Delete</button>
                        </div>
                    </td>
                </tr>

                <?php endforeach; ?>

            </table>
        </div>

    </div>

    <!-- Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- modal form -->

    <!-- add from -->
    <div class="modal fade" id="addform" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="addinfo_form" id="add-form" method="post" action="add_income.php">
                        <input type="hidden" id="add_id" name="id">
                        <label for="income">income:</label>
                        <input type="number" id="add_income" name="income" placeholder="Enter income (฿)" min="0.25" max="99999999.75"
                            step="0.25" required><br>
                        <label for="date">Date:</label>
                        <input type="date" id="add_date" name="date" required><br>
                        <label for="description">Description:</label>
                        <input type="text" id="add_description" name="description" maxlength="40"
                            placeholder="Enter a description"><br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- update form -->
    <div class="modal fade" id="updateform" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="addinfo_form" id="update-form" method="post" action="update_income.php">
                        <input type="hidden" id="update_id" name="id">
                        <label for="income">income:</label>
                        <input type="number" id="update_income" name="income" min="0.25" step="0.25" max="99999999.75" required><br>
                        <label for="date">Date:</label>
                        <input type="date" id="update_date" name="date" required><br>
                        <label for="description">Description:</label>
                        <input type="text" id="update_description" name="description" maxlength="40"
                            placeholder="Enter a description"><br>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="check()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>