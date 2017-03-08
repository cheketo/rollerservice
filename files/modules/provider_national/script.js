$(document).ready(function(){
	
	if($("#cuit").length>0)
		$("#cuit").inputmask();  //static mask
	
	
	
	DeleteAgent();
	if($('.selectTags').length>0)
	{
		// $('#province_select').select2({placeholder: {id: '0',text: 'Seleccione una Provincia'}});
		// $('#province_select').on("select2:select", function (e) {$("#province").val(e.params.data.id); fillZoneSelect();});
		// $('#province_select').on("select2:unselect", function (e) { $("#province").val(''); });
		
		// setZoneSelect2();
		
		$('#iva_select').select2({placeholder: {id: '0',text: 'Seleccione IVA'}});
		$('#iva_select').on("select2:select", function (e) { $("#iva").val(e.params.data.id); });
		$('#iva_select').on("select2:unselect", function (e) { $("#iva").val(''); });
		
		// $('#province').on("change", function (event) {event.preventDefault();  });
		select2Focus();
		
	}
});

// function setZoneSelect2()
// {
// 	$('#zone_select').select2({placeholder: {id: '0',text: 'Seleccione una Zona'}});
// 	$('#zone_select').on("select2:select", function (e) { $("#zone").val(e.params.data.id); });
// 	$('#zone_select').on("select2:unselect", function (e) { $("#zone").val(''); });
// }
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



// ///////////////////////// LOAD ZONE SELECT ////////////////////////////////
// function fillZoneSelect()
// {
// 	var province = $('#province').val();
// 	var process = '../../library/processes/proc.common.php';

// 	var string      = 'province='+ province +'&action=fillzones&object=GeolocationProvince';

//     var data;
//     $.ajax({
//         type: "POST",
//         url: process,
//         data: string,
//         cache: false,
//         success: function(data){
//             if(data)
//             {
//                 $('#zone-wrapper').html(data);
//             }else{
//                 $('#zone-wrapper').html('<span class="input-group-addon"><i class="fa fa-map-o"></i></span><select id="zone_select" class="form-control select2 selectTags" disabled="disabled" style="width: 100%;"></select>');
//             }
//             if($('#zone_select').length)
// 			{
// 				setZoneSelect2();
// 	            //$('#zone_select').on("change", function () { setZones(); });
// 	            $("#zone").val(0);
// 	            select2Focus();
// 			}
//         }
//     });
// }

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

$('#agent_new').click(function(){
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