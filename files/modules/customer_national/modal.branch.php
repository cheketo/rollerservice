<?php 
    // function GetBranchModal($ID=1)
    // {
    //     $HTML .= '
    //     <!-- //// BEGIN BRANCH MODAL '.$ID.' //// -->
    //     <div id="branch_modal_'.$ID.'" class="modal fade" role="dialog">
    //         <div class="modal-dialog">
    //             <!-- Modal content-->
    //             <div class="modal-content">
    //                 <div class="modal-header">
    //                     <button type="button" class="close" data-dismiss="modal">&times;</button>
    //                     <h4 class="modal-title" id="BranchTitle'.$ID.'">Editar Sucursal</i></h4>
    //                 </div>
    //                 <div class="modal-body">
                    
    //                     <div class="row form-group inline-form-custom">
    //                         <div class="col-xs-12 col-sm-6">
    //                             Nombre de la Sucursal:
    //                         </div>
    //                         <div class="col-xs-12 col-sm-6">
    //                             '.insertElement('text','branch_name_'.$ID,'','form-control',' placeholder="Nombre" validateMinLength="3///El nombre debe contener 3 caracteres como m&iacute;nimo."').'
    //                         </div>
    //                     </div>
                        
    //                     <h4 class="subTitleB"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>-->
    //                     <div class="row form-group inline-form-custom">
    //                         <div class="col-xs-12 col-sm-6">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
    //                                 '.insertElement('text','address_'.$ID,'','form-control','disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
    //                             </span>
    //                         </div>
    //                         <div class="col-xs-12 col-sm-6">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
    //                                 '.insertElement('text','postal_code_'.$ID,'','form-control','disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo."').'
    //                             </span>
    //                         </div>
    //                     </div>
    //                     <div class="row form-group inline-form-custom">
    //                         <div class="col-xs-12 col-sm-12">
    //                             <!-- GOOGLE MAPS FRAME '.$ID.' -->
    //                             '.InsertAutolocationMap($ID).'
    //                         </div>
    //                     </div>
                        
    //                     <br>
    //                     <h4 class="subTitleB"><i class="fa fa-phone"></i> Datos de contacto</h4>
    //                     <div class="row form-group inline-form-custom">
    //                         <div class="col-sm-6 col-xs-12">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
    //                                 '.insertElement('text','email_'.$ID,'','form-control',' placeholder="Email"').'
    //                             </span>
    //                         </div>
    //                         <div class="col-sm-6 col-xs-12">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-phone"></i></span>
    //                                 '.insertElement('text','phone_'.$ID,'','form-control',' placeholder="Tel&eacute;fono"').'
    //                             </span>
    //                         </div>
    //                     </div>
    //                     <div class="row form-group inline-form-custom">
    //                         <div class="col-sm-6 col-xs-12">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-desktop"></i></span>
    //                                 '.insertElement('text','website_'.$ID,'','form-control',' placeholder="Sitio Web"').'
    //                             </span>
    //                         </div>
    //                         <div class="col-sm-6 col-xs-12">
    //                             <span class="input-group">
    //                                 <span class="input-group-addon"><i class="fa fa-fax"></i></span>
    //                                 '.insertElement('text','fax_'.$ID,'','form-control',' placeholder="Fax"').'
    //                             </span>
    //                         </div>
    //                     </div>
                        
    //                     <br>
    //                     <div class="row">
    //                         <div class="col-md-12 info-card">
    //                             <h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>
    //                             <span id="empty_agent_'.$ID.'" class="Info-Card-Empty info-card-empty">No hay representantes ingresados</span>
    //                             <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row"></div>
    //                             <div class="row txC">
    //                                 <button id="branch_agent_new_'.$ID.'" branch="'.$ID.'" type="button" class="btn btn-warning Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar un representante</button>
    //                             </div>
    //                             '.insertElement("hidden","branch_total_agents_".$ID,"0",'','branch="'.$ID.'"').'
    //                             '.insertElement("hidden","branch_name_".$ID,'central','','branch="'.$ID.'"').'
    //                             <div id="agent_form_'.$ID.'" class="Info-Card-Form Hidden">
    //                                 <form id="new_agent_form_'.$ID.'">
    //                                     <div class="info-card-arrow">
    //                                         <div class="arrow-up"></div>
    //                                     </div>
    //                                     <div class="info-card-form animated fadeIn">
    //                                         <div class="row form-group inline-form-custom">
    //                                             <div class="col-xs-12 col-sm-6">
    //                                                 <span class="input-group">
    //                                                     <span class="input-group-addon"><i class="fa fa-user"></i></span>
    //                                                     '.insertElement('text','agentname_1','','form-control',' placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre"').'
    //                                                 </span>
    //                                             </div>
    //                                             <div class="col-xs-12 col-sm-6">
    //                                                 <span class="input-group">
    //                                                     <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
    //                                                     '.insertElement('text','agentcharge_'.$ID,'','form-control',' placeholder="Cargo"').'
    //                                                 </span>
    //                                             </div>
    //                                         </div>
    //                                         <div class="row form-group inline-form-custom">
    //                                             <div class="col-xs-12 col-sm-6">
    //                                                 <span class="input-group">
    //                                                     <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
    //                                                     '.insertElement('text','agentemail_'.$ID,'','form-control',' placeholder="Email" validateEmail="Ingrese un email v&aacute;lido."').'
    //                                                 </span>
    //                                             </div>
    //                                             <div class="col-xs-12 col-sm-6">
    //                                                 <span class="input-group">
    //                                                     <span class="input-group-addon"><i class="fa fa-phone"></i></span>
    //                                                     '.insertElement('text','agentphone_'.$ID,'','form-control',' placeholder="Tel&eacute;fono"').'
    //                                                 </span>
    //                                             </div>
    //                                         </div>
    //                                         <div class="row form-group inline-form-custom">
    //                                             <div class="col-xs-12 col-sm-12">
    //                                                 <span class="input-group">
    //                                                     <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
    //                                                     '.insertElement('textarea','agentextra_'.$ID,'','form-control','rows="1" placeholder="Informaci&oacute;n Extra"').'
    //                                                 </span>
    //                                             </div>
    //                                         </div>
    //                                         <div class="row txC">
    //                                             <button id="agent_add_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnGreen"><i class="fa fa-check"></i> Agregar</button>
    //                                             <button id="agent_cancel_'.$ID.'" branch="'.$ID.'" type="button" class="Info-Card-Form-Done btn btnRed"><i class="fa fa-times"></i> Cancelar</button>
    //                                         </div>
    //                                     </div>
    //                                 </form>
    //                             </div>
    //                         </div>
    //                     </div>
                        
    //                     <br>
    //                     <h4 class="subTitleB"><i class="fa fa-briefcase"></i> Corredores</h4>
    //                     <div id="agent_list_'.$ID.'" branch="'.$ID.'" class="row">
    //                         <div class="col-xs-12 col-sm-6">
    //                             '.insertElement('select','select_broker_'.$ID,'','form-control select2 selectTags BrokerSelect','',$DB->fetchAssoc('admin_user',"admin_id,CONCAT(first_name,' ',last_name) as name","status='A' AND profile_id = 361",'name'),'0','Seleccione una Opci&oacute;n').'
    //                             '.insertElement('hidden','brokers_'.$ID,'').'
    //                         </div>
    //                         <div class="col-xs-12 col-sm-6">
    //                             <button id="add_broker_'.$ID.'" branch="'.$ID.'" style="margin:0px!important;" type="button" class="btn btn-success Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar Corredor</button>
    //                         </div>
    //                     </div>
                        
                        
    //                 </div>
    //             </div>
    //         </div>
    //     </div>
    //     <!-- //// END BRANCH MODAL '.$ID.' //// -->
    //     ';
    
    //     return $HTML;
    // }
?>