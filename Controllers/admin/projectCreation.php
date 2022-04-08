<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlentities(GETPOST('action'));
$idProject = intval(GETPOST('idProject'));
$name = htmlentities(GETPOST('name'));
$type = htmlentities(GETPOST('type'));
$description = htmlentities(GETPOST('description'));
$envoi = GETPOST('envoi');

$success = false;
$errors = array();

$Project = new Project();

$errors = array();
$success = false;

$tpl = "projectCreation.php";
$page = CONTROLLERS_URL."admin/".$tpl;

if($action == "addProjet")
{
    if($envoi || $idProject)
    {
        if($name && $type)
        {
            try {
                $Project->setName($name);
                $Project->setType($type);
                $Project->setDescription($description);
                $Project->setFk_organization($idOrganization);
                $lastInsertedId = $Project->create();
                LogHistory::create($idUser, 'create', 'project', $lastInsertedId, "organization", $idOrganization, $idOrganization, "INFO", null, $ip, $page);
                $success = "Le projet a été créé avec succès.";
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = "Une erreur est survenue.";
                LogHistory::create($idUser, 'create', 'project', $lastInsertedId, "organization", $idOrganization, $idOrganization, "INFO", $th->getMessage(), $ip, $page);
            }
        } 
        else 
        {
            $errors[] = "Tous les champs ne sont pas remplis.";
        }
    } 
    else 
    {
        header('location:'.ROOT_PATH.'index.php');
    }
}

require_once VIEWS_PATH."admin/".$tpl;
?>