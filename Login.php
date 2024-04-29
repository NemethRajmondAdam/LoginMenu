<?php
require_once('Html.php');
require_once('UserController.php');
require_once("Parancsok.php");
$parancsok = new Parancsok();

Html::docType();
Html::LgnBtn();
Html::Registraion();
Html::PasswordRe();
Html::LoginMenu();
UserController::handle();

?>
