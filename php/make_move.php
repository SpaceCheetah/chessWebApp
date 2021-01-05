<?php
header('Content-type:application/json;charset=utf-8');

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
$player = $_POST["player"];
$new_move = $_POST["move"];
$fen = $_POST["fen"];

if($id && $player && $new_move && $fen) {
    $stmt = $conn->prepare("SELECT turn, moves FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($turn, $moves);
    $stmt->fetch();
    $turn_converted = $turn ? "white" : "black";
    if($turn_converted == $player) {
        $moves .= ";" . $new_move;
        $stmt->close();
        $turn = !$turn;
        $stmt = $conn->prepare("UPDATE in_progress SET turn=?, moves=?, last_move=?, FEN=? WHERE id=?");
        $stmt->bind_param("isssi", $turn, $moves, $new_move, $fen, $id);
        $stmt->execute();
        echo "{\"result\":\"success\"}";
    }
    else {
        echo "{\"result\":\"wrong turn\"}";
    }
}
?>