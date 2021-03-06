<?php 
// import all models
require_once "../services/header.php";
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $action = GETPOST('action');

    switch($action) {
        case 'consentCookie':
            try {
                setcookie(
                    'consentCookie',
                    1,
                    time()+86400,
                    '',
                    '',
                    false, //true on production otherwise false
                    true
                );
                echo json_encode($_COOKIE);
            } catch (\Throwable $th) {
                //throw $th;
                echo json_encode(false);
            }
            break;
        case 'checkCookieConsent':
            if(isset($_COOKIE['consentCookie']) && $_COOKIE['consentCookie'] == 1)
            {
                echo json_encode(true);
            }
            else
            {
                echo json_encode(false);
            }
            break;
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>