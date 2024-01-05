
<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['send'])){
// Reading file content
$myfile = fopen("main.html", "r") or die("Unable to open file!");
$file_content = " ";
while(!feof($myfile)) {
    echo "Reading main.html ... <br> ";
    $file_content .= fgets($myfile);
  }


fclose($myfile);

//writing post value in HTML
foreach($_POST as $key => $value) {
    if ($key === 'date-from-to' && empty($value)) {
        $value="decide later";
    }
    if (gettype($value) == "array"){
        $value = join(", ", $value);
    }
    try {
        $file_content = str_replace("{{".$key."}}", $value, $file_content);
    } catch (\Throwable $th) {
        echo $th;
    }
}

//Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'prajwalsiwakoti39@gmail.com';                     //SMTP username
    $mail->Password   = 'ayqh xbir usup fcnq ';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('prajwalsiwakoti39@gmail.com','wilderness-outdoors');
    $mail->addAddress('techsayatri@gmail.com', 'Reciever');     //Add a recipient
    if (!empty($_POST['email'])){
        $mail->addBcc($_POST["email"]);
    }
    
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'New Tailormade Tour Details';
    echo "sending main.html ... <br> ";
    $mail->Body = $file_content;
    

    $mail->send();
    setcookie("message","Form submitted sucessfully",0, "/TailorMade/","", false, false);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}" . "<br>";
    setcookie("message","Form didn't submitted sucessfully",0, "/TailorMade/","http://localhost", false, false);
}
}

$redirectUrl=$_SERVER["HTTP_REFERER"];
header("Location: $redirectUrl");

?>

