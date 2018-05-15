$(document).ready(function(){
	CloseAgentWindow();
	DisableAgentBtn();
	CompanyHasChanged();
	Unselect();
	$("#ShowAgentBtn").click(function(){
		ShowAgentWindow();
	});
	if($("#company").val())
		FillBranches();
});

function ShowAgentWindow()
{
	$("#AgentWindow").removeClass('Hidden');
	$("#AgentCompanyName").html($('#company option:selected').html());
}

function DisableAgentBtn()
{
	if($("#company").val())
	{
		$("#ShowAgentBtn").prop("disabled",false);
	}else{
		$("#ShowAgentBtn").prop("disabled",true);
	}
}

function CompanyHasChanged()
{
	$("#company").change(function(){
		DisableAgentBtn();
		HideAgentWrapper();
		UnselectAgent();
		FillBranches();
	});
}

function CloseAgentWindow()
{
	$(".CloseAgentWindow").click(function(){
		$("#AgentWindow").addClass('Hidden');	
		$(".agent_cancel").click();
	});
}

//////////////// BRANCHES ///////////////////
function FillBranches()
{
	var company = $('#company').val();
	if(company)
	{
		var process = process_url;
		var string      = 'id='+ company +'&action=fillbranches&object=Company';
	    var data;
	    $.ajax({
	        type: "POST",
	        url: process,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	                $('#BranchWrapper').html(data);
	            }else{
	                $('#BranchWrapper').html('<select id="agent_branch" class="form-control chosenSelect" disabled="disabled" ><option value="0">Sin Sucursales</option</select>');
	                
	            }
	            chosenSelect();
	            ShowAgentWrapper();
	            DeleteAgent();
	        }
	    });
	}
}

function ShowAgentWrapper()
{
	$("#agent_branch").change(function(){
		if($(this).val())
		{
			FillAgents();
		}
	})
}

function HideAgentWrapper()
{
	$('#AgentWrapper').addClass('Hidden');
}

/////////////// AGENTS ////////////////////////
function FillAgents()
{
	var branch = $('#agent_branch').val();
	if(branch)
	{
		var process = process_url;
		var string      = 'id='+ branch +'&action=fillagents&object=CompanyAgent';
	    var data;
	    $.ajax({
	        type: "POST",
	        url: process,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	            	$('#AgentWrapper').removeClass('Hidden');
	                $('#AgentWrapper').html(data);
	                DeleteAgent();
	                ShowNewAgentForm();
	                HideNewAgentForm();
	                CreateNewAgent();
	                SelectAgent();
	            }else{
	                $('#AgentWrapper').html('<span class="text-center">Sin Contacto</span>');
	                
	            }
	        }
	    });
	}
	return false;
}


////////// DELETE

function DeleteAgent()
{
	$(".DeleteAgent").click(function(e){
		e.preventDefault();
		e.stopPropagation();
		var branch	= $("#agent_branch").val();
		var card	= $(this).attr("agent");
		var name	= $("#agent_name_"+card+"_"+branch).val();
		var id		= $("#agent_id_"+card+"_"+branch).val();
		alertify.confirm(utf8_decode('¿Desea eliminar el contacto <strong>'+name+'</strong>?'), function(e){
			if(e)
			{
				RemoveAgent(id,card);
				return false;
			}
		});
		return false;
	});
}

function RemoveAgent(id,card)
{
	var process = process_url;
	var string      = 'id='+ id +'&action=removeagent&object=CompanyAgent';
    var data;
    $.ajax({
        type: "POST",
        url: process,
        data: string,
        cache: false,
        success: function(data){
            if(data)
            {
                console.log(data);
                return false;
            }else{
            	$("#agent_card_"+card).remove();
            	if($("#agent").val() && $("#agent").val()==id)
                {
                	UnselectAgent();
                }
                return false;
            }
        }
    });
    return false;
}

/////////// CREATE

function ShowNewAgentForm()
{
	$(".agent_new").click(function(e){
		e.stopPropagation();
		var branch = $(this).attr('branch');
		$("#agent_form_"+branch).removeClass('Hidden');
		return false;
	});
}

function HideNewAgentForm()
{
	$(".agent_cancel").click(function(e){
		e.stopPropagation();
		var branch = $(this).attr('branch');
		$("#agent_form_"+branch).addClass('Hidden');
	});
}

function CreateNewAgent()
{
	$(".agent_add").click(function(e){
		e.stopPropagation();
		var branch = $(this).attr('branch');
		if(validate.validateFields("agent_form_"+branch))
		{
			var company = $("#company").val();
			var name = $('#agentname_'+branch).val();
			var charge = $('#agentcharge_'+branch).val();
			var email = $('#agentemail_'+branch).val();
			var phone = $('#agentphone_'+branch).val();
			var extra = $('#agentextra_'+branch).val();
			
			var process = process_url;
			var string  = 'branch='+ branch + '&company=' + company + '&name=' + name + '&charge=' + charge + '&email=' + email + '&phone=' + phone + '&extra=' + extra + '&action=addagent&object=CompanyAgent';
		    var data;
		    $.ajax({
		        type: "POST",
		        url: process,
		        data: string,
		        cache: false,
		        success: function(data){
		            if(data)
		            {
						if(isNaN(data))
						{
							notifyError("Ha ocurrido un error al intentar crear el contacto.");
							console.log(data);
							return false;	
						}else{
							var ID = branch;
							var A = parseInt($("#branch_total_agents_"+ID).val())+1;
							var chargeHTML ='';
							var emailHTML ='';
							var phoneHTML ='';
							var extraHTML ='';
							if(charge && charge!=undefined)
								chargeHTML = '<br><span><i class="fa fa-briefcase"></i> '+charge+'</span>';
							if(email && email!=undefined)
								emailHTML = '<br><span><i class="fa fa-envelope"></i> '+email+'</span>';
							if(phone && phone!=undefined)
								phoneHTML = '<br><span><i class="fa fa-phone"></i> '+phone+'</span>';
							if(extra && extra!=undefined)
								extraHTML = '<br><span><i class="fa fa-info-circle"></i> '+extra+'</span>';
							var HTML = '<div class="col-md-6 col-sm-6 col-xs-12 AgentCard" id="agent_card_'+A+'">'+
                                '<div class="info-card-item">'+
                                    '<input type="hidden" id="agent_id_'+A+'_'+ID+'" value="'+data+'" />'+
                                    '<input type="hidden" id="agent_name_'+A+'_'+ID+'" value="'+name+'" />'+
                                    '<input type="hidden" id="agent_charge_'+A+'_'+ID+'" value="'+charge+'" />'+
                                    '<input type="hidden" id="agent_email_'+A+'_'+ID+'" value="'+email+'" />'+
                                    '<input type="hidden" id="agent_phone_'+A+'_'+ID+'" value="'+phone+'" />'+
                                    '<input type="hidden" id="agent_extra_'+A+'_'+ID+'" value="'+extra+'" />'+
                                    '<div class="close-btn DeleteAgent" agent="'+A+'"><i class="fa fa-times"></i></div>'+
                                    '<span><i class="fa fa-user"></i> <b>'+name+'</b></span>'+
                                    chargeHTML+phoneHTML+emailHTML+extraHTML+
                                    '<div class="text-center">'+
                                        '<button type="button" class="btn btn-sm btn-success SelectAgentBtn" id="select_'+A+'" branch="'+ID+'" agent="'+A+'" ><i class="fa fa-check"></i> Seleccionar</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                            $("#branch_total_agents_"+ID).val(A);
                            $("#agents_row").append(HTML);
                            DeleteAgent();
                            SelectAgent();
                        	$(".agent_cancel").click();
                            $('#agentname_'+branch).val('');
							$('#agentcharge_'+branch).val('');
							$('#agentemail_'+branch).val('');
							$('#agentphone_'+branch).val('');
							$('#agentextra_'+branch).val('');
						}
					}else{
						notifyError("Ha ocurrido un error al intentar crear el contacto.");
						console.log("No data returned.");
						return false;
		            }
		        }
		    });
		}
	});
}

function SelectAgent()
{
	$(".SelectAgentBtn").click(function(e){
		e.stopPropagation();
		var branch = $(this).attr("branch");
		var agent = $(this).attr("agent");
		$("#agent").val($("#agent_id_"+agent+"_"+branch).val());
		$(".CloseAgentWindow").click();
		$("#ShowAgentBtn").html('<i class="fa fa-male"></i> '+$("#agent_name_"+agent+"_"+branch).val());
		$("#ShowAgentBtn").removeClass("btn-warning");
		$("#ShowAgentBtn").addClass("btn-success");
		return false;
	});
}

function UnselectAgent()
{
	$("#agent").val('');
	$("#ShowAgentBtn").html('<i class="fa fa-times"></i> Sin Contacto');
	$("#ShowAgentBtn").removeClass("btn-success");
	$("#ShowAgentBtn").addClass("btn-warning");
}

function Unselect()
{
	$("#Unselect").click(function(e){
		e.stopPropagation();
		UnselectAgent();
		$(".CloseAgentWindow").click();
	})
}