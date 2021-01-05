<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
if($id) {
    $stmt = $conn->stmt_init();
    $stmt->prepare("SELECT white, black, moves FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc());
    $stmt->close();
}
?>