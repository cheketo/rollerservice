<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('../../../../vendors/phpmailer/src/PHPMailer.php');
include('../../../../vendors/phpmailer/src/Exception.php');
include('../../../../vendors/phpmailer/src/SMTP.php');

class Mailer extends PHPMailer
{
	var $Batch = false;
	
	const LOG_TABLE = 'core_log_email';
	const BATCH_TABLE = 'email_batch';
	
    function __contrsuct($Exceptions = null)
    {
        parent::__construct($Exceptions);
    }
    
    public function SetBatch($Batch)
    {
    	$this->Batch = $Batch;
    }
    
    public function InsertBatchEmail($AssocID,$Sender,$SenderName,$Receiver,$ReceiverName,$Subject,$HTML,$Files="",$AltBody="",$CC="",$BCC="")
    {
    	if($Files)
		{
		    if(is_array($Files))
		    {
		        foreach ($Files as $File)
		        {
		            $Attachment .= $Attachment? ",".$File:$File;
		        }
		    }else{
		        $Attachment = $Files;
		    }
		}
		
		if(is_array($CC))
		{
			foreach ($CC as $CCemail) {
				if(is_array($CCemail))
					$cc .= $cc?",".$CCemail[0]." ---> ".$CCemail[1]:$CCemail[0]." ---> ".$CCemail[1];
				else
					$cc .= $cc?",".$CCemail." ---> ".$CCemail:$CCemail." ---> ".$CCemail;
			}
		}else{
			$cc .= $CC." ---> ".$CC;
		}
		$CC = $cc;
		
		if(is_array($BCC))
		{
			foreach ($BCC as $BCCemail) {
				if(is_array($BCCemail))
					$bcc .= $bcc?",".$BCCemail[0]." ---> ".$BCCemail[1]:$BCCemail[0]." ---> ".$BCCemail[1];
				else
					$bcc .= $bcc?",".$BCCemail." ---> ".$BCCemail:$BCCemail." ---> ".$BCCemail;
			}
		}else{
			$bcc .= $BCC." ---> ".$BCC;
		}
		$BCC = $bcc;
		
		return Core::Insert(self::BATCH_TABLE,'sender,sender_name,receiver,receiver_name,subject,message,files,alt_message,cc,bcc,associated_id,creation_date,created_by,organization_id',"'".$Sender."','".$SenderName."','".$Receiver."','".$ReceiverName."','".$Subject."','".$HTML."','".$Attachment."','".$AltBody."','".$CC."','".$BCC."',".$AssocID.",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
    }
    
    function QuotationEmail($QuotationID,$ReceiverAddress,$ReceiverName,$Subject,$Attachments,$Sender='ventas@rollersevice.com.ar',$HTML='<html><body>Cotizaci&oacute;n Roller Service S.A.</body></html>',$AltBody='Cotización Roller Service',$CC="",$BCC="ventas@rollersevice.com.ar")
    {
    	$SenderName = 'Roller Service S.A.';
		if($this->Batch)
		{
			$Sent = $this->InsertBatchEmail($QuotationID,$Sender,$SenderName,$ReceiverAddress,$ReceiverName,$Subject,$HTML,$Attachments,$AltBody,$CC,$BCC);
		}else{
			$this->isSendmail();
			$this->setFrom($Sender, $SenderName);
			$this->addReplyTo($Sender, $SenderName);
			$this->addAddress($ReceiverAddress, $ReceiverName);
			$this->Subject = $Subject;
			$this->msgHTML($HTML);
			$this->AltBody = $AltBody;
			if(is_array($CC))
			{
				foreach ($CC as $CCemail) {
					if(is_array($CCemail))
						$this->AddCC($CCemail[0], $CCemail[1]);
					else
						$this->AddCC($CCemail, $CCemail);
				}
			}else{
				$this->AddCC($CC, $CC);
			}
			if(is_array($BCC))
			{
				foreach ($BCC as $BCCemail) {
					if(is_array($BCCemail))
						$this->AddBCC($BCCemail[0], $BCCemail[1]);
					else
						$this->AddBCC($BCCemail, $BCCemail);
				}
			}else{
				$this->AddBCC($BCC, $BCC);
			}
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
			$this->EmailLog($Sent,$ReceiverAddress,$Sender,$Subject,$HTML,$Attachments,$QuotationID);
		}
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
    	$LogID = Core::Insert(self::LOG_TABLE,'sender,receiver,subject,message,file,sent,error,associated_id,creation_date,created_by,organization_id',"'".$Sender."','".$Receiver."','".$Subject."','".$Message."','".$FileLog."','".$Sent."','".$Error."',".$AssocID.",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
    	if(!$LogID)
    		return $Sent;
    	else
    		return $LogID;
    }
    
    public function SendBatchEmails($ShowLogs=false)
    {
    	if(!$_SESSION[CoreOrganization::TABLE_ID]) $_SESSION[CoreOrganization::TABLE_ID]="000";
		if(!$_SESSION[CoreUser::TABLE_ID]) $_SESSION[CoreUser::TABLE_ID]="000";
		
    	if($ShowLogs) echo "Excuting emails query.<br><br>";
    	$Emails = Core::Select(self::BATCH_TABLE,'*',"status='P'");
    	if($ShowLogs) echo "Emails query executed :<br>".Core::LastQuery()."<br><br>";
    	foreach($Emails as $Email)
    	{
    		if($ShowLogs) print_r($Email);
    		if($ShowLogs) echo "<br><br>";
    		if($ShowLogs) echo "Email N&deg;".$Email['email_id']." is being configurated.<br>";
    		$this->isSendmail();
			$this->setFrom($Email['sender'], $Email['sender_name']);
			$this->addReplyTo($Email['sender'], $Email['sender_name']);
			$this->addAddress($Email['receiver'], $Email['receiver_name']);
			$this->Subject = $Email['subject'];
			$this->msgHTML($Email['message']);
			$this->AltBody = $Email['alt_message'];
			
			$CC = explode(",",$Email['cc']);
			$BCC = explode(",",$Email['bcc']);
			foreach($CC as $EmailCC)
			{
				$EmailCC = explode(" ---> ",$EmailCC);
				if($ShowLogs) echo "Adding ".$EmailCC[0]." as CC.<br>";
				$this->AddCC($EmailCC[0], $EmailCC[1]);
			}
			foreach($BCC as $EmailBCC)
			{
				$EmailBCC = explode(" ---> ",$EmailBCC);
				if($ShowLogs) echo "Adding ".$EmailBCC[0]." as BCC.<br>";
				$this->AddBCC($EmailBCC[0], $EmailBCC[1]);
			}
			
			if($Email['files'])
			{
				if($ShowLogs) echo "Adding files of email N&deg;".$Email['email_id'].".<br>";
				$Files = explode(",",$Email['files']);
			    if(is_array($Files))
			    {
			        foreach ($Files as $File)
			        {
			        	$this->addAttachment($File);
			        }
			    }else{
			        $this->addAttachment($Files);
			    }
			}
			if($ShowLogs) echo "Email N&deg;".$Email['email_id']." is being sent.<br>";
			$Sent = $this->send();
			if($Sent)
			{
				Core::Update(self::BATCH_TABLE,"status='F'","email_id=".$Email['email_id']);
				if($ShowLogs) echo "Email N&deg;".$Email['email_id']." sent OK.<br>";
			}else{
				Core::Update(self::BATCH_TABLE,"status='E'","email_id=".$Email['email_id']);
				if($ShowLogs) echo "Email N&deg;".$Email['email_id']." hs not been sent, there was an ERROR.<br>";
			}
			$this->EmailLog($Sent,$Email['receiver'],$Email['sender'],$Email['subject'],$Email['message'],$Email['files'],$Email['associated_id']);
    	}
    	if($ShowLogs && count($Emails)<1) echo "No se encuentran emails pendientes de envio.<br>";
    }
}
?>