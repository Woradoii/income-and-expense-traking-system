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
    </script>

</head>

<body>

    <div class="top-bar">
        <a href="..\Overview\Total.php" id="overview">Overview</a>
        <a href="balance.php"><b>Balance</b></a>
        <a href="income.php">Income</a>
        <a href="..\expense\expense.php">Expense</a>
    </div>

    <div class="header">Total Income/Balance</div>

    <div class="content">
        <button id="back" onclick="window.location.href='../Overview/Total.php'" style="cursor: pointer;">back</button>
        <div class="balance-box">
            <div class="balance">
                <h3>Balance</h3>
                <h1>
                    <?php echo number_format($balance, 2); ?>
                </h1>
                <h5>TH ฿</h5>
            </div>
        </div>



        <div class="table">
            <?php
            $sql = "
                SELECT id, income AS amount, date, description, 'income' AS type
                FROM income
                WHERE delete_at IS NULL
            
                UNION ALL
            
                SELECT id, expense AS amount, date, description, 'expense' AS type
                FROM expense
                WHERE delete_at IS NULL
            
                ORDER BY date ASC
            ";


            ?>
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
                        <input type="number" id="add_income" name="income" placeholder="Enter income (฿)" min="0" max="99999999.75"
                            step="0.01" required><br>
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
                        <input type="number" id="update_income" name="income" min="0" step="0.01" max="99999999.75" required><br>
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