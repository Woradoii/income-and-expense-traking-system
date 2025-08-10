<?php
require '..\config\framework.php';
require '..\config\config.php';
?>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($connect, $_POST["id"]);
    $expense = mysqli_real_escape_string($connect, $_POST["expense"]);
    $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
    $date = mysqli_real_escape_string($connect, $_POST["date"]);
    $sql = "UPDATE expense 
                     SET expense='$expense', `date` ='$date', description='$descrip' 
                     WHERE id='$id' ";
    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo '
        <script>
            window.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Saved!",
                    text: "Your data has been saved.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../expense/expense.php";
                });
            });
        </script>';
    } else {
        echo '<script> alert("Sorry, there was an error."); </script>';
    }
}
?>


<?php
// require '..\config\config.php';

// // $id = $_GET["id"];
// // echo $id ;

// $id = mysqli_real_escape_string($connect, $_GET["id"]);
// echo $id;
// $expense = mysqli_real_escape_string($connect, $_POST["expense"]);
// $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
// $date = mysqli_real_escape_string($connect, $_POST["date"]);
// $sql = "UPDATE expense 
//                      SET expense='$expense', `date` ='$date', description='$descrip' 
//                      WHERE id='$id' ";
// $result = mysqli_query($connect, $sql);

// if ($result) {
//     echo '<script>
//             Swal.fire({
//                 title: "Saved!",
//                 text: "Your data has been saved.",
//                 icon: "success",
//                 confirmButtonText: "OK"
//             }).then(() => {
//                 window.location.href = "../expense/expense.php";
//             });
//         </script>';
// } else {
//     echo '<script> alert("Sorry, there was an error."); </script>';
// }

// header("Location:..\expense\expense.php");
?>