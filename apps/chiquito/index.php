<?php
$chistes = [
    "Te contaria un chiste sobre UDP, pero puede que no lo pilles",
    "Solo hay 10 tipos de personas, las que saben binario y las que no",
    "Tu madre es tan FAT que no puede leer archivos mayores que 4GB"
];

$chisteId = rand(0, 2);

echo "<h1>$chistes[$chisteId]</h1>";
?>