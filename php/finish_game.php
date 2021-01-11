<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
$reason = $_POST["result"];

if($id && $reason) {
    $stmt = $conn->prepare("SELECT moves, FEN, white_id, black_id FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO finished_games (moves, result, FEN, white_id, black_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $result["moves"], $reason, $result["FEN"], $result["white_id"], $result["black_id"]);
        if($stmt->execute()) {
            echo "{\"result\": \"success\"}";
            $stmt->close();
            $stmt = $conn->prepare("DELETE FROM in_progress WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
        else {
            echo "{\"result\": \"failure\", \"reason\": \"" . $stmt->error . "\"}";
        }
    }
}
?>