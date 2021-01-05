<?php
header('Content-type:application/json;charset=utf-8');
register_shutdown_function('shutdown');
function shutdown() {
    print_r(error_get_last());
}

$conn = mysqli_connect("p:localhost", "u312026651_chess", "cygUVHoUV4wH7GzhGdkslscN3w9apYidr", "u312026651_chess");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_POST["id"];
$player = $_POST["player"];
$new_move = $_POST["move"];

if($id && $player && $move) {
    $stmt = $conn->stmt_init();
    $stmt->prepare("SELECT turn, moves FROM in_progress WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($turn, $moves);
    $stmt->fetch();
    if($turn == $player) {
        $moves .= ";" . $new_move;
        $stmt->close();
        $stmt = $conn->stmt_init();
        $turn = !$turn;
        $stmt->prepare("UPDATE in_progress SET turn=? moves=? last_move=? WHERE id=?");
        $stmt->bind_param("issi", $turn, $moves, $new_move, $id);
        $stmt->execute();
        echo "{\"result\":\"success\"}";
    }
    else {
        echo "{\"result\":\"wrong turn\"}";
    }
}
?>