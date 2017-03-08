$(document).ready(function(){
	
	if($("#cuit").length>0)
		$("#cuit").inputmask();  //static mask

	DeleteAgent();
	CancelAgent();
	if($('.selectTags').length>0)
	{
		$('#iva_select').select2({placeholder: {id: '',text: 'Seleccione IVA'}});
		$('#iva_select').on("select2:select", function (e) { $("#iva").val(e.params.data.id); });
		$('#iva_select').on("select2:unselect", function (e) { $("#iva").val(''); });
		
		select2Broker();
		
		select2Focus();
	}
});

function select2Broker()
{
	$('.BrokerSelect').select2({
		tags: true
	});
	$('.BrokerSelect').on("change", function (e) { 
		var id = $(this).attr("branch");
		SelectBrokers(id);
	});
}

function SelectBrokers(id)
{
	var brokers = "0";
	$("#select_broker_"+id).next('span').children('span').children('span').children('ul').children('.select2-selection__choice').each(function(){
		var optionName = $(this).attr("title");
		$("#select_broker_"+id).children("option").each(function(){
			if($(this).html()==optionName)
			{
				brokers += ","+$(this).attr("value");
			}
		});
	});
	$("#brokers_"+id).val(brokers);
}
///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate,#BtnCreateNext").on("click",function(e){
		e.preventDefault();
		//alert(validate.validateFields('new_company_form')+' && '+validateMaps());
		ShowErrorMapDiv();
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

			confirmText += " el cliente '"+$("#name").val()+"'";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= '../../library/processes/proc.common.php?object=Customer';
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
	AddAgent();
});

function AddAgent()
{
	$(".agent_add").on("click",function(e){
		e.preventDefault();
		var id = $(this).attr("branch");
		if(validate.validateFields('new_agent_form_'+id))
		{
			var name = $('#agentname_'+id).val();
			var charge = $('#agentcharge_'+id).val();
			var email = $('#agentemail_'+id).val();
			var phone = $('#agentphone_'+id).val();
			var extra = $('#agentextra_'+id).val();
			if(!$("#branch_total_agents_"+id).val() || $("#branch_total_agents_"+id).val()=='undefined')
				$("#branch_total_agents_"+id).val(0);
			var total = parseInt($("#branch_total_agents_"+id).val())+1;
			
			
			$("#branch_total_agents_"+id).val(total);
			var agent = $("#branch_total_agents_"+id).val();
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
			
			$("#agent_list_"+id).append('<div class="col-md-6 col-sm-6 col-xs-12 AgentCard"><div class="info-card-item"><input type="hidden" id="agent_name_'+agent+'_'+id+'" value="'+name+'" /><input type="hidden" id="agent_charge_'+agent+'_'+id+'" value="'+charge+'" /><input type="hidden" id="agent_email_'+agent+'_'+id+'" value="'+email+'" /><input type="hidden" id="agent_phone_'+agent+'_'+id+'" value="'+phone+'" /><input type="hidden" id="agent_extra_'+agent+'_'+id+'" value="'+extra+'" /><div class="close-btn DeleteAgent"><i class="fa fa-times"></i></div><span><i class="fa fa-user"></i> <b>'+name+'</b></span>'+chargehtml+phonehtml+emailhtml+extrahtml+'</div></div>');
			
			$('#agentname_'+id).val('');
			$('#agentcharge_'+id).val('');
			$('#agentemail_'+id).val('');
			$('#agentphone_'+id).val('');
			$('#agentextra_'+id).val('');
			$('#agent_form_'+id).addClass('Hidden');
			$('#SaveBranchEdition'+id).removeClass('disabled-btn');
			$('#SaveBranchEdition'+id).prop("disabled", false);
			$("#empty_agent_"+id).remove();
			DeleteAgent();
		}
	});
}

function CancelAgent()
{
	$(".agent_cancel").on("click",function(e){
		e.preventDefault();
		var id = $(this).attr("branch");
		$('#agentname_'+id).val('');
		$('#agentcharge_'+id).val('');
		$('#agentemail_'+id).val('');
		$('#agentphone_'+id).val('');
		$('#agentextra_'+id).val('');
		$('#agent_form_'+id).addClass('Hidden');
		$('#SaveBranchEdition'+id).removeClass('disabled-btn');
		$('#SaveBranchEdition'+id).prop("disabled", false);
	});
}

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
	ShowAgentForm();
});

function ShowAgentForm()
{
	$('.agent_new').on("click",function(){
		var id = $(this).attr("branch");
	    if ($('#agent_form_'+id).hasClass('Hidden')) {
			$('#agent_form_'+id).removeClass('Hidden');
			$('#SaveBranchEdition'+id).addClass('disabled-btn');
			$('#SaveBranchEdition'+id).attr('disabled', true);
	    } else {
			$('#agent_form').addClass('Hidden');
			$('#SaveBranchEdition'+id).removeClass('disabled-btn');
			$('#SaveBranchEdition'+id).prop("disabled", false);
	    }
	});
}

///////////////////////////// BROKERS /////////////////////////////////////////

function AddBroker()
{
	$(".add_broker").on("click",function(){
		var id = $(this).attr("branch");
	});
}

function ShowErrorMapDiv()
{
	if(!validateMap(1))
	{
		$("#MapsErrorMessage").removeClass('Hidden');
	}else{
		$("#MapsErrorMessage").addClass('Hidden');
	}
}

//////////////////////////// ADD BRANCH ///////////////////////////////////////
function addBranchModal()
{
		
		var process		= '../../library/processes/proc.common.php?object=Customer&action=Getbranchmodal';
		var haveData	= function(returningData)
		{
			//console.log(returningData);
			$("#ModalBranchesContainer").append(returningData);
			$("#branch_modal_"+$("#total_branches").val()).show();
			EditBranch();
			select2Broker();
			CancelBranchEdition();
			SaveBranchEdition();
			validateMap();
			ShowAgentForm();
			AddAgent();
			CancelAgent();
			initMap($("#total_branches").val());
			validateDivChange();
		}
		var noData		= function()
		{
			console.log("no returning data");
		}
		sumbitFields(process,haveData,noData);
		
		
}

function addBranch()
{
	var id = parseInt($("#total_branches").val())+1;
	$("#total_branches").val(id);
	var name = 'Sucursal '+(id-1);
	var img = '../../../skin/images/body/pictures/main_branch.png';
	var html = '<div id="branch_row_'+id+'" class="row branch_row listRow2" style="margin:0px!important;"><div class="col-lg-1 col-md-2 col-sm-3 flex-justify-center hideMobile990"><div class="listRowInner"><img class="img" style="margin-top:5px!important;" src="'+img+'" alt="Sucursal" title="Sucursal"></div></div><div class="col-lg-9 col-md-7 col-sm-5 flex-justify-center"><span class="listTextStrong" style="margin-top:15px!important;" id="branch_row_name_'+id+'">'+name+'</span></div><div class="col-lg-1 col-md-2 col-sm-3 flex-justify-center"><button type="button" branch="'+id+'" id="EditBranch'+id+'" class="btn btnBlue EditBranch"><i class="fa fa-pencil"></i></button>&nbsp;<button type="button" id="DeleteBranch'+id+'" branch="'+id+'" class="btn btnRed DeleteBranch"><i class="fa fa-trash"></i></button></div></div>';
	$("#branches_container").append(html);
	addBranchModal();
	DeleteBranch();
}

$("#add_branch").on("click",function(){
	addBranch();
});

function EditBranch()
{
	$(".EditBranch").on("click",function(){
		var id = $(this).attr('branch');
		$("#branch_modal_"+id).show();
		// console.log($("#branch_modal_"+id).html());
	});
}

function SaveBranchEdition()
{
	$(".SaveBranchEdition").click(function(e){
		e.stopPropagation();
		var id = $(this).attr('branch');
		
		// alert(validate.validateFields('branch_form_'+id));
		// alert(validate.getLastValidation());
		
		if(validate.validateFields('branch_form_'+id) && validateMap(id))
		{
			$("#branch_modal_"+id).removeClass("NewBranch");
			$("#branch_modal_"+id).hide();
			$("#branch_row_name_"+id).html('Sucursal '+$("#branch_name_"+id).val());
			$("#BranchTitle"+id).html('Editar Sucursal '+$("#branch_name_"+id).val());
		}
		ShowErrorMapDiv();
		return false;
	});
	
	$(".branchname").on("keyup",function(){
		var name = $(this).val();	
		var id = $(this).attr('branch');
		$("#BranchTitle"+id).html('Editar Sucursal '+name);
	});
}

function DeleteBranch()
{
	$(".DeleteBranch").on("click",function(){
		var id = $(this).attr('branch');
		$("#branch_row_"+id).remove();
		$("#branch_modal_"+id).remove();
	});
}

function CancelBranchEdition()
{
	$(".CancelBranchEdition").on("click",function(){
		var id = $(this).attr('branch');
		if($("#branch_modal_"+id).hasClass("NewBranch"))
		{
			$("#DeleteBranch"+id).click();
			$("#total_branches").val(id-1);
		}else{
			$("#branch_modal_"+id).hide();
		}
	});
	return false;
}
$(document).ready(function(){
	EditBranch();
	SaveBranchEdition();
	CancelBranchEdition();
	$(".LoadedMap").click(function(){
		if(!$(this).hasClass('Initializated'))
		{
			var id =$(this).attr("branch");
			$(this).addClass('Initializated');
			initMap(id);
		}
		
	});
})