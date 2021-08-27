<?php
$action = $_GET["action"] ?? false;
$idUser = $_GET["idUser"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$firstname = $_POST["prenom"] ?? false;
$lastname = $_POST["nom"] ?? false;
$email = $_POST["email"] ?? false;
$idPoste = $_POST["idPoste"] ?? false;
$idEquipe = $_POST["idEquipe"] ?? false;
$birth = $_POST["birth"] ?? false;

$oldmdp = $_POST["oldmdp"] ?? false;
$newmdp = $_POST["newmdp"] ?? false;
$newmdp2 = $_POST["newmdp2"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);
$Poste = new Poste();
$Equipe = new Equipe();

$page = $_SERVER["REQUEST_URI"];

if($page == 'listeMembres.php')
{
    $membres = $User->fetchAll($idOrganisation);
    $postes = $Poste->fetchAll($idOrganisation);
    $equipes = $Equipe->fetchAll($idOrganisation);
}


if($action == "updateFirstname")
{
    if($idUser && $firstname && $rights == "admin")
    {
        if($envoi)
        {
            $userFirstname = $User->getFirstname();
        
            if($firstname != $userFirstname)
            {
                try
                {
                    $User->updateFirstname($firstname);
                } 
                catch (exception $e)
                {
                    // header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=updatePrenomFatal');
                    $erreurs[] = "Le prénom n'a pas pu être modifié.";
                }
                // header("location:".VIEWS_PATH."admin/listeMembres.php?success=prenomUpdate");
                $success = "Le prénom a bien été modifié.";
            } 
            else 
            {
                // header("location:".VIEWS_PATH."admin/listeMembres.php?error=firstnameNoChange");
                $erreurs[] = "Le nom est le même qu'avant.";
            }
        } 
        else 
        {
            header("location:".ROOT_PATH."/index.php");
        }
    } 
    else 
    {
        header("location:".ROOT_PATH."/index.php");
    }
}


if($action == "updateLastname")
{
    if($idUser && $lastname && $rights == "admin")
    {
        if($envoi)
        {
            $userLastname = $User->getLastname();
        
            if($lastname != $userLastname)
            {
                try
                {
                    $User->updateLastname($lastname);
                } 
                catch (exception $e)
                {
                    // header('location:'.VIEWS_PATH.'/admin/listeMembres.php?error=lastnameUpdateFatal');
                    $erreurs[] = "La modification de nom n'a pas pu aboutir.";
                }
                // header("location:".VIEWS_PATH."admin/listeMembres.php?success=lastnameUpdate");
                $success = "Le nom a bien été modifié.";
            } 
            else 
            {
                // header("location:".VIEWS_PATH."admin/listeMembres.php?error=lastnameNoChange");
                $erreurs[] = "Le nom n'a pas été changé.";
            }
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updatePoste")
{
    if($idUser && $idPoste && $rights == "admin")
    {
        try 
        {
            $User->updatePoste($idPoste);
        }
        catch (exception $e)
        {
            // header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=posteUpdateFatal');
            $erreurs[] = "La modification de poste n'a pas pu aboutir.";
        }
        // header("location:".VIEWS_PATH."admin/listeMembres.php?success=posteUpdate");
        $success = "La modification de poste a bien été prise en compte.";
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updateEquipe")
{
    if($idUser && $idEquipe && $rights == "admin")
    {
        try
        {
            $User->updateEquipe($idEquipe);
        } 
        catch (exception $e)
        {
            // header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=equipeUpdateFatal');
            $erreurs[] = "La modification d'équipe n'a pas pu aboutir.";
        }
        // header("location:".VIEWS_PATH."admin/listeMembres.php?success=equipeUpdate");
        $success = "Le modification d'équipe a bien été prise en compte.";
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updatePassword")
{
    if($envoi)
    {
        if(!empty($oldmdp) && !empty($newmdp) && !empty($newmdp2))
        {
            if($newmdp === $newmdp2)
            {
                if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                {
                    // header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=mdpRules');
                    $erreurs[] = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                } 
                else
                {
                    $oldmdp = $User->getPassword();
                    if(!password_verify($oldmdp, hash($newmdp, PASSWORD_BCRYPT)))
                    {
                        $erreurs[] = "L'ancien mot de passe est incorrect.";
                        // header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=incorrectMdp');
                    } 
                    else 
                    {
                        if($oldmdp == hash($newmdp, PASSWORD_BCRYPT))
                        {
                            $erreurs[] = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
                            // header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=noChange');
                        } 
                        else 
                        {
                            try
                            {
                                $User->updatePassword(hash($newmdp, PASSWORD_BCRYPT));
                            } 
                            catch (Exception $e) 
                            {
                                $erreurs[] = "Erreur SQL : Le mot de passe n'a pas pu être changé.";
                            }
                            // header("location:".VIEWS_PATH."membres/passwordUpdate.php?success=1");
                            $success = true;
                        }
                    }
                }  
            } 
            else 
            {
                // header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=unmatch');
                $erreurs[] = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
            }
        } 
        else
        {
            // header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=missingInput');
            $erreurs[] = "Erreur : Un champs n'est pas rempli.";
        }

    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "signup")
{
    if($envoi)
    {
        if($email && $firstname && $lastname && $birth && $idPoste && $idEquipe)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $idPoste = intval($idPoste);
                if(is_int($idPoste))
                {
                    $idEquipe = intval($idEquipe); 
                    if(is_int($idEquipe))
                    {
                        $speciaux = "/[.!@#$%^&*()_+=]/";
                        $nombres = "/[0-9]/";
                        if(!preg_match($nombres, $firstname) && !preg_match($speciaux, $firstname))
                        {
                            if(!preg_match($nombres, $lastname) && !preg_match($speciaux, $lastname))
                            {
                                if($User->verifEmail($email))
                                {
                                    if($User->create($prenom, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation))
                                    {
                                        // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?success=1");
                                        $success = true;
                                    } 
                                    else 
                                    {
                                        // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=inscriptionfailed");
                                        $erreurs[] = "Erreur : L'inscription n'a pas pu aboutir.";
                                    }
                                    
                                } 
                                else 
                                {
                                    // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=emailindisponible");
                                    $erreurs[] = "Erreur : L'adresse email est déjà prise.";
                                }

                            } 
                            else 
                            {
                                // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=nommatch");
                                $erreurs[] = "Erreurs : Le nom n'est pas correct.";
                            }
                        
                        } 
                        else 
                        {
                            // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=prenommatch");
                            $erreurs[] = "Erreur : Le prénom n'est pas correct.";
                        }

                    } 
                    else 
                    {
                        // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=idequipeint");
                        $erreurs[] = "L'équipe n'est pas correct.";
                    }

                } 
                else 
                {
                    // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=idposteint");
                    $erreurs[] = "Le poste n'est pas correct.";
                }

            } 
            else 
            {
                // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=emailvalidate");
                $erreurs[] = "Le format de l'adresse email n'est pas correct.";
            }

        } 
        else 
        {
            // header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=champsvide");
            $erreurs[] = "Un champs n'est pas rempli.";
        }
    } 
    else 
    {
        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php");
    }
}
?>