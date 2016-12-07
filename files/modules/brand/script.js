$(document).ready(function(){
	if($('.selectTags').length>0)
	{
		$('.selectTags').select2({placeholder: {id: '0',text: 'Seleccione un País'},allowClear: true});
		$('.selectTags').on("select2:select", function (e) { $("#country").val(e.params.data.id); });
		$('.selectTags').on("select2:unselect", function (e) { $("#country").val(''); });
	}
});
///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate,#BtnCreateNext").click(function(){
		if(validate.validateFields(''))
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

			confirmText += " la marca '"+$("#name").val()+"'";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= '../../library/processes/proc.common.php?object=Brand';
					if(BtnID=="BtnCreate")
					{
						var target		= 'list.php?element='+$('#name').val()+'&msg='+ $("#action").val();
					}else{
						var target		= 'new.php?element='+$('#name').val()+'&msg='+ $("#action").val();
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
			$("#BtnCreate").click();
		}
	});
});