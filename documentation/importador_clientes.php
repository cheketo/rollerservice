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
    
    $File = fopen("importacion_clientes.csv", "r");
    while(!feof($File))
    {
        $Line = addslashes(str_replace("?","Ñ",fgets($File)));
        if($I==1)
        {    
            $Row = explode(",",$Line);
            if($Row[0]>0)
            {
                //CUSTOMER
                $ID = trim($Row[0]);
                $Name = trim($Row[2])? "'".strtoupper(trim($Row[1])." ".trim($Row[2]))."'":"'".strtoupper(trim($Row[1]))."'";
                $CUIT = trim($Row[3])?str_replace("-","",trim($Row[3])):0;
                $IIBB = trim($Row[10])?"'".strtoupper(trim($Row[10]))."'":"''";
                $Balance = intval(trim($Row[11]))?intval(trim($Row[11]))/100:0;
                $BalanceInitial=intval(trim($Row[12]))?intval(trim($Row[12]))/100:0;
                $BalancePositive=intval(trim($Row[13]))?intval(trim($Row[13]))/100:0;
                $ConditionID = trim($Row[17])>0?trim($Row[17]):0;
                $ProviderNumber=trim($Row[19])>0?"'".trim($Row[19])."'":0;;
                $Reputation = strtoupper(trim($Row[20]))=='S'?-1:0;
                $CredLimit = trim($Row[21])>0?trim($Row[21]):0;
                $International = $ProvinceID==25?"'Y'":"'N'";
                $Customer = "'Y'";
                
                switch (trim($Row[9])) {
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
                    default:
                        $IvaID=1;
                    break;
                }
                $TypeID = trim($Row[22])=="R"?1:2;
                
                $CompanyID = Core::Select("company","company_id","name=".$Name)[0]['company_id'];
                
                if(!$CompanyID)
                {
                    Core::Insert('company','company_id,name,cuit,type_id,iva_id,iibb,balance,balance_initial,balance_positive,purchase_condition_id,provider_number,reputation,credit_limit,international,customer,organization_id,creation_date',$ID.",".$Name.",".$CUIT.",".$TypeID.",".$IvaID.",".$IIBB.",".$Balance.",".$BalanceInitial.",".$BalancePositive.",".$ConditionID.",".$ProviderNumber.",".$Reputation.",".$CredLimit.",".$International.",".$Customer.",1,NOW()");
                    if($CUIT>0 && !CheckCUIT($CUIT))
                    {
                        echo "El CUIT ".$CUIT." no cumple la verificaci&oacute;n. Cliente ".$Name."<br><br>";
                    }
                    $MainBranch = "Y";
                    $BranchName = "'Central'";
                }else{
                    $MainBranch = "N";
                    $BranchName = "'Adicional'";
                }
                
                //BRANCH
                $Address = "'".trim($Row[4])."'";
                $CP     = "'".trim($Row[6])." ".trim($Row[5])."'";
                $ProvinceID = trim($Row[7])>0?trim($Row[7]):0;
                $ZoneID = 1;
                $RegionID = 1;
                $CountryID = 1;
                $Phone = trim($Row[8])?"'".trim($Row[8])."'":"''";
                $Email = "'".trim($Row[26])."'";
                $Web="'".trim($Row[27])."'";
                $MainBranch = $MainBranch=="N"? "'N'":"'Y'";
                $BranchID = Core::Insert('company_branch','company_id,country_id,province_id,region_id,zone_id,name,address,postal_code,phone,email,website,main_branch,organization_id,creation_date',$ID.",".$CountryID.",".$ProvinceID.",".$RegionID.",".$ZoneID.",".$BranchName.",".$Address.",".$CP.",".$Phone.",".$Email.",".$Web.",".$MainBranch.",1,NOW()");
                
                //AGENT
                if(trim($Row[23]))
                {
                    $Agent = "'".trim($Row[23])."'";
                    $AgentDesc = "'".trim($Row[25]).trim($Row[24])."'";
                    Core::Insert('company_agent','company_id,branch_id,name,extra,organization_id,creation_date',$ID.",".$BranchID.",".$Agent.",".$AgentDesc.",1,NOW()");
                }
                
                //BROKER
                $BrokerID = trim($Row[16])==1 || trim($Row[16])==2 || trim($Row[16])==3 || trim($Row[16])==13?trim($Row[16]):0;
                if($BrokerID>0)
                {
                    $PercentageCommission = trim($Row[30])>0?trim($Row[30])/100:0;
                    Core::Insert('relation_company_broker','company_id,branch_id,broker_id,percentage_commission,organization_id',$ID.",".$BranchID.",".$BrokerID.",".$PercentageCommission.",1");
                }
            }
        }else{
            $I=1;
        }
    }
    fclose($File);
    

?>