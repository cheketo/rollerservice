<div class="window Hidden" id="AgentWindow">
    <div class="window-border"><h4><div class="pull-left"><i class="fa fa-user-group"></i> Seleccionar contacto de <span id="AgentCompanyName" class="font-weight-bold"></span></div><div class="pull-right"><div id="AgentWindowClose" class="CloseAgentWindow text-red"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body">
        <h4 class="subTitleB"><i class="fa fa-building"></i> Seleccionar sucursal</h4>
        <div id="BranchWrapper">
            <?php //echo Core::InsertElement('select','agent_branch','','form-control chosenSelect','data-placeholder="Seleccione una sucursal"',Core::Select(CompanyBranch::TABLE,CompanyBranch::TABLE_ID.','.$CompanyName,$Field."= 'Y' ".$FieldInternational." AND status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID],'name'),' ',''); ?>
        </div>
        <div id="AgentWrapper" class="info-card">
            
        </div>
    </div>
    <div class="window-border txC">
        <!--<button type="button" class="btn btn-primary btnBlue" id="SaveAndSend"><i class="fa fa-send"></i> Enviar</button>-->
        <button class="btn btn-primary CloseAgentWindow" id="CancelAgent"><i class="fa fa-arrow-left"></i> Regresar</button>
        <button class="btn btn-danger" id="Unselect"><i class="fa fa-times"></i> Dejar Sin Contacto</button>
        
    </div>
  </div>