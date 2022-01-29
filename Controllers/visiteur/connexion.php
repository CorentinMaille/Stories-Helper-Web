<?php
//import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$envoi = GETPOST('envoi');

$email = GETPOST('email');
$password = GETPOST('password');
$message = GETPOST('message');
$rememberMe = GETPOST('rememberMe');

$User = new User();
$Organization = new Organization();

$errors = array();
$success = false;

$data = array();

$tpl = "connexion.php";

if(isset($_COOKIE["remember_me"]))
{
    $cookie = explode("-", $_COOKIE["remember_me"]);

    if($User->checkToken($cookie[0], $cookie[1]))
    {
        $User->fetch($cookie[0]);

        $_SESSION["rights"] = $User->isAdmin() == 1 ? "admin" : "user";
        $_SESSION["idUser"] = intval($User->getRowid());
        $_SESSION["idOrganization"] = intval($User->getFk_organization());

        $success = true;
    }
}

if($envoi)
{
    if($email && $password)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if($User->checkByEmail($email))
            {
                $User->fetchByEmail($email);

                if(password_verify($password, $User->getPassword()))
                {
                    $consent = $User->getConsent();
                    
                    $_SESSION["idUser"] = intval($User->getRowid());
                    $_SESSION["idOrganization"] = intval($User->getFk_organization());

                    if($rememberMe)
                    {
                        $token = bin2hex(random_bytes(15));
                        setcookie(
                            'remember_me',
                            $User->getRowid() . "-" . $token,
                            time() + 604800
                        );
                    }

                    $User->addCookie($User->getRowid(), $token);
                    
                    if($consent == true)
                    {
                        $_SESSION["rights"] = $User->isAdmin() == 1 ? "admin" : "user";
                    }
                    else
                    {
                        $_SESSION["rights"] = "needConsent";
                    }
                    
                    $success = true;
                } 
                else 
                {
                    $errors[] = "Le mot de passe est incorrect.";
                }
            }
            else
            {
                $errors[] = "Cette adresse email n'est associée à aucun compte.";
            }
        } 
        else 
        {
            $errors[] = "Le format de l'adresse email est incorrect.";
        }
    } 
    else 
    {
        $errors[] = "Un champs n'a pas été rempli.";
    }

}

if($success)
{
    header("location:".ROOT_URL."index.php");
    exit;
}
else
{
    require_once VIEWS_PATH."visiteur/".$tpl;
}