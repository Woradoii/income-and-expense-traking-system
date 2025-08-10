<?php
require '..\config\framework.php';
require '..\config\config.php';
?>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($connect, $_POST["id"]);
    $income = mysqli_real_escape_string($connect, $_POST["income"]);
    $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
    $date = mysqli_real_escape_string($connect, $_POST["date"]);
    $sql = "UPDATE income 
                     SET income='$income', `date` ='$date', description='$descrip' 
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
                    window.location.href = "../income/Income.php";
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
// $income = mysqli_real_escape_string($connect, $_POST["income"]);
// $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
// $date = mysqli_real_escape_string($connect, $_POST["date"]);
// $sql = "UPDATE income 
//                      SET income='$income', `date` ='$date', description='$descrip' 
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
//                 window.location.href = "../income/Income.php";
//             });
//         </script>';
// } else {
//     echo '<script> alert("Sorry, there was an error."); </script>';
// }

// header("Location:..\income\Income.php");
?>