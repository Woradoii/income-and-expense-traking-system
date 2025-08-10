<?php
require '..\config\framework.php';
require '..\config\config.php';
?>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $income = mysqli_real_escape_string($connect, $_POST["income"]);
    $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
    $date = mysqli_real_escape_string($connect, $_POST["date"]);
    $sql = "INSERT INTO income (income,date,description) VALUES ('$income','$date','$descrip')";
    $result = mysqli_query($connect, $sql);

    if ($result) {

        echo '
        <script>
            window.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Added!",
                    text: "Your data has been inserted.",
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


// if ($_SERVER["REQUEST_METHOD"] == "GET") {
//     $income = mysqli_real_escape_string($connect, $_GET["add_income"] ?? '');
// }

?>