<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once('vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('vendor/phpmailer/phpmailer/src/Exception.php');

class Parancsok{

    protected $mysqli;
    function __construct($host='localhost', $user='root', $password='', $db='raktar')
    {
        $this->mysqli=new mysqli($host, $user, $password, $db);
        $this->mysqli->set_charset('UTF8');
        if ($this->mysqli->connect_errno)
        {
            throw new Exception($this->mysqli->connect_errno);
        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    private function getNewToken()
    {
        return str_replace(["=","+"], ["",""], base64_encode(random_bytes(160/8)));
    }


    function EmailKuldes($email,$vezNev,$kerNev,$password){
        $mail = new PHPMailer();
        $token = $this->getNewToken();

        try {

            $mail->isSMTP();                                            
            $mail->Host       = 'localhost';                    
            $mail->SMTPAuth   = false;                                   
            $mail->Port       = 1025;                                
            
            $fullName = $vezNev." ".$kerNev; 

            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress($email,$fullName);     
        
            $mail->isHTML(true);                                 
            $mail->Subject = 'Raktár regisztráció';
            $mail->Body    = 'A regisztráció véglegesítésének érdekében kattintson az alábbi linkre: <a href="http://localhost:81/Raktar/Login.php?registraion-token='.$token.'">';
        
            $mail->send();
            //echo 'Message has been sent';
            $this->Registraion($email,$fullName,$password,$token);
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function keresEmail($email)
    {
        $query = "SELECT * FROM user WHERE email = '$email'";

        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    function Registraion($email,$fullName,$password,$token)
    {
        if (empty($this->keresEmail($email))) {
            $created_at = date("Y-m-d H:i:s");
            $jelenlegi_ido = time();
            $hozzaadando_ido = 3600;
            $uj_ido = $jelenlegi_ido + $hozzaadando_ido;

            $jelzso = password_hash($password,PASSWORD_DEFAULT);

            $token_valid_until= date("Y-m-d H:i:s",$uj_ido);
            $query = "INSERT INTO user (is_active,name,email,password,token,token_valid_until,created_at) VALUES ('0','$fullName','$email','$jelzso','$token','$token_valid_until','$created_at')";
            
            if ($this->mysqli->query($query) == TRUE) {
                echo "Kattinson az emailben kapott linkre a regisztráció véglegesítéséért!";
            }
            else {
                echo " ";
            }
        }
        else {
            echo 'Ezzel az email cím már létezik 1 profil!';
        }
    }

    function activateToken($token)
    {
        
        $registered_at = date("Y-m-d H:i:s");
        $token_valid_until = "SELECT token_valid_until FROM user WHERE token = '$token'";
        if ($token_valid_until>=$registered_at) {
            $query="UPDATE user SET is_active=1,registered_at='$registered_at' WHERE token ='$token' ";

            if ($this->mysqli->query($query) == TRUE) {
                echo "Sikeres regisztráció!";
            }
            else {
                echo "Hiba az adatok feltöltése közben.". $this->mysqli->error;
            }
        }
        else{
            echo"lejárt az idő!";
        }

    }

    function passwordChange($email,$password)
    {
        if (!empty($this->keresEmail($email))) {
            $jelszo = password_hash($password,PASSWORD_DEFAULT);
            $query="UPDATE user SET password='$jelszo' WHERE email ='$email' ";

            if ($this->mysqli->query($query) == TRUE) {
                echo " ";
            }
            else {
                echo "Hiba az adatok feltöltése közben.". $this->mysqli->error;
            }
        }
        else {
            echo"Ezzel az email címmel nincs profil létrehozva!";
        }

    }

    function login($email,$jelszo)
    {
        if (!empty($this->keresEmail($email))) {
            $result = $this->mysqli->query("SELECT password FROM user WHERE email='$email'");
            $password = $result->fetch_assoc();
            $hash = $password['password'];

            $verify = password_verify($jelszo, $hash);

            if ($verify) {
                $query="UPDATE user SET loged_in=1 WHERE email ='$email' ";

                if ($this->mysqli->query($query) == TRUE) {
                    echo "beléptél ";
                }
                else {
                    echo "Hiba az adatok feltöltése közben.". $this->mysqli->error;
                }
            }
            else{
                echo"Hibás jelszó!";
            }
            
        }
        else {
            echo"Ezzel az email címmel nincs profil létrehozva!";
        }
    }


    function isAdmin($email,$adminpass)
    {
        if (!empty($this->keresEmail($email))) {

            if ($adminpass=="1234567890") {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            echo"Ezzel az email címmel nincs profil létrehozva!";
        }
    }

}