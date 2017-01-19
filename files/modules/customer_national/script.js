$(document).ready(function(){
	
	if($("#cuit").length>0)
		$("#cuit").inputmask();  //static mask

	DeleteAgent();
	if($('.selectTags').length>0)
	{
		$('#iva_select').select2({placeholder: {id: '0',text: 'Seleccione IVA'}});
		$('#iva_select').on("select2:select", function (e) { $("#iva").val(e.params.data.id); });
		$('#iva_select').on("select2:unselect", function (e) { $("#iva").val(''); });
		
		$('.BrokerSelect').select2({placeholder: {id: '0',text: 'Seleccione un Corredor'}});
		
		select2Focus();
	}
});

function select2Focus()
{
	$('.select2').on(
        'select2:select',(
            function(){
                $(this).focus();
            }
        )
    );
}
///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate,#BtnCreateNext").on("click",function(e){
		e.preventDefault();
		if(validate.validateFields('new_company_form') && validateMaps())
		{
			var BtnID = $(this).attr("id")
			if(get['id']>0)
			{
				confirmText = "modificar";
				procText = "modificaci&oacute;n"
			}else{
				confirmText = "crear";
				procText = "creaci&oacute;n"
			}

			confirmText += " el proveedor '"+$("#name").val()+"'";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= '../../library/processes/proc.common.php?object=Provider';
					if(BtnID=="BtnCreate")
					{
						var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
					}else{
						var target		= 'new.php?element='+$('#title').val()+'&msg='+ $("#action").val();
					}
					var haveData	= function(returningData)
					{
						$("input,select").blur();
						notifyError("Ha ocurrido un error durante el proceso de "+procText+".");
						console.log(returningData);
					}
					var noData		= function()
					{
						document.location = target;
					}
					sumbitFields(process,haveData,noData);
				}
			});
		}
	});

	$("input").keypress(function(e){
		if(e.which==13){
			if($("#BtnCreate").is(":disabled"))
			{
				$("#agent_new").click();
			}else{
				$("#BtnCreate").click();
			}
		}
	});
});

///////////////////////// CREATE/EDIT FORM FUNCTIONS ////////////////////////////////////
$(function(){
	$("#agent_add").on("click",function(e){
		e.preventDefault();
		if(validate.validateFields('new_agent_form'))
		{
			var name = $('#agentname').val();
			var charge = $('#agentcharge').val();
			var email = $('#agentemail').val();
			var phone = $('#agentphone').val();
			var extra = $('#agentextra').val();
			if(!$("#total_agents").val() || $("#total_agents").val()=='undefined')
				$("#total_agents").val(0);
			var total = parseInt($("#total_agents").val())+1;
			
			
			$("#total_agents").val(total);
			var agent = $("#total_agents").val();
			if(charge)
			{
				chargehtml = '<br><span><i class="fa fa-briefcase"></i> '+charge+'</span>';
			}else{
				chargehtml = '';
			}
			if(phone)
			{
				phonehtml = '<br><span><i class="fa fa-phone"></i> '+phone+'</span>';
			}else{
				phonehtml = '';
			}
			if(email)
			{
				emailhtml = '<br><span><i class="fa fa-envelope"></i> '+email+'</span>';
			}else{
				emailhtml = '';
			}
			if(extra)
			{
				extrahtml = '<br><span><i class="fa fa-info-circle"></i> '+extra+'</span>';
			}else{
				extrahtml = '';
			}
			
			$("#agent_list").append('<div class="col-md-6 col-sm-6 col-xs-12 AgentCard"><div class="info-card-item"><input type="hidden" id="agent_name_'+agent+'" value="'+name+'" /><input type="hidden" id="agent_charge_'+agent+'" value="'+charge+'" /><input type="hidden" id="agent_email_'+agent+'" value="'+email+'" /><input type="hidden" id="agent_phone_'+agent+'" value="'+phone+'" /><input type="hidden" id="agent_extra_'+agent+'" value="'+extra+'" /><div class="close-btn DeleteAgent"><i class="fa fa-times"></i></div><span><i class="fa fa-user"></i> <b>'+name+'</b></span>'+chargehtml+phonehtml+emailhtml+extrahtml+'</div></div>');
			
			$('#agentname').val('');
			$('#agentcharge').val('');
			$('#agentemail').val('');
			$('#agentphone').val('');
			$('#agentextra').val('');
			$('#agent_form').addClass('Hidden');
			$('#BtnCreate').removeClass('disabled-btn');
			$('#BtnCreate').prop("disabled", false);
			$('#BtnCreateNext').removeClass('disabled-btn');
			$('#BtnCreateNext').prop("disabled", false);
			$("#empty_agent").remove();
			DeleteAgent();
		}
	});
	
	
});

function DeleteAgent()
{
	$(".DeleteAgent").on("click",function(event){
		event.preventDefault();
		$(this).parents(".AgentCard").remove();
	});
}

///////////////////////// UPLOAD IMAGE ////////////////////////////////////
$(function(){
	$("#image_upload").on("click",function(){
		$("#image").click();	
	});
	
	$("#image").change(function(){
		var process		= '../../library/processes/proc.common.php?action=newimage&object=Provider';
		var haveData	= function(returningData)
		{
			$('#newimage').val(returningData);
			$("#company_logo").attr("src",returningData);
			$('#company_logo').addClass('pulse').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		      $(this).removeClass('pulse');
		    });
		}
		var noData		= function(){console.log("No data");}
		sumbitFields(process,haveData,noData);
	});
});

$('#agent_new').on("click",function(){
    if ($('#agent_form').hasClass('Hidden')) {
      $('#agent_form').removeClass('Hidden');
      $('#BtnCreate').addClass('disabled-btn');
      $('#BtnCreate').attr('disabled', 'disabled');
      $('#BtnCreateNext').addClass('disabled-btn');
      $('#BtnCreateNext').attr('disabled', 'disabled');
    } else {
      $('#agent_form').addClass('Hidden');
      $('#BtnCreate').removeClass('disabled-btn');
      $('#BtnCreate').prop("disabled", false);
      $('#BtnCreateNext').removeClass('disabled-btn');
      $('#BtnCreateNext').prop("disabled", false);
    }
});

//////////////////////////// ADD BRANCH ///////////////////////////////////////
function addBranch2()
{
		var id = parseInt($("#total_branches").val())+1;
		$("#total_branches").val(id);
		var html = '';
		html += '<h4 class="subTitleB"><i class="fa fa-globe"></i> Geolocalizaci&oacute;n</h4>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>';
		html += '<input type="text" id="address_'+id+'" class="form-control" disabled="disabled" placeholder="Direcci&oacute;n" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo.">';
		html += '</span>';
		html += '</div>';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>';
		html += '<input type="text" id="postal_code_'+id+'" class="form-control" disabled="disabled" placeholder="C&oacute;digo Postal" validateMinLength="4///La direcci&oacute;n debe contener 4 caracteres como m&iacute;nimo.">';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-xs-12 col-sm-12">';
		html += InsertAutolocationMap(id);
		// html += '<iframe name="map'+id+'" id="map'+id+'" map="'+id+'" src="../../library/frames/frame.map.autolocation.php" framepadding="0" frameborder="0" style="width:100%;height:25em;overflow:expand;"></iframe>';
		// html += '<div id="map'+id+'_ErrorMsg" class="ErrorText Red Hidden">Seleccione una ubicaci&oacute;n</div>';
		// html += '<input type="hidden" id="map'+id+'_lat" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_lng" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_address" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_address_short" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_zone" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_zone_short" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_region" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_region_short" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_province" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_province_short" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_country" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_country_short" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_postal_code" branch="'+id+'">';
		// html += '<input type="hidden" id="map'+id+'_postal_code_suffix" branch="'+id+'">';
		
		html += '</div>';
		html += '</div>';
		html += '<br>';
		html += '<h4 class="subTitleB"><i class="fa fa-globe"></i> Datos de contacto</h4>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-sm-6 col-xs-12">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-envelope"></i></span>';
		html += '<input type="text" id="email_'+id+'" class="form-control" placeholder="Email">';
		html += '</span>';
		html += '</div>';
		html += '<div class="col-sm-6 col-xs-12">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-phone"></i></span>';
		html += '<input type="text" id="phone_'+id+'" class="form-control" placeholder="Tel&eacute;fono">';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-sm-6 col-xs-12">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-desktop"></i></span>';
		html += '<input type="text" id="website_'+id+'" class="form-control" placeholder="Sitio Web">';
		html += '</span>';
		html += '</div>';
		html += '<div class="col-sm-6 col-xs-12">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-fax"></i></span>';
		html += '<input type="text" id="fax_'+id+'" class="form-control" placeholder="Fax">';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<br>';
		html += '<div class="row">';
		html += '<div class="col-md-12 info-card">';
		html += '<h4 class="subTitleB"><i class="fa fa-male"></i> Representantes</h4>';
		html += '<div id="agent_list" class="row">';
		html += '</div>';
		html += '<div class="row txC">';
		html += '<button id="agent_new_1" branch="1" type="button" class="btn btn-warning Info-Card-Form-Btn AddAgent"><i class="fa fa-plus"></i> Agregar un representante</button>';
		html += '</div>';
		html += '<input type="hidden" id="total_agents_'+id+'" branch="'+id+'">';
		html += '<input type="hidden" id="branch_name_'+id+'" branch="'+id+'">'; /////// BRANCH NAME MUST BE A TEXT INPUT
		html += '<div id="agent_form_'+id+'" branch="1" class="Info-Card-Form Hidden">';
		html += '<form id="new_agent_form_'+id+'">';
		html += '<div class="info-card-arrow">';
		html += '<div class="arrow-up"></div>';
		html += '</div>';
		html += '<div class="info-card-form animated fadeIn">';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-user"></i></span>';
		html += '<input type="text" id="agentname_'+id+'" class="form-control" placeholder="Nombre y Apellido" validateEmpty="Ingrese un nombre">';
		html += '</span>';
		html += '</div>';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>';
		html += '<input type="text" id="agentcharge_'+id+'" class="form-control" placeholder="Cargo">';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-envelope"></i></span>';
		html += '<input type="text" id="agentemail_'+id+'" class="form-control" placeholder="Email" validateEmail="Ingrese un email v&aacute;lido.">';
		html += '</span>';
		html += '</div>';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-phone"></i></span>';
		html += '<input type="text" id="agentphone_'+id+'" class="form-control" placeholder="Tel&eacute;fono">';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row form-group inline-form-custom">';
		html += '<div class="col-xs-12 col-sm-12">';
		html += '<span class="input-group">';
		html += '<span class="input-group-addon"><i class="fa fa-info-circle"></i></span>';
		html += '<textarea id="agentextra_'+id+'" class="form-control" rows="1" placeholder="Informaci&oacute;n Extra"></textarea>';
		html += '</span>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row txC">';
		html += '<button id="agent_add_'+id+'" branch="'+id+'" type="button" class="Info-Card-Form-Done btn btnGreen"><i class="fa fa-check"></i> Agregar</button>';
		html += '<button id="agent_cancel_'+id+'" branch="'+id+'" type="button" class="Info-Card-Form-Done btn btnRed"><i class="fa fa-times"></i> Cancelar</button>';
		html += '</div>';
		html += '</div>';
		html += '</form>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '<br>';
		html += '<h4 class="subTitleB"><i class="fa fa-briefcase"></i> Corredores</h4>';
		html += '<div id="broker_list_'+id+'" branch="'+id+'" class="row">';
		html += '<div class="col-xs-12 col-sm-6">';
		html += ''; ///////<?php echo insertElement('select','select_broker_1','','form-control select2 selectTags BrokerSelect','',$DB->fetchAssoc('admin_user',"admin_id,CONCAT(first_name,' ',last_name) as name","status='A' AND profile_id = 361",'name'),'0','Seleccione una Opci&oacute;n'); ?>
		html += '<input type="hidden" id="brokers_'+id+'" branch="'+id+'">';
		html += '</div>';
		html += '<div class="col-xs-12 col-sm-6">';
		html += '<button id="add_broker" branch="'+id+'" style="margin:0px!important;" type="button" class="btn btn-success Info-Card-Form-Btn"><i class="fa fa-plus"></i> Agregar Corredor</button>';
		html += '</div>';
		html += '</div>';
		html += '<hr>';
		
		$("#branches_container").append(html);
}

function addBranch()
{
	var name = 'Sucursal';
	var img = '../../../skin/images/body/pictures/main_branch.png';
	//var img = '../../../skin/images/body/pictures/coal_power_plant.png';
	var html = '<div class="row branch_row listRow2" style="margin:0px!important;"><div class="col-lg-1 col-md-2 col-sm-3 flex-justify-center hideMobile990"><div class="listRowInner"><img class="img" style="margin-top:5px!important;" src="'+img+'" alt="Sucursal" title="Sucursal"></div></div><div class="col-lg-9 col-md-7 col-sm-5 flex-justify-center hideMobile990"><span class="listTextStrong" style="margin-top:15px!important;">'+name+'</span></div><div class="col-lg-1 col-md-2 col-sm-3 flex-justify-center"><button type="button" class="btn btnBlue"><i class="fa fa-pencil"></i></button>&nbsp;<button type="button" class="btn btnRed"><i class="fa fa-trash"></i></button></div></div>';
	$("#branches_container").append(html);
	
}

$("#add_branch").on("click",function(){
	addBranch();
});