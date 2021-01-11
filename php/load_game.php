<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
if($id) {
    $stmt = $conn->prepare("SELECT white_id, black_id, moves FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($white_id, $black_id, $moves);
    $stmt->fetch();
    $stmt->close();
    $stmt = $conn->prepare("SELECT id, name FROM names WHERE id=? OR id=?");
    $stmt->bind_param("ss", $white_id, $black_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $white = null;
    $black = null;
    while($row = $result->fetch_assoc()) {
        if($row['id'] == $white_id) {
            $white = $row['name'];
        }
        else {
            $black = $row['name'];
        }
    }
    echo json_encode(array("moves" => $moves, "white" => $white, "black" => $black));
}
?>