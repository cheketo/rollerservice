///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").on("click",function(e){
		if(validate.validateFields('*'))
		{
            alertify.confirm(utf8_decode('¿Desea crear el registro ?'), function(e)
            {
				if(e)
				{
					var process		= '../../library/processes/proc.common.php?object=Config&action=create';
					var target		= 'www.google.com';
					
					var haveData	= function(returningData)
					{
						alert(returningData+' OK');
						document.location = target;
					}
					var noData		= function()
					{
						$("input,select").blur();
						notifyError("Ha ocurrido un error durante el proceso.");
						console.log(returningData);
					}
					sumbitFields(process,haveData,noData);
				}
			});
		}
	});

	$("input").keypress(function(e){
		if(e.which==13){
	        $("#BtnCreate").click();
		}
	});
});
