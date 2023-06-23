<?php
require("Classes/Classes.php");
session_save_path("../sessions");
session_start();
if($_SERVER["REQUEST_METHOD"]=="POST")
{  
    function OTP()
    {
        $string = "qwertyuiopa7418520963sdfghjklzxcvbnm";
        $otp="";
        $r = rand(8,35);
        for($i=0;$i<=$r;$i++)
        {
            $otp .= $string[rand(0,strlen($string))];
        }
        return $otp;
    }

    $data = $P->data_citizen($_POST["VC"]);

    if($data)
    {
        if($P->has_voted($_POST["VC"])){die("Error: You have already Voted ");}
        $_SESSION["OTP"]=OTP();
        send_mail($data["VEmail"],"OTP req","Election OTP".date("y-m-d"),"Your otp is: <b>".$_SESSION["OTP"]."</b>","Your otp is: ".$_SESSION["OTP"]);
    }
    else
    {
        echo "Error: Invalid voter card";
    }
    // echo $_SESSION["OTP"];
}



?>