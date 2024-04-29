<?php
class Html{
    
    static function docType(){
        echo'<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <title>Raktár Rendszer</title>
        </head>' ;
    }

    static function LgnBtn(){

        echo'<form method="POST">
                <button class="lgn-button" type="submit" name="login">Login</button>
           </form>';
    }

    static function LoginMenu(){
        if (isset($_POST['login'])) {
            echo '<style type="text/css">.lgn-button  { display: none; }</style>';
            echo'<form method="POST" >
                    <div class="lgn-menu">
                        <input type="email" name="mail" placeholder="Email cím" ><br>
                        <input type="password" name="password" placeholder="Jelszó" ><br>
                        <button class="button" type="submit" name="bjl">Bejelentkezés</button>
                        <button class="button" type="submit" name="rgs">Regisztráció</button><br>
                        <button class="password-re" type="submit" name="pssRe">Elfelejtettem a jelszót</button><br>
                    </div>
                </form>';    
        }else{echo'';}
        
    }

    static function Registraion(){
        if (isset($_POST['rgs'])) {
            echo '<style type="text/css">.lgn-menu  { display: none; }</style>';
            echo '<style type="text/css">.lgn-button  { display: none; }</style>';
            echo '<form method="POST" >
                    <div class="rgs-menu">
                        <input type="text" placeholder="Vezetéknév" name="lastname" required>
                        <input type="text" placeholder="Keresztnév" name="firstname" required><br>
                        <input type="email" placeholder="Email cím" name="mail" required><br>
                        <input type="password" placeholder="Jelszó" name="password" required><br>
                        <input type="password" placeholder="Jelszó újra" name="passwordA" required><br>
                        <button class="button" type="submit" name="btn-registraion">Regisztráció</button>
                    </div>
                </form>';    
        }
    }

    static function PasswordRe(){
        if (isset($_POST['pssRe'])) {
            echo '<style type="text/css">.lgn-menu  { display: none; }</style>';
            echo '<style type="text/css">.lgn-button  { display: none; }</style>';
            echo '<form method="POST">
                    <div class="pssRe-menu">
                        <input type="email" name="mail" placeholder="Email cím" required><br>
                        <input type="password" name="password" placeholder="Új jelszó" required><br>
                        <input type="password"  name="passwordA" placeholder="Jelszó újra" required><br>
                        <button class="button" type="submit" name="save">Mentés</button>
                    </div>
                    </form>';                        
        }

    }

    static function RegistraionEnd()
    {
        echo '<style type="text/css">.lgn-menu  { display: none; }</style>';
        echo '<style type="text/css">.lgn-button  { display: none; }</style>';
        echo"<form method='POST'><div class='rgs-menu'>Kattintson az emailben küldött linkre a regisztráció véglegesítéséért!<br>";
        echo'<button class="button" type="submit" name="btn-resend">Email újra küldése</button></div></form>';
    }
}