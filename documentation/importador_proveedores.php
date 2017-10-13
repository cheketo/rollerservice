<?php
    
    function CheckCUIT($CUIT)
	{
		$CUIT = preg_replace('/[^\d]/','',(string)$CUIT);
		if(strlen($CUIT)!= 11)
		{
			return false;
		}
		$Sum = 0;
		$Digits = str_split($CUIT);
		$Digit = array_pop($Digits);
		for($I=0;$I<count($Digits);$I++)
		{
			$Sum+=$Digits[9-$I]*(2+($I%6));
		}
		$Verif = 11-($Sum%11);
		$Verif = $Verif==11?0:$Verif;
		return $Digit==$Verif;
	}
    include("../files/core/resources/classes/class.core.data.base.php");
    include("../files/core/resources/classes/class.core.php");
    
    $GLOBALS['DB'] = new CoreDataBase();
    $GLOBALS['DB']->Connect();
    $I=0;
    $File = fopen("PROVEEDOR_COMAS.csv", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("%",",",str_replace(",",";",str_replace(";","%",fgets($File)))));
        if($I>1 && $I<100)
        {    
            $Row = explode(";",$Line);
            if($Row[0]>0)
            {
                //PROVIDER
                // $ID = trim($Row[0]);
                $Name = trim($Row[2])? "'".strtoupper(trim($Row[1])." ".trim($Row[2]))."'":"'".strtoupper(trim($Row[1]))."'";
                $CUIT = trim($Row[3])? str_replace("-","",trim($Row[3])):0;
                $IIBB = trim($Row[13])?"'".strtoupper(trim($Row[13]))."'":"''";
                $Balance = intval(trim($Row[14]))?intval(trim($Row[14]))/100:0;
                $BalanceInitial=intval(trim($Row[15]))?intval(trim($Row[15]))/100:0;
                $BalancePositive=intval(trim($Row[16]))?intval(trim($Row[16]))/100:0;
                $ConditionID = trim($Row[18])>0?trim($Row[18]):0;
                // $ProviderNumber=trim($Row[19])>0?"'".trim($Row[19])."'":0;;
                $CurrencyID = $Row[19]==2?1:2;
                // $Reputation = strtoupper(trim($Row[20]))=='S'?-1:0;
                // $CredLimit = trim($Row[21])>0?trim($Row[21]):0;
                $International = $ProvinceID==25?"'Y'":"'N'";
                $Provider = "'Y'";
                
                switch (trim($Row[12])) {
                    case 'EX':
                        $IvaID=4;
                    break;
                    case 'XE':
                        $IvaID=8;
                    break;
                    case 'CF':
                        $IvaID=5;
                    break;
                    case 'MT':
                        $IvaID=12;
                    break;
                    case 'IN':
                        $IvaID=1;
                    break;
                    default:
                        $IvaID=0;
                    break;
                }
                $TypeID = 1;
                
                $Company = Core::Select("view_company_list","*","name=".$Name." OR cuit=".$CUIT)[0];
                $CompanyID = $Company['company_id'];
                
                if(!$CompanyID)
                {
                    $CompanyID = Core::Insert('company','name,cuit,type_id,iva_id,iibb,balance,balance_initial,balance_positive,purchase_condition_id,currency_id,international,provider,organization_id,creation_date',$ID.",".$Name.",".$CUIT.",".$TypeID.",".$IvaID.",".$IIBB.",".$Balance.",".$BalanceInitial.",".$BalancePositive.",".$ConditionID.",".$CurrencyID.",".$International.",".$Provider.",1,NOW()");
                    if($CUIT>0 && !CheckCUIT($CUIT))
                    {
                        echo "El CUIT ".$CUIT." no cumple la verificaci&oacute;n. Cliente ".$Name."<br><br>";
                    }
                    $MainBranch = "'Y'";
                    $BranchName = "'Central'";
                }else{
                    echo $CompanyID.': '.$Company['name'].'<br>';
                    echo 'Customer: '.$Company['customer'].'<br><br>';
                    $MainBranch = "'N'";
                    $BranchName = "'Adicional'";
                }
                
                //BRANCH
                $Address = "'".trim($Row[4])."'";
                $CP     = "'".trim($Row[5])." - ".trim($Row[6])." - ".trim($Row[10])."'";
                $ProvinceID = trim($Row[7])>0?trim($Row[7]):0;
                $ZoneID = 1;
                $RegionID = 1;
                $CountryID = 1;
                $Phone = trim($Row[8])?"'".trim($Row[8])."'":"''";
                $Email = "'".trim($Row[9])."'";
                
                if($Address!=$Company['branch_id'])
                    $BranchID = Core::Insert('company_branch','company_id,country_id,province_id,region_id,zone_id,name,address,postal_code,phone,email,main_branch,organization_id,creation_date',$CompanyID.",".$CountryID.",".$ProvinceID.",".$RegionID.",".$ZoneID.",".$BranchName.",".$Address.",".$CP.",".$Phone.",".$Email.",".$MainBranch.",1,NOW()");
                else
                    $BranchID = $Company['branch_id'];
                
                //AGENT
                if(trim($Row[11]))
                {
                    $Agent = "'".trim($Row[11])."'";
                    Core::Insert('company_agent','company_id,branch_id,name,organization_id,creation_date',$ID.",".$BranchID.",".$Agent.",1,NOW()");
                }
            }
        }else{
            $I++;
        }
    }
    fclose($File);
    

?>