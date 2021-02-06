<?php

include_once('./percorsi.php');


/**
 * This example shows how to extend PHPMailer to simplify your coding.
 * If PHPMailer doesn't do something the way you want it to, or your code
 * contains too much boilerplate, don't edit the library files,
 * create a subclass instead and customise that.
 * That way all your changes will be retained when PHPMailer is updated.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once (ROOT_DIR . '/vendor/autoload.php');
require_once (ROOT_DIR . '/private/mail_credential.php');


/**
 * Use PHPMailer as a base class and extend it
 */
class MailGenerica extends PHPMailer
{
    
    /**
     * myPHPMailer constructor.
     *
     * @param bool| null $exceptions
     * @param string    $body A default HTML message body
     */
    public function __construct($exceptions)
    {
        //Don't forget to do this or other things may not be set correctly!
        parent::__construct($exceptions);

        $this->SMTPDebug = SMTP::DEBUG_OFF;

        $this->CharSet = "UTF-8";

        $this->SMTPAuth = true;                               // Enable SMTP authentication
        
        $this->Username = USERNAME;
        
        $this->Password = PASSWORD;
        
        //Set a default 'From' address
        $this->setFrom(FROM, FROM);
        //Send via SMTP
        
        $this->isSMTP();
        //Equivalent to setting `Host`, `Port` and `SMTPSecure` all at once
        

        $this->Host = HOST;

        
        
        
        //Inject a new debug output handler
        // $this->Debugoutput = static function ($str, $level) {
        // echo "Debug level $level; message: $str\n";
        // };

    }


    //Extend the send function
    public function invia($email, $soggetto, $corpomail)
    {

       
        if ($email) {
            $this->addAddress($email);
        }
    
        $this->Subject = $soggetto;


        $this->msgHTML($corpomail);


        
        $r = parent::send();
        // echo 'I sent a message';
        return $r;
    }
}

