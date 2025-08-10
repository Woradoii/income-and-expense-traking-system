<?php
require '..\config\config.php';

$id = $_POST['id'];
$sql = "SELECT *
                FROM income  
                WHERE id =" . $id;
$result = mysqli_query($connect, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
// $income = $rows[0];
// return $income;

header('Content-Type: application/json'); // บอกว่า response เป็น JSON
echo json_encode($rows[0]); // ส่งข้อมูลออกไปแบบ JSON

// return $income;
// echo $income;
