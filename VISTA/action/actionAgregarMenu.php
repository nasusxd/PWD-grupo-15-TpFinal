<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";

$abmMenu = new ABMMenu();
echo json_encode($abmMenu->crearMenu(dataSubmitted()));
