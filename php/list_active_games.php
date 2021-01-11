<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];

if($id) {
    $stmt = $conn->prepare("SELECT id, FEN, white_id, black_id FROM in_progress WHERE white_id=? OR black_id=?");
    $stmt->bind_param("ss", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = array("s", $id);
    $games = array();
    while($row = $result->fetch_assoc()) {
        if($id == $row["white_id"]) {
            array_push($ids, $row["black_id"]);
        }
        else {
            array_push($ids, $row["white_id"]);
        }
        array_push($games, $row);
    }
    $stmt->close();
    $ids_length = count($ids);
    $parameters = "?";
    for($i = 2; $i < $ids_length; $i ++) {
        $parameters .= ", ?";
        $ids[0] .= "s";
    }
    $stmt = $conn->prepare("SELECT id, name FROM names WHERE id IN (" . $parameters . ")");
    $ref = new ReflectionClass('mysqli_stmt');
    $method = $ref->getMethod('bind_param');
    $method->invokeArgs($stmt, $ids);
    $stmt->execute();
    echo json_encode(array("games" => $games, "ids" => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)));
}
?>