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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function check() {
            Swal.fire({
                title: "Do you want to save the changes?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Save",
                denyButtonText: `Don't save`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    document.getElementById("income-form").submit();
                    Swal.fire("Saved!", "", "success");
                } else if (result.isDenied) {
                    window.location.href = '../income/Income.php';
                }
            });
        }

        async function update() {
            const {
                value: text
            } = await Swal.fire({
                input: "text",
                inputLabel: "Message",
                inputPlaceholder: "Type your message here...",
                inputAttributes: {
                    "aria-label": "Type your message here"
                },
                showCancelButton: true
            });
            if (text) {
                window.location.href = "Income.php?income=" + text;
            }
        }
    </script>




</head>

<body>
    <div class="top-bar">
        <div class="dropdown">
            <a href="Total-Overview.html" id="overview">Overview</a>
            <div class="content">
                <a href="Daily-Overview.html">Daily</a>
                <a href="Weekly-Overview.html">Weekly</a>
                <a href="Monthly-Overview.html">Monthly</a>
            </div>

        </div>
        <a href="#"><b>Income</b></a>
        <a href="#">Expenses</a>
    </div>
    <div class="header">Income</div>

    <div class="content">


        <div class="balance-box">
            <div class="balance">
                <h3>Balance</h3>
                <h1><?php echo number_format($balance, 2); ?></h1>
                <h5>TH ฿</h5>
            </div>
            <div class="add">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
                    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
                    crossorigin="anonymous" referrerpolicy="no-referrer" />
                <i class="fa fa-plus-circle" aria-hidden="true" type="button"
                    onclick="window.location.href='../add/Income-add.php'" style="cursor:pointer"></i>
            </div>
        </div>







        <?php

        $id = mysqli_real_escape_string($connect, $_GET["id"]);
        $sql = "SELECT *
                        FROM income  
                        WHERE id =" . $id;
        $result = mysqli_query($connect, $sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $income = $rows[0];

        // var_dump($rows[0]);
        // echo "<br>";
        // var_dump($income);
        // var_dump($rows);
        // var_dump($income);

        ?>

        <div id="form">
            <div class="add-form">
                <div class="box-form">
                    <form class="addinfo_form" id="income-form" method="post">

                        <label for="income">Income:</label>
                        <input type="number" id="income" name="income"
                            min="0" step="0.01" required value="<?php echo ($income["income"]) ?>"><br>

                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required value="<?php echo ($income["date"]) ?>"><br>

                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" value="<?php echo ($income["description"]) ?>"><br>

                        <button class="btn" id="back" type="button" onclick="window.location.href = '../income/Income.php'">back</button>
                        <button class="btn" id="confirm" type="button" onclick="check()">confirm</button>
                    </form>

                </div>
            </div>
        </div>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $income = mysqli_real_escape_string($connect, $_POST["income"]);
            $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
            $date = mysqli_real_escape_string($connect, $_POST["date"]);
            $sql = "UPDATE income 
                    SET income='$income', date='$date', description='$descrip' 
                    WHERE id=$id";
            $result = mysqli_query($connect, $sql);

            if ($result) {


                echo '<script>
                        Swal.fire({
                            title: "Saved!",
                            text: "Your data has been saved.",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "../income/Income.php";
                        });
                    </script>';
            } else {
                echo '<script> alert("Sorry, there was an error."); </script>';
            }
        }
        ?>


        <div class="table">
            <table>
                <th>No.</th>
                <th>Cost (TH ฿)</th>
                <th>Date</th>
                <th>Description</th>
                <th></th>
                <?php
                $sql = "SELECT * FROM income WHERE delete_at is null ";
                $sql .= "ORDER BY ID ASC";
                $result = mysqli_query($connect, $sql);
                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                ?>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td>
                            <?php echo $row['id'] ?>
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
                                <a href="../update/Income-update.php?id=<?php echo $row['id'] ?>"><button class="btn" id="update">Update</button></a>
                                <button class="btn" id="delete" onclick="b_delete(<?php echo $row['id'] ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>



    </div>


</body>

</html>