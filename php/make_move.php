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
$client_move_num = $_POST["move_num"];

if($id && $player && $new_move && $fen) {
    $stmt = $conn->prepare("SELECT turn, moves, move_num FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($turn, $moves, $move_num);
    $stmt->fetch();
    $turn_converted = $turn ? "white" : "black";
    if($turn_converted == $player && $client_move_num == $move_num + 1) {
        $moves .= ";" . $new_move;
        $stmt->close();
        $turn = !$turn;
        $stmt = $conn->prepare("UPDATE in_progress SET turn=?, moves=?, last_move=?, FEN=?, move_num=? WHERE id=?");
        $stmt->bind_param("isssii", $turn, $moves, $new_move, $fen, $client_move_num, $id);
        $stmt->execute();
        echo "{\"result\":\"success\"}";
    }
    else {
        echo "{\"result\":\"wrong turn\"}";
    }
}
?>