<?php 
// import all models
require_once "../../services/header.php";

// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $rights = $_SESSION["rights"] ?? false;
    $idOrganization = $_SESSION["idOrganization"] ?? null;
    $idUser = $_SESSION['idUser'] ?? null;

    if($rights == 'admin' && $idUser > 0 && $idOrganization > 0)
    {
        $action = htmlentities(GETPOST('action'));
        $offset = intval(htmlentities(GETPOST('offset')));

        $Organization = new Organization();
        $Organization->setRowid($idOrganization);
        $Organization->setPrivacy(1);

        switch($action)
        {
            case 'loadmore':
                if($offset)
                {
                    try {
                        $Organization->fetchNextUsers($offset);
                        $users = $Organization->getUsers();
                        
                        if(is_array($users) && count($users) > 0)
                        {
                            // return new users
                            echo json_encode($users);
                        }
                    } catch (\Throwable $th) {
                        echo json_encode($th);
                        // echo json_encode(false);
                    }
                    break;
                }
        }
    }
    else
    {
        header("location:".ROOT_URL."index.php");
    }
} else {
    header("location:".ROOT_URL."index.php");
} 
?>