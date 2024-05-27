<?php
require_once("Parancsok.php");
require_once("Html.php");
class UserController{

    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "GET":
            default:
                self::getRequest();
                break;
        }
    }

    static function postRequest()
    {

        $parancsok =  new Parancsok();
        if (isset($_POST['btn-registraion'])) {
            //echo "kész";
            $email = $_POST['mail'];
            $vezNev = $_POST['lastname'];
            $kerNev = $_POST['firstname'];
            $password = $_POST['password'];
            if ($password == $_POST['passwordA']) {
                $parancsok->EmailKuldes($email,$vezNev,$kerNev,$password);
            }
            else{echo"A jelszó nem egyezik!";}
            /*Html::RegistraionEnd();
            if (isset($_POST['btn-resend'])) {
                $parancsok->EmailKuldes($email,$vezNev,$kerNev);
                Html::RegistraionEnd();
            }*/
        }

        if (isset($_POST['save'])) {
            $email = $_POST['mail'];
            $password = $_POST['password'];
            if ($password == $_POST['passwordA']) {
                $parancsok->passwordChange($email,$password);
            }
            else{echo"A jelszó nem egyezik!";}
        }

        if (isset($_POST['bjl'])) {
            $email = $_POST['mail'];
            $password = $_POST['password'];
            if ($parancsok->isAdmin($email,$password)) {
                Html::AdminMenu();
            }
            else {
                $parancsok->login($email,$password);
            }
            
        }

    }

    static function getRequest()
    {
        $parancsok =  new Parancsok();
        //echo"GET";
        if (isset($_GET['registraion-token'])) {
            //echo"teszt";
            $token = $_GET['registraion-token'];
            $parancsok->activateToken($token);
        }
    }

}