<?php
$connection = mysqli_connect('localhost', 'ureznetq_vis_ur_', 'Bgfh112@', 'ureznetq_vis_ur_');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>