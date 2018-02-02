<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('../../../../vendors/phpmailer/src/PHPMailer.php');
include('../../../../vendors/phpmailer/src/Exception.php');
include('../../../../vendors/phpmailer/src/SMTP.php');

class Mailer extends PHPMailer
{
	const LOG_TABLE = 'core_log_email';
	
    function __contrsuct($Exceptions = null)
    {
        parent::__construct($Exceptions);
    }
    
    function QuotationEmail($ReceiverAddress,$ReceiverName,$Subject,$Attachments,$Sender='ventas@rollersevice.com.ar',$HTML='<html><body>Cotizaci&oacute;n Roller Service S.A.</body></html>',$AltBody='Cotización Roller Service')
    {
        $this->isSendmail();
		$this->setFrom($Sender, 'Roller Service S.A.');
		$this->addReplyTo($Sender, 'Roller Service S.A.');
		$this->addAddress($ReceiverAddress, $ReceiverName);
		$this->Subject = $Subject;
		$this->msgHTML($HTML);
		$this->AltBody = $AltBody;
		if($Attachments)
		{
		    if(is_array($Attachments))
		    {
		        foreach ($Attachments as $Attachment)
		        {
		            $this->addAttachment($Attachment);
		        }
		    }else{
		        $this->addAttachment($Attachments);
		    }
		}
		$Sent = $this->send();
		$this->EmailLog($Sent,$ReceiverAddress,$Sender,$Subject,$HTML,$Attachments);
		return $Sent;
    }
    
    public function EmailLog($Sent,$Receiver,$Sender,$Subject,$Message="",$Files="",$AssocID=0)
    {
    	$Sent = $Sent?"OK":"ERROR";
	    if(is_array($Files))
	        foreach ($Files as $File)
	            $FileLog .= $FileLog? " - ".$File:$File;
	    else
	        $FileLog = $Files;
		$Error = $this->ErrorInfo?$this->ErrorInfo:"";
    	$LogID = Core::Insert(self::LOG_TABLE,'sender,receiver,subject,message,file,sent,error,associated_id',"'".$Sender."','".$Receiver."','".$Subject."','".$Message."','".$FileLog."','".$Sent."','".$Error."',".$AssocID);
    	if(!$Sent)
    		return $Sent;
    	else
    		return $LogID;
    }
}
?>