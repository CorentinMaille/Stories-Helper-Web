<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$success = false;
$erreurs = array();

$tpl = "listeProjets.php";
$data = new stdClass;

if($rights === "admin")
{




    $data = array(
        "success" => $success,
        "erreurs" => $erreurs,
        "currentProjects" => $currentProjects,
    );

    $data = json_encode($data);

    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}