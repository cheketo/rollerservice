$(document).ready(function(){
	CloseEmailWindow();
	
	$("#BtnCreateAndEmail").click(function(){
		ShowEmailWindow();
	});
});

function ShowEmailWindow()
{
	if(validate.validateFields('QuotationForm'))
	{
		$("#EmailWindow").removeClass('Hidden');
		$("#CompanyName").html($('#company option:selected').html());
		GetReceiverEmail();
	}
}

function CloseEmailWindow()
{
	$(".CloseEmailWindow").click(function(){
		$("#receiver").val('');
		$("#EmailWindow").addClass('Hidden');	
	});
}

function GetReceiverEmail()
{
	var id = $("#agent").val();
	if(id=="undefined" || id<1) id=0;
	var string	= 'company='+$("#company").val()+'&agent='+ id +'&action=getcompanyemail&object=Company';
	$.ajax({
        type: "POST",
        url: process_url,
        data: string,
        cache: false,
        success: function(data){
            if(data)
            {
            	$("#receiver").val(data);
            }
        }
    });
}