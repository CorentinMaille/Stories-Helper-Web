<?php
require_once "../../services/header.php";

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $envoi = GETPOST('envoi');
    $email = GETPOST('email');
    $password = GETPOST('password');
    $message = GETPOST('message');
    $rememberMe = GETPOST('rememberMe');

    $User = new User();
    $Organization = new Organization();

    $error     = false;
    $success    = false;
    $rights     = false;

    $tpl = "connexion.php";

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
                        try {
                            $_SESSION["idUser"] = intval($User->getRowid());
                            $_SESSION["idOrganization"] = intval($User->getFk_organization());
    
                            $token = '';
                            if($rememberMe)
                            {
                                $token = bin2hex(random_bytes(15));
                                setcookie(
                                    'remember_me',
                                    $User->getRowid() . "-" . $token,
                                    time() + 604800,
                                    '',
                                    '',
                                    false, //true on production otherwise false
                                    true
                                );
                            }
    
                            $User->setToken($token);
                            $User->updateToken();
    
                            $consent = $User->getConsent();
                            if($consent == 1)
                            {
                                $_SESSION["rights"] = $User->isAdmin() == 1 ? "admin" : "user";
                                LogHistory::create($User->getFk_organization(), $User->getRowid(), "INFO", 'connect', 'user', $User->getLastname().' '.$User->getFirstname());
                            }
                            else
                            {
                                $_SESSION["rights"] = "needConsent";
                                LogHistory::create($User->getFk_organization(), $User->getRowid(), "INFO", 'connect', 'user', $User->getLastname().' '.$User->getFirstname());
                            }

                            $rights = $_SESSION['rights'] ?? false;
                            
                            $success = 'Vous êtes connecté.';
                        } catch (\Throwable $th) {
                            $error = $th;
                            // echo json_encode($th);
                        }                  
                    } 
                    else 
                    {
                        $error = "La paire identifiant / mot de passe est incorrecte.";
                    }
                }
                else
                {
                    $error = "La paire identifiant / mot de passe est incorrecte.";
                }
            } 
            else 
            {
                $error = "Le format de l'adresse email est incorrect.";
            }
        } 
        else 
        {
            $error = "Un champs n'a pas été rempli.";
        }

    }

    $response = array(
        'error'     => $error,
        'success'   => $success,
        'rights'    => $rights
    );

    echo json_encode($response);
}

?>