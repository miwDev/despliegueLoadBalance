<?php
require_once '/var/www/html/VotosRepo.php';

$repo = new VotosRepo('db1', 'user', '1234', 'linares_db');

echo "Dirección del Servidor ==> " . $_SERVER["SERVER_ADDR"] . "<br>"; // debug para enseñar el cambio de carga entre contenedores
echo "Nombre del Servidor ==> " . $_SERVER['SERVER_NAME'] . "<br>";

$resultado_mensaje = "";

if (isset($_POST['respuesta'])) {
    $respuesta = $_POST['respuesta'];

    if ($respuesta === 'si') {
        $repo->saveVotoSi();
        $resultado_mensaje = "Muy buena respuesta ciudadano Jienense";
    } elseif ($respuesta === 'no') {
        $repo->saveVotoNo();
        $resultado_mensaje = "Los servicios del estado estan de camino a su localización";
    }
}

$votosTotalesSi = $repo->findAllSi();
$votosTotalesNo = $repo->findAllNo();

echo "<h1>¿Independizar Linares de la provincia de Jaén?</h1>";
echo "<form method=\"POST\" action='#' >";
echo "    <button type=\"submit\" name=\"respuesta\" value=\"si\">Sí</button>";
echo "    <button type=\"submit\" name=\"respuesta\" value=\"no\">No</button>";
echo "</form>";

if (!empty($resultado_mensaje)) {
    echo "<p>" . $resultado_mensaje . "</p>";
}

echo "<h2>Votos totales SI: $votosTotalesSi</h2>";
echo "<h2>Votos totales NO: $votosTotalesNo</h2>";
?>