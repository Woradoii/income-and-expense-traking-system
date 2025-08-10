<?php
require '..\config\config.php';

// $id = $_GET["id"];
// echo $id ;

$id = mysqli_real_escape_string($connect, $_GET["id"]);

// -- hard delete -----
// $sql = "DELETE
//                     FROM expense  
//                     WHERE id =" . $id;

// -- soft delete ---
date_default_timezone_set("Asia/Bangkok");
$today = date("Y-m-d H:i:s");
$sql = "UPDATE expense SET delete_at = '" . $today . "' WHERE id =" . $id;
echo $sql;
$result = mysqli_query($connect, $sql);

header("Location:expense.php");
