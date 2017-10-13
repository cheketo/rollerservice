///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").on("click",function(e){
		e.preventDefault();
		if(validate.validateFields('new_company_form') && validateMaps())
		{
			alertify.confirm(utf8_decode('¿Desea modificar los datos de la empresa?'), function(e){
				if(e)
				{
					var process		= process_url+'?object=ConfigurationCompany';
					var haveData	= function(returningData)
					{
						$("input,select").blur();
						notifyError("Ha ocurrido un error durante el proceso de modificación.");
						console.log(returningData);
					}
					var noData		= function()
					{
						notifySuccess("Los datos han sido modificados correctamente.");
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


///////////////////////// UPLOAD IMAGE ////////////////////////////////////
$(function(){
	$("#image_upload").on("click",function(){
		$("#image").click();	
	});
	
	$("#image").change(function(){
		var process		= process_url+'?action=newimage&object=ConfigurationCompany';
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