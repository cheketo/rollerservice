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
        if($I>1)
        {
            $Row = explode(";",$Line);
            
            if($Row[0]>0)
            {
                //PROVIDER
                $OldID = trim($Row[0]);
                $Name = trim($Row[2])? "'".strtoupper(trim($Row[1])." ".trim($Row[2]))."'":"'".strtoupper(trim($Row[1]))."'";
                $CUIT = trim($Row[3])? str_replace("-","",trim($Row[3])):0;
                $IIBB = trim($Row[13])?"'".strtoupper(trim($Row[13]))."'":"''";
                $Balance = intval(trim($Row[14]))?intval(trim($Row[14]))/100:0;
                $BalanceInitial=intval(trim($Row[15]))?intval(trim($Row[15]))/100:0;
                $BalancePositive=intval(trim($Row[16]))?intval(trim($Row[16]))/100:0;
                $ConditionID = trim($Row[18])>0?trim($Row[18]):0;
                $CurrencyID = $Row[19]==2?1:2;
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
                if($CUIT>0)
                {
                    $Company = Core::Select("company","name,customer,company_id,cuit","cuit=".$CUIT."")[0];
                }else{
                    $Company = Core::Select("company","name,customer,company_id,cuit","name=".$Name."")[0];
                }
                $CompanyID = $Company['company_id'];
                if(!$CompanyID)
                {
                    $CompanyID = Core::Insert('company','name,cuit,type_id,iva_id,iibb,balance,balance_initial,balance_positive,purchase_condition_id,currency_id,international,provider,old_id,organization_id,creation_date',$Name.",".$CUIT.",".$TypeID.",".$IvaID.",".$IIBB.",".$Balance.",".$BalanceInitial.",".$BalancePositive.",".$ConditionID.",".$CurrencyID.",".$International.",".$Provider.",".$OldID.",1,NOW()");
                    if($CUIT>0 && !CheckCUIT($CUIT))
                    {
                        echo "El CUIT ".$CUIT." no cumple la verificaci&oacute;n. Cliente ".$Name."<br><br>";
                    }
                    $MainBranch = "'Y'";
                    $BranchName = "'Central'";
                }else{
                    if($Company['customer']=='Y')
                    {
                        echo 'Cliente y Proveedor<br><br>';
                        echo $CompanyID.': '.$Company['name'].'<br>';
                        echo 'Customer: '.$Company['customer'].'<br><br>';
                        $IDS .= $IDS? ",".$CompanyID:$CompanyID;
                    }
                    $MainBranch = "'N'";
                    $BranchName = "'Adicional'";
                    $Branch = Core::Select("company_branch","branch_id,name,address","company_id=".$CompanyID)[0];
                }
                
                //BRANCH
                $Address = "'".trim($Row[4])."'";
                $CP     = "'".trim($Row[5])." - ".trim($Row[6])." - ".trim($Row[10])."'";
                $ProvinceID = trim($Row[7])>0? trim($Row[7]):0;
                $ZoneID = 1;
                $RegionID = 1;
                $CountryID = 1;
                $Phone = trim($Row[8])?"'".trim($Row[8])."'":"''";
                $Email = "'".trim($Row[9])."'";
                
                if($Address!=$Branch['address'])
                    $BranchID = Core::Insert('company_branch','company_id,country_id,province_id,region_id,zone_id,name,address,postal_code,phone,email,main_branch,organization_id,creation_date',$CompanyID.",".$CountryID.",".$ProvinceID.",".$RegionID.",".$ZoneID.",".$BranchName.",".$Address.",".$CP.",".$Phone.",".$Email.",".$MainBranch.",1,NOW()");
                else{
                    $BranchID = $Company['branch_id'];
                    $Agent = Core::Select('company_agent','name',"branch_id=".$BranchID)[0];
                }
                
                //AGENT
                if(trim($Row[11]) && trim($Row[11])!=$Agent['name'])
                {
                    $AgentName = "'".trim($Row[11])."'";
                    Core::Insert('company_agent','company_id,branch_id,name,organization_id,creation_date',$CompanyID.",".$BranchID.",".$AgentName.",1,NOW()");
                }
            }
        }else{
            $I++;
        }
    }
    echo Core::Update('company',"provider='Y'","company_id IN (".$IDS.")");
    fclose($File);
    

?>