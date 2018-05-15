<?php

class CompanyAgent
{
    use CoreCrud,CoreImage;
    
    const TABLE				= 'company_agent';
	const TABLE_ID			= 'agent_id';
	const SEARCH_TABLE		= 'company_agent';
	const DEFAULT_IMG		= '../../../../skin/images/agents/default/default.png';
	const DEFAULT_IMG2		= '../../../../skin/images/agents/default/default2.png';
	const DEFAULT_IMG_DIR	= '../../../../skin/images/agents/default/';
	const IMG_DIR			= '../../../../skin/images/agents/';
	
	public function __construct($ID=0)
	{
	    $this->ID = $ID;
		if($this->ID!=0)
		{
			$this -> GetData();
		}
	}
	
    public function Fillagents()
    {
        $ID = $_POST['id'];
        if($ID)
        {
            $Agents	= Core::Select(self::TABLE,'*',CompanyBranch::TABLE_ID.'='.$ID);
            $A=0;
            $AgentsHTML = '<div class="row" id="agents_row"><div class="col-xs-12"><h4 class="subTitleB"><i class="fa fa-male"></i> Contactos</h4></div>';
            foreach($Agents as $Agent)
            {
                $A++;
                $Charge = $Agent['charge']? '<br><span><i class="fa fa-briefcase"></i> '.$Agent['charge'].'</span>':'';
                $Email = $Agent['email']? '<br><span><i class="fa fa-envelope"></i> '.$Agent['email'].'</span>':'';
                $Phone = $Agent['phone']? '<br><span><i class="fa fa-phone"></i> '.$Agent['phone'].'</span>':'';
                $Extra = $Agent['extra']? '<br><span><i class="fa fa-info-circle"></i> '.$Agent['extra'].'</span>':'';
                $AgentsHTML .= '<div class="col-md-6 col-sm-6 col-xs-12 AgentCard" id="agent_card_'.$A.'">
                                    <div class="info-card-item">
                                        <input type="hidden" id="agent_id_'.$A.'_'.$ID.'" value="'.$Agent[CompanyAgent::TABLE_ID].'" />
                                        <input type="hidden" id="agent_name_'.$A.'_'.$ID.'" value="'.$Agent['name'].'" />
                                        <input type="hidden" id="agent_charge_'.$A.'_'.$ID.'" value="'.$Agent['charge'].'" />
                                        <input type="hidden" id="agent_email_'.$A.'_'.$ID.'" value="'.$Agent['email'].'" />
                                        <input type="hidden" id="agent_phone_'.$A.'_'.$ID.'" value="'.$Agent['phone'].'" />
                                        <input type="hidden" id="agent_extra_'.$A.'_'.$ID.'" value="'.$Agent['extra'].'" />
                                        <div class="close-btn DeleteAgent" agent="'.$A.'"><i class="fa fa-times"></i></div>
                                        <span><i class="fa fa-user"></i> <b>'.$Agent['name'].'</b></span>
                                        '.$Charge.$Phone.$Email.$Extra.'
                                        <div class="text-center">
                                            <button type="button" class="btn btn-sm btn-success SelectAgentBtn" id="select_'.$A.'" branch="'.$ID.'" agent="'.$A.'" ><i class="fa fa-check"></i> Seleccionar</button>
                                        </div>
                                    </div>
                                </div>';
            }
        }
        $AgentsHTML .= '</div>';
        
        $HTML = $AgentsHTML.
                '<div class="row txC">
                    <button id="agent_new_'.$ID.'" branch="'.$ID.'" type="button" class="btn btn-info Info-Card-Form-Btn agent_new"><i class="fa fa-plus"></i> Agregar un contacto</button>
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
                </div>';
        
	    echo $HTML;
    }
    
    public function Removeagent()
    {
        $this -> ID = $_POST['id'];
        $this -> FastDelete(self::TABLE);
    }
    
    public function Addagent()
    {
        $CompanyID = $_POST['company'];
        $BranchID = $_POST['branch'];
        $Name = $_POST['name'];
        $Charge = $_POST['charge'];
        $Phone = $_POST['phone'];
        $Email = $_POST['email'];
        $Extra = $_POST['extra'];
        
        echo Core::Insert(self::TABLE,Company::TABLE_ID.",".CompanyBranch::TABLE_ID.",name,charge,phone,email,extra,creation_date,created_by,organization_id",$CompanyID.",".$BranchID.",'".$Name."','".$Charge."','".$Phone."','".$Email."','".$Extra."',NOW(),".$_SESSION[CoreUser::TABLE_ID].",".$_SESSION[CoreOrganization::TABLE_ID]);
    }
    
    ////////// CREAR LA FUNCION PARA CREAR UN NUEVO AGENTE
    
}

?>