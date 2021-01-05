<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];

if($id) {
    $stmt = $conn->stmt_init();
    $stmt->prepare("SELECT id, white, black, FEN, white_id FROM in_progress WHERE white_id=? OR black_id=?");
    $stmt->bind_param("ss", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $to_echo = [];
    while($row = $result->fetch_assoc()) {
        $row["user_white"] = $id == $row["white_id"];
        unset($row['white_id']);
        array_push($to_echo, $row);
    }
    echo json_encode($to_echo);
    $stmt->close();
}
?>