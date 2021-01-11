<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
$name = $_POST["name"];

if($id) {
    $stmt = $conn->prepare("INSERT INTO names(id, name) VALUES (?, ?) ON DUPLICATE KEY UPDATE name=?");
    $stmt->bind_param("sss", $id, $name, $name);
    $stmt->execute();
}
?>