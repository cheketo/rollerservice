<?php

class CompanyBranch
{
    use CoreCrud,CoreImage;
    
    const TABLE				= 'company_branch';
	const TABLE_ID			= 'branch_id';
	const DEFAULT_IMG		= '../../../../skin/images/branches/default/default.png';
	const DEFAULT_IMG2		= '../../../../skin/images/branches/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/branches/default/';
	const IMG_DIR			= '../../../../skin/images/branches/';
	
	public static function GetBranches($Branches)
	{
	    $TotalBranches = array();
		if(is_array($Branches))
		{
		    $X=0;
			foreach($Branches as $Branch)
			{
			    if($Branch['branch_id']!=$ID)
			    {
			        if(!$X)
			            $Branch['branch_logo'] = self::DEFAULT_IMG;
			        else
			            $Branch['branch_logo'] = self::DEFAULT_IMG2;
			            
			        $TotalBranches[] = $Branch;
			        $ID=$Branch['branch_id'];
			    }
			    $X++;
			}
		}
		return $TotalBranches;
	}
    
    public static function InsertBranchInfo($ID,$DeleteInfo=0)
	{
		
		// DELETE ALL BRANCHES
		if($DeleteInfo)
		{
			Core::Delete('company_agent',self::TABLE_ID."=".$ID);
			Core::Delete('relation_company_broker',self::TABLE_ID."=".$ID);
			Core::Delete(slf::TABLE,self::TABLE_ID."=".$ID);
		}
		
		// BRANCHES
		for($I=1;$I<=$_POST['total_branches'];$I++)
		{
			if($_POST['branch_name_'.$I])
			{
				$Branches[$I]['name']			= $_POST['branch_name_'.$I];
				$Branches[$I]['website']		= $_POST['website_'.$I];
				$Branches[$I]['fax']			= $_POST['fax_'.$I];
				$Branches[$I]['email']			= $_POST['email_'.$I];
				$Branches[$I]['phone']			= $_POST['phone_'.$I];
				
				if($I==1)
					$Branches[$I]['main_branch']			= 'Y';
				else
					$Branches[$I]['main_branch']			= 'N';
					
				// LOCATION DATA
				$Branches[$I]['lat']			= $_POST['map'.$I.'_lat'];
				$Branches[$I]['lng']			= $_POST['map'.$I.'_lng'];
				
				$Branches[$I]['address']		= $_POST['map'.$I.'_address_short'];
				if(!$Branches[$I]['address'])
					$Branches[$I]['address']	= $_POST['address_'.$I];
				$Branches[$I]['postal_code']	= $_POST['map'.$I.'_postal_code'];
				if(!$Branches[$I]['postal_code'])
					$Branches[$I]['postal_code']= $_POST['postal_code_'.$I];
				
				$Branches[$I]['zone_short']		= $_POST['map'.$I.'_zone_short'];
				$Branches[$I]['region_short']	= $_POST['map'.$I.'_region_short'];
				$Branches[$I]['province_short']	= $_POST['map'.$I.'_province_short'];
				$Branches[$I]['country_short']	= $_POST['map'.$I.'_country_short'];
				
				$Branches[$I]['zone']			= $_POST['map'.$I.'_zone'];
				$Branches[$I]['region'] 		= $_POST['map'.$I.'_region'];
				$Branches[$I]['province']		= $_POST['map'.$I.'_province'];
				$Branches[$I]['country']		= $_POST['map'.$I.'_country'];
			
				// INSERT NEW LOCATIONS
				$Branches[$I]['country_id']		= Geolocation::InsertCountry($Branches[$I]['country'],$Branches[$I]['country_short']);
				$Branches[$I]['province_id']	= Geolocation::InsertProvince($Branches[$I]['province'],$Branches[$I]['province_short']);
				$Branches[$I]['region_id']		= Geolocation::InsertRegion($Branches[$I]['region'],$Branches[$I]['region_short']);
				$Branches[$I]['zone_id']		= Geolocation::InsertZone($Branches[$I]['zone'],$Branches[$I]['zone_short']);
				
				$BranchID 		= Core::Insert("customer_branch",self::TABLE_ID.",country_id,province_id,region_id,zone_id,name,address,phone,email,website,fax,postal_code,main_branch,lat,lng,creation_date,created_by,".CoreOrganzaition::TABLE_ID,$ID.",".$Branches[$I]['country_id'].",".$Branches[$I]['province_id'].",".$Branches[$I]['region_id'].",".$Branches[$I]['zone_id'].",'".$Branches[$I]['name']."','".$Branches[$I]['address']."','".$Branches[$I]['phone']."','".$Branches[$I]['email']."','".$Branches[$I]['website']."','".$Branches[$I]['fax']."','".$Branches[$I]['postal_code']."','".$Branches[$I]['main_branch']."',".$Branches[$I]['lat'].",".$Branches[$I]['lng'].",NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganzaition::TABLE_ID]);
				//echo Core::LastQuery();
				
				// AGENTS DATA
				$Agents = array();
				for($A=1;$A<=$_POST['branch_total_agents_'.$I];$A++)
				{
					if($_POST['agent_name_'.$A.'_'.$I])
					{
						$AgentName		= ucfirst($_POST['agent_name_'.$A.'_'.$I]);
						$AgentCharge	= ucfirst($_POST['agent_charge_'.$A.'_'.$I]);
						$AgentEmail 	= $_POST['agent_email_'.$A.'_'.$I];
						$AgentPhone		= $_POST['agent_phone_'.$A.'_'.$I];
						$AgentExtra 	= $_POST['agent_extra_'.$A.'_'.$I];
						$Agents[]		= array('name'=>$AgentName,'charge'=>$AgentCharge,'email'=>$AgentEmail,'phone'=>$AgentPhone,'extra'=>$AgentExtra);
					}
				}
				
				// INSERT AGENTS
				$Fields="";
				foreach($Agents as $Agent)
				{
					if($Fields)
						$Fields .= "),(";
					$Fields .= $ID.",".$BranchID.",'".$Agent['name']."','".$Agent['charge']."','".$Agent['email']."','".$Agent['phone']."','".$Agent['extra']."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganzaition::TABLE_ID];
				}
				Core::Insert('company_agent',self::TABLE_ID.',branch_id,name,charge,email,phone,extra,creation_date,created_by,'.CoreOrganzaition::TABLE_ID,$Fields);
				
				// INSERT BROKERS
				$Brokers = explode(",",$_POST['brokers_'.$I]);
				$Fields="";
				foreach($Brokers as $Broker)
				{
					if($Broker>0)
					{
						if($Fields)
							$Fields .= "),(";
						$Fields .= $ID.",".$BranchID.",".$Broker.",".$_SESSION['organization_id'];
					}
				}
				if($Fields)
					Core::Insert('relation_company_broker',self::TABLE_ID.',branch_id,broker_id,organization_id',$Fields);
			}
		}
	}
	
	public function Getbranchmodal($ID=1,$Data=array())
    {
    	if($_POST['total_branches'])
    		$ID = $_POST['total_branches'];
    	if(empty($Data))
		{
			if($ID>1)
				$NewClass = "NewBranch";
			$BranchName = (intval($ID)-1);
			$Agents	= array();
		}else{
			$BranchName = $Data['name'];
			$Results	= Core::Select('relation_customer_broker','broker_id','branch_id='.$Data['branch_id']);
			foreach($Results as $Broker)
		    {
		      $Brokers .= $Brokers? ','.$Broker['broker_id'] : $Broker['broker_id']; 
		    }
		    $Agents	= Core::Select('customer_agent','*','branch_id='.$Data['branch_id']);
		    $A=0;
		    $AgentsHTML = "";
		    foreach($Agents as $Agent)
		    {
		    	$A++;
		    	$Charge = $Agent['charge']? '<br><span><i class="fa fa-briefcase"></i> '.$Agent['charge'].'</span>':'';
		    	$Email = $Agent['email']? '<br><span><i class="fa fa-envelope"></i> '.$Agent['email'].'</span>':'';
		    	$Phone = $Agent['phone']? '<br><span><i class="fa fa-phone"></i> '.$Agent['phone'].'</span>':'';
		    	$Extra = $Agent['extra']? '<br><span><i class="fa fa-info-circle"></i> '.$Agent['extra'].'</span>':'';
		    	$AgentsHTML .= '<div class="col-md-6 col-sm-6 col-xs-12 AgentCard"><div class="info-card-item"><input type="hidden" id="agent_name_'.$A.'_'.$ID.'" value="'.$Agent['name'].'" /><input type="hidden" id="agent_charge_'.$A.'_'.$ID.'" value="'.$Agent['charge'].'" /><input type="hidden" id="agent_email_'.$A.'_'.$ID.'" value="'.$Agent['email'].'" /><input type="hidden" id="agent_phone_'.$A.'_'.$ID.'" value="'.$Agent['phone'].'" /><input type="hidden" id="agent_extra_'.$A.'_'.$ID.'" value="'.$Agent['extra'].'" /><div class="close-btn DeleteAgent"><i class="fa fa-times"></i></div><span><i class="fa fa-user"></i> <b>'.$Agent['name'].'</b></span>'.$Charge.$Phone.$Email.$Extra.'</div></div>';
		    }
		    // if($A==0)
		    // {
		    // 	$NoAgents = '<span id="empty_agent_'.$ID.'" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>';
		    // }
		}
		
		if(!$A)
	    {
	    	$NoAgents = '<span id="empty_agent_'.$ID.'" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>';
	    }
        
    	
    	if($ID>1)
    	{
    		$BranchNameHTML = '<div class="row form-group inline-form-custom">
						<div class="col-xs-12 col-sm-4">
                            Nombre de la Sucursal:
                        </div>
                        <div class="col-xs-12 col-sm-8">
                            '.Core::InsertElement('text','branch_name_'.$ID,$BranchName,'form-control branchname','branch="'.$ID.'" placeholder="Nombre" validateEmpty="Ingrese un nombre de sucursal"').'
                        </div>
                        </div>';
    	}else{
    		$BranchName = 'Central';
    		$BranchNameHTML = Core::InsertElement('hidden','branch_name_'.$ID,$BranchName);
    	}
    	
    	if(Core::IsMobile())
    	{
    		$TopEm = 7;
    		$BodyEm = 24;
    	}else{
    		$TopEm = 11;
    		$BodyEm = 41;
    	}
    	
        $HTML .= '
        <!-- //// BEGIN BRANCH MODAL '.$ID.' //// -->
        <div id="branch_modal_'.$ID.'" class="modal fade '.$NewClass.'" role="dialog" style="opacity:1!important;">
            <div class="modal-dialog" style="top:'.$TopEm.'em;">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                        <h4 class="modal-title" id="BranchTitle'.$ID.'">Editar Sucursal '.$BranchName.'</i></h4>
                    </div>
                    <div class="modal-body" style="max-height:'.$BodyEm.'em;overflow-y:scroll;">
                    <form id="branch_form_'.$ID.'" name="branch_form_'.$ID.'">
                            '.$BranchNameHTML.'
                        <h4 class="subTitleB" style="margin-top:0px;"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>
                        <div class="row form-group inline-form-custom">
                            <div class="col-xs-12 col-sm-6">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                    '.Core::InsertElement('text','address_'.$ID,$Data['address'],'form-control','disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
                                </span>
                            </div>
                            <div class="col-xs-12 col-sm-6 margin-top1em">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                                    '.Core::InsertElement('text','postal_code_'.$ID,$Data['postal_code'],'form-control','disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
                                </span>
                            </div>
                        </div>
                        <div class="row form-group inline-form-custom">
                            <div class="col-xs-12 col-sm-12 MapWrapper">
                                '.Core::InsertAutolocationMap($ID,$Data).'
                            </div>
                        </div>
                        
                        <br>
                        <h4 class="subTitleB"><i class="fa fa-phone"></i> Datos de contacto</h4>
                        <div class="row form-group inline-form-custom">
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    '.Core::InsertElement('text','email_'.$ID,$Data['email'],'form-control',' placeholder="Email"').'
                                </span>
                            </div>
                            <div class="col-sm-6 col-xs-12 margin-top1em">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                    '.Core::InsertElement('text','phone_'.$ID,$Data['phone'],'form-control',' placeholder="Tel&eacute;fono"').'
                                </span>
                            </div>
                        </div>
                        <div class="row form-group inline-form-custom">
                            <div class="col-sm-6 col-xs-12">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
                                    '.Core::InsertElement('text','website_'.$ID,$Data['website'],'form-control',' placeholder="Sitio Web"').'
                                </span>
                            </div>
                            <div class="col-sm-6 col-xs-12 margin-top1em">
                                <span class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                                    '.Core::InsertElement('text','fax_'.$ID,$Data['fax'],'form-control',' placeholder="Fax"').'
                                </span>
                            </div>
                        </div>
                        </form>
                        <br>
                        <div class="row">
                            <div class="col-md-12 info-card">
                                <h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>
                                '.$NoAgents.'
                                <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row">
                                '.$AgentsHTML.'
                                </div>
                                <div class="row txC">
                                    <button id="agent_new_'.$ID.'" branch="'.$ID.'" type="button" class="btn btn-warning Info-Card-Form-Btn agent_new"><i class="fa fa-plus"></i> Agregar un representante</button>
                                </div>
                                '.Core::InsertElement("hidden","branch_total_agents_".$ID,count($Agents),'','branch="'.$ID.'"').'
                                <div id="agent_form_'.$ID.'" class="Info-Card-Form Hidden">
                                    <form id="new_agent_form_'.$ID.'">
                                        <div class="info-card-arrow">
                                            <div class="arrow-up"></div>
                                        </div>
                                        <div class="info-card-form animated fadeIn">
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                        '.Core::InsertElement('text','agentname_'.$ID,'','form-control',' placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre"').'
                                                    </span>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 margin-top1em">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
                                                        '.Core::InsertElement('text','agentcharge_'.$ID,'','form-control',' placeholder="Cargo"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-6">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                        '.Core::InsertElement('text','agentemail_'.$ID,'','form-control',' placeholder="Email" validateEmail="Ingrese un email v&aacute;lido."').'
                                                    </span>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 margin-top1em">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                        '.Core::InsertElement('text','agentphone_'.$ID,'','form-control',' placeholder="Tel&eacute;fono"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row form-group inline-form-custom">
                                                <div class="col-xs-12 col-sm-12">
                                                    <span class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
                                                        '.Core::InsertElement('textarea','agentextra_'.$ID,'','form-control','rows="1" placeholder="Informaci&oacute;n Extra"').'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row txC">
                                                <button id="agent_add_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnGreen agent_add"><i class="fa fa-check"></i> Agregar</button>
                                                <button id="agent_cancel_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnRed agent_cancel"><i class="fa fa-times"></i> Cancelar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <br>
                        <h4 class="subTitleB"><i class="fa fa-briefcase"></i> Corredores</h4>
                        <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row">
                            <div class="col-xs-12">
                                '.Core::InsertElement('multiple','brokers_'.$ID,$Brokers,'form-control chosenSelect BrokerSelect','data-placeholder="Seleccionar Corredores" branch="'.$ID.'"',Core::Select('core_user',"user_id,CONCAT(first_name,' ',last_name) as name","status='A' AND profile_id = 361",'name'),' ','').'
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer txC" style="background-color:#6f69bd!important;">
						<button type="button" name="button" class="btn btn-success btnBlue SaveBranchEdition" id="SaveBranchEdition'.$ID.'" data-dismiss="modal" branch="'.$ID.'">Guardar</button>
						<button type="button" name="button" class="btn btn-success btnRed CancelBranchEdition" id="CancelBranchEdition'.$ID.'" data-dismiss="modal" branch="'.$ID.'">Cancelar</button>
					</div>
                </div>
            </div>
        </div>
        <!-- //// END BRANCH MODAL '.$ID.' //// -->
        ';
    
        echo $HTML;
    }
}

?>