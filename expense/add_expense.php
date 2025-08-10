<?php
require '..\config\framework.php';
require '..\config\config.php';
?>



<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense = mysqli_real_escape_string($connect, $_POST["expense"]);
    $descrip = mysqli_real_escape_string($connect, $_POST["description"]);
    $date = mysqli_real_escape_string($connect, $_POST["date"]);
    $sql = "INSERT INTO expense (expense,date,description) VALUES ('$expense','$date','$descrip')";
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
                    window.location.href = "../expense/expense.php";
                });
            });
        </script>';
    } else {
        echo '<script> alert("Sorry, there was an error."); </script>';
    }
}


// if ($_SERVER["REQUEST_METHOD"] == "GET") {
//     $expense = mysqli_real_escape_string($connect, $_GET["add_expense"] ?? '');
// }

?>