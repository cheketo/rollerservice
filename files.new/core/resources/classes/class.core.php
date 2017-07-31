<?php
    class Core
    {
        const DEFAULT_GRID_IMG = '../../../../skin/images/body/pictures/img-back-gen.jpg';
        
        static function Select($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
    	{
    		return $GLOBALS['DB']->Select($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
    	}
    	
    	static function SelectRow($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
    	{
    		return $GLOBALS['DB']->SelectRow($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
    	}
    	
    	static function NumRows($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
    	{
    		return $GLOBALS['DB']->NumRows($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
    	}
    	
    	static function Insert($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
    	{
    		return $GLOBALS['DB']->Insert($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
    	}
    	
    	static function Update($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
    	{
    		return $GLOBALS['DB']->Update($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
    	}
    	
    	static function Delete($Table,$Where)
    	{
    		return $GLOBALS['DB']->Delete($Table,$Where);
    	}
    	
    	static function TableData($Table)
    	{
    	    return $GLOBALS['DB']->TableData($Table);
    	}
    	
    	static function AffectedRows()
    	{
    		return $GLOBALS['DB']->AffectedRows();
    	}
    	
    	static function LastQuery()
    	{
    		return $GLOBALS['DB']->LastQuery();
    	}
    	
    	public static function InsertArrayToID($Table,$Fields,$ID,$IDs)
    	{
    	    if($IDs && $IDs!='null')
    	        return Core::Insert($Table,$Fields,self::AssocArrayToID($ID,$IDs));
    	}
    	
    	public static function AssocArrayToID($ID,$Values=array())
    	{
    	    if($ID>0 && !empty($Values))
    	    {
    	        if(!is_array($Values))
    	            $Values = explode(",",$Values);
    	        for($i=0;$i<count($Values);$i++)
        		{
        			if(intval($Values[$i])>0)
        				$String .= !$String? $ID.",".$Values[$i] : "),(".$ID.",".$Values[$i];
        		}
        		return $String;
    	    }
    	}
        
        public static function include_dir($Path,$s="/")
        {
            $handle = opendir($Path);
            while ($file = readdir($handle)) {
                $ext = substr(strtolower($file),-3);
                if($ext == 'php') include_once($Path.$s.$file);
            }
            closedir($handle);
        }
        
        public static function Autoload($Resource)
    	{
    	    
    		$File	        = strtolower(self::SplitAtUpperCaseAddDot($Resource));
    		$CoreClass	    = "../../../core/resources/classes/class".$File.".php";
    		$CoreTrait      = "../../../core/resources/traits/trait".$File.".php";
    		$ProjectClass   = "../../../project/resources/classes/class".$File.".php";
    		$ProjectTrait	= "../../../project/resources/traits/trait".$File.".php";
    		if(file_exists($CoreClass))
    		{
    			include_once $CoreClass;
    		}elseif(file_exists($CoreTrait)){
    			include_once $CoreTrait;
    		}elseif(file_exists($ProjectClass)){
    			include_once $ProjectClass;
    		}elseif(file_exists($ProjectTrait)){
    			include_once $ProjectTrait;
    		}else{
    		    echo $CoreTrait."<br>";
    		    echo $Resource." NOT FOUND!<br>";
    		}
    	}
        
        public static function SplitAtUpperCaseAddDot($String){
            return preg_replace('/([a-z0-9])?([A-Z])/','$1.$2',$String);
        }
        
        public static function GetLink()
    	{
    		$ActualUrl	= explode("/",$_SERVER['PHP_SELF']);
    		return $ActualUrl[count($ActualUrl)-4]."/".$ActualUrl[count($ActualUrl)-3]."/".$ActualUrl[count($ActualUrl)-2]."/".basename($_SERVER['PHP_SELF']);
    	}
        
        public static function DecimalNumber($n, $n_decimals)
        {
            return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
        }
        
        static function GetDirFiles($Path)
    	{
    		$Files 	= array();
    		if(!is_dir($Path)) mkdir($Path);
    	    $Dir 	= opendir($Path);   
    	    while ($Element = readdir($Dir)){
    	        if( $Element != "." && $Element != ".."){
    	            if( is_dir($Path.$Element) ){
    	                //Add Dir Files
    	                $Files[] = self::GetDirFiles($Element);
    	            } else {
    	                //Add File
    	                $Files[] = $Element;
    	            }
    	        }
    	    }
    	    return $Files;
    	}
    
    	static function GetFileTypeImg($Ext)
    	{
    		switch(strtolower($Ext))
    		{
    			case "xls":
    			case "xlsx":
    			case "xlt":
    			case "xltx":
    			case "csv":
    				$Ext = "xls";
    			break;
    			case "doc":
    			case "dot":
    			case "docx":
    			case "docm":
    			case "dotx":
    			case "dotm":
    				$Ext = "doc";
    			break;
    			case "ppt":
    			case "pot":
    			case "pps":
    				$Ext = "ppt";
    			break;
    			case "pdf":
    				$Ext = "pdf";
    			break;
    			case "avi":
    				$Ext = "avi";
    			break;
    			case "mp3":
    				$Ext = "mp3";
    			break;
    			case "3gp":
    				$Ext = "3gp";
    			break;
    			case "wav":
    				$Ext = "wav";
    			break;
    			case "rar":
    			case "zip":
    				$Ext = "rar";
    			break;
    			case "wma":
    				$Ext = "wma";
    			break;
    			case "wmv":
    				$Ext = "wmv";
    			break;
    			default: $Ext = "txt"; break;
    
    		}
    		return $Ext;
    	}
    	
    	public static function FromNumberToMoneyLabel($Number)
    	{
    	    if($Number==0)
    	        $Class = 'default';
    	    else
    	        $Class = intval($Number) > 0? 'success':'danger';
    	    return '<span class="label label-'.$Class.'">'.self::FromDBToMoney($Number).'</span>';
    	}
    	
    	public static function FromDBToMoney($Number)
    	{
    	    return "$".number_format($Number, 2, ',', '.');
    	}
    	
    	public static function FromMoneyToDB($String)
    	{
    	    $String=trim($String);
    	    $String=str_replace("$","",$String);
			if(substr($String,-3,1)=="." || substr($String,-3,1)=="," || substr($String,-2,1)=="." || substr($String,-2,1)==",")
			{
			    if(substr($String,-3,1)=="." || substr($String,-3,1)==",")
				    $String=str_replace(".","",substr($String,0,-3)).".".substr($String,-2);
				else
				    $String=str_replace(".","",substr($String,0,-2)).".".substr($String,-1);
		    }else{
				$String=str_replace(".","",$String);
		    }
			$String=str_replace(",","",$String);
			return $String;
    	}
    	
    	public static function FromNumberToCUIT($Number)
    	{
    	    return substr($Number,0,2).'-'.substr($Number,2,8).'-'.substr($Number,10,1);
    	}
    	
    	public static function FromCUITToNumber($CUIT)
    	{
    	    return str_replace('-','',$CUIT);
    	}
    	
    	public function EmailLink($Email)
    	{
    	    return '<a href="mailto:'.$Email.'">'.$Email.'</a>';
    	}
    
    	static function MonthFormat($Month)
    	{
    		$FormatedMonth		= array();
    		$FormatedMonth[1]	= 'Enero';
    		$FormatedMonth[2]	= 'Febrero';
    		$FormatedMonth[3]	= 'Marzo';
    		$FormatedMonth[4]	= 'Abril';
    		$FormatedMonth[5]	= 'Mayo';
    		$FormatedMonth[6]	= 'Junio';
    		$FormatedMonth[7]	= 'Julio';
    		$FormatedMonth[8]	= 'Agosto';
    		$FormatedMonth[9]	= 'Septiembre';
    		$FormatedMonth[10]	= 'Octubre';
    		$FormatedMonth[11]	= 'Noviembre';
    		$FormatedMonth[12]	= 'Diciembre';
    		
    		return $FormatedMonth[$Month];
    	}
    	
    	static function AddSlashesArray($Array)
    	{
    		foreach($Array as $Key => $Value)
    		{
    				if(is_array($Value))
    			{
    				$Array[$Key]=self::AddSlashesArray($Value);
    			}else{
    				$Array[$Key]=addslashes($Value);
    			}
    		}
    		return $Array;
    	}
    	
    	static function Utf8EncodeArray($Array)
    	{
    		foreach($Array as $Key => $Value)
    		{
    			if(is_array($Value))
    			{
    				$Array[$Key]=self::Utf8EncodeArray($Value);
    			}else{
    				$Array[$Key]=utf8_encode($Value);
    			}
    		}
    		
    		return $Array;
    	}
    	
    	static function InvoiceNumber($Number)
    	{
    		return sprintf("%08d",$Number);
    	}
    	
    	static function InvoicePrefixNumber($Number)
    	{
    		return sprintf("%04d",$Number);
    	}
    	
    	static function CUITFormat($Number)
    	{
    		return substr($Number,0,2)."-".substr($Number,2,8)."-".substr($Number,10,1);
    	}
    	
    	static function DateTimeFormat($DateTime,$Mode='')
    	// Returns a formated date
    	{
    		switch(strtolower($Mode))
    		{
    		    case 'time':
    		        $Time	= explode(" ",$DateTime);
    		        return $Time[1];
    		    break;
    			case 'full':
    				$DateTime	= explode(" ",$DateTime);
    				$Time		= $DateTime[1];
    				$Date		= explode("-",$DateTime[0]);
    				$Day		= $Date[2];
    				$Month		= self::MonthFormat(intval($Date[1]));
    				$Year		= $Date[0];
    				
    				$DateTime	= $Day." de ".$Month." del ".$Year;
    				return		$Time? $DateTime.", a las ".$Time : $DateTime;
    			break;
    			case 'complete':
    				$DateTime	= explode(" ",$DateTime);
    				$Time		= $DateTime[1];
    				$Date		= explode("-",$DateTime[0]);
    				$Day		= $Date[2];
    				$Month		= $Date[1];
    				$Year		= substr($Date[0],2);
    				
    				$DateTime	= $Day."/".$Month."/".$Year;
    				return		$Time? $DateTime." | ".$Time." Hs." : $DateTime;
    			break;
    			default:
    			    $Date	= explode(" ",$DateTime);
    		        return implode('/',array_reverse(explode('-',$Date[0])));
    			break;
    		}	
    	}
    	
    	static function InsertElement($Type,$ID='',$Value='',$Class='',$Extra='',$Query='',$FirstValue='',$FirstText='')
    	// Returns Form Elements
    	{
    		if($Type=="checkbox")  $Class = 'CheckBox '.$Class;
    		$Class	= $Class? ' class="'.$Class.'" ': '';
    		if($Type!="textarea")
    			if(!in_array($Type,array("select","button","multiple")))  $Value	= $Value? ' value="'.$Value.'" ': '';
    			//if($Type!="select" && $Type!="button")  $Value	= $Value? ' value="'.$Value.'" ': '';
    
    
    		
    		switch(strtolower($Type)){
    			case 'text': 		return '<input type="text" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    			case 'password': 	return '<input type="password" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    			case 'textarea': 	return '<textarea id="'.$ID.'" name="'.$ID.'"'.$Class.$Extra.' >'.$Value.'</textarea>'; break;
    			case 'checkbox': 	return '<input type="checkbox" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    			case 'radio': 		return '<input type="radio" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    			case 'hidden': 		return '<input type="hidden" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    			case 'image': 		return '<input type="text" readonly="readonly" id="File'.$ID.'" name="FileField"'.$Value.$Class.$Extra.' /><input type="file" id="'.$ID.'" name="'.$ID.'" class="Hidden" />'; break;
    			case 'file': 		return '<input type="text" readonly="readonly" id="File'.$ID.'" name="FileField"'.$Value.$Class.$Extra.' /><input type="file" id="'.$ID.'" name="'.$ID.'" class="Hidden" />'; break;
    			case 'button': 		return '<button id="'.$ID.'" name="'.$ID.'"'.$Class.$Extra.' >'.$Value.'</button>'; break;
    			case 'select': 		$Select	  = 	'<select id="'.$ID.'" name="'.$ID.'"'.$Class.$Extra.' firstvalue="'.$FirstValue.'" firsttext="'.$FirstText.'" >';
    								$Select	 .= $FirstValue || $FirstText ? '<option value="'.$FirstValue.'" >'.$FirstText.'</option>' : '' ;
    								
    								if(is_array($Query))
    								{
    									while ($Data = current($Query))
    									{
    										if(is_array($Data))
    										{
    												$Selected 	= $Data[key($Data)] == $Value ? ' selected="selected" ' : '';
    												$Select	   .= '<option value="'.$Data[key($Data)].'" '.$Selected.' >';
    												next($Data);
    												$Select .= $Data[key($Data)].'</option>';
    											
    										}else{
    											
    											$Selected	 = key($Query)==$Value ? ' selected="selected" ' : '';
    											$Select		.= '<option value="'.key($Query).'" '.$Selected.' >'.$Data.'</option>';
    											
    										}
    										next($Query);
    									}
    								}else{
    									
    									if($Query) include($Query);
    									
    								}
    								$Select	.=	'</select>';
    								return	$Select;
    			break;
    			case 'multiple': 	$Select	= 	'<select id="'.$ID.'" multiple="multiple" name="'.$ID.'"'.$Class.$Extra.' firstvalue="'.$FirstValue.'" firsttext="'.$FirstText.'" >';
    								$Select	.= $FirstValue || $FirstText ? '<option value="'.$FirstValue.'" >'.$FirstText.'</option>' : '' ;
    								$Values	= explode(",",$Value);
    								if(is_array($Query))
    								{
    									while ($Data = current($Query))
    									{
    										if(is_array($Data))
    										{
    												$Selected 	= in_array($Data[key($Data)],$Values) ? ' selected="selected" ' : '';
    												$Select	   .= '<option value="'.$Data[key($Data)].'" '.$Selected.' >';
    												next($Data);
    												$Select .= $Data[key($Data)].'</option>';
    											
    										}else{
    											
    											$Selected	 = in_array(key($Query),$Values) ? ' selected="selected" ' : '';
    											$Select		.= '<option value="'.key($Query).'" '.$Selected.' >'.$Data.'</option>';
    											
    										}
    										next($Query);
    									}
    								}else{
    									
    									if($Query) include($Query);
    									
    								}
    								$Select	.=	'</select>';
    								return	$Select;
    			break;
    								
    			default: 			return '<input type="'.$Type.'" id="'.$ID.'" name="'.$ID.'"'.$Value.$Class.$Extra.' />'; break;
    		}
    	}
    
    	static function ReplaceLatin($Str)
    	{
    		$Str = str_replace("ñ","n",$Str);
    		$Str = str_replace("Ñ","N",$Str);
    		$Str = str_replace("á","a",$Str);
    		$Str = str_replace("Á","A",$Str);
    		$Str = str_replace("é","e",$Str);
    		$Str = str_replace("É","E",$Str);
    		$Str = str_replace("í","i",$Str);
    		$Str = str_replace("Í","I",$Str);
    		$Str = str_replace("ó","o",$Str);
    		$Str = str_replace("Ó","O",$Str);
    		$Str = str_replace("ú","u",$Str);
    		$Str = str_replace("Ú","U",$Str);
    		$Str = str_replace(" ","",$Str);
    		$Str = str_replace("ä","a",$Str);
    		$Str = str_replace("Ä","A",$Str);
    		$Str = str_replace("ë","e",$Str);
    		$Str = str_replace("Ë","E",$Str);
    		$Str = str_replace("ï","i",$Str);
    		$Str = str_replace("Ï","I",$Str);
    		$Str = str_replace("ö","o",$Str);
    		$Str = str_replace("Ö","O",$Str);
    		$Str = str_replace("ü","u",$Str);
    		$Str = str_replace("Ü","U",$Str);
    		$Str = str_replace("â","a",$Str);
    		$Str = str_replace("Â","A",$Str);
    		$Str = str_replace("ê","e",$Str);
    		$Str = str_replace("Ê","E",$Str);
    		$Str = str_replace("î","i",$Str);
    		$Str = str_replace("Î","I",$Str);
    		$Str = str_replace("ô","o",$Str);
    		$Str = str_replace("Ô","O",$Str);
    		$Str = str_replace("û","u",$Str);
    		$Str = str_replace("Û","u",$Str);
    
    		return $Str;
    	}
    
    	static function MoveImage($New,$Temp,$Old='')
    	{
    		$Tmp = array_reverse(explode("/", $Temp));
    		if(file_exists($Old)) unlink($Old);
    		if(file_exists($Temp))
    		{
    			copy($Temp, $New);
    			unlink($Temp);
    		}
    		return file_exists($New);
    	}
    	
    	static function IsMobile()
    	{
    		$UserAgent = $_SERVER['HTTP_USER_AGENT'];
    		return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$UserAgent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($UserAgent,0,4));
    	}
    	
    	static function ValidateID($Data)
    	{
    		if(!$_GET['id'] || empty($Data))
    		{
    			header('Location: list.php?error=user');
    			die();
    		}
    	}
    	
    	static function InsertAutolocationMap($ID=1,$Data=array())
    	{
    		$html = '<div id="map'.$ID.'_ErrorMsg" class="ErrorText Red Hidden">Seleccione una ubicaci&oacute;n</div>';
    		
    		$html .= self::InsertElement('hidden','map'.$ID.'_lat',$Data['lat']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_lng',$Data['lng']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_address',$Data['address']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_address_short',$Data['address']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_zone',$Data['zone']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_zone_short',$Data['zone_short']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_region',$Data['region']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_region_short',$Data['region_short']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_province',$Data['province']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_province_short',$Data['province_short']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_country',$Data['country']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_country_short',$Data['country_short']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_postal_code',$Data['postal_code']);
    		$html .= self::InsertElement('hidden','map'.$ID.'_postal_code_suffix',$Data['postal_code_suffix']);  
    		$html .= self::InsertElement('text','pac-input'.$ID,'','pac-input controls');
        	$html .= '<div id="map'.$ID.'" class="GoogleMap" map="'.$ID.'" style="position:relative!important;"></div>';
        	
        	return $html;
    	}
    }
?>