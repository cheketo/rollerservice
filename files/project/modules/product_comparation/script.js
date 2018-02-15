// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insertrelation')
		notifySuccess('La relaci&oacute;n del c&oacute;digo <b>'+get['element']+'</b> ha sido creada correctamente.');
	if(get['msg']=='updaterelation')
		notifySuccess('La relaci&oacute;n del c&oacute;digo <b>'+get['element']+'</b> ha sido modificada correctamente.');
	if(get['msg']=='relation')
		notifySuccess('Los productos importados de <b>'+get['element']+'</b> han sido asociados correctamente.');
});

$(function(){
	///////////////////////// CREATE/EDIT ////////////////////////////////////
	$("#BtnRelation").click(function(){
		if($("#relation").val()>0)
		{
			var target		= 'list.php?element='+$('#code').val()+'&msg=updaterelation';
			askAndSubmit(target,'ProductRelation','¿Desea crear una relaci&oacute;n con el c&oacute;digo <b>'+$('#code').val()+'</b>?');	
		}else{
			var target		= 'list.php?element='+$('#code').val()+'&msg=insertrelation';
			askAndSubmit(target,'ProductRelation','¿Desea crear una relaci&oacute;n con el c&oacute;digo <b>'+$('#code').val()+'</b>?');	
		}
		
	});
	// $("#BtnCreateNext").click(function(){
	// 	var target		= 'new.php?element='+$('#code').val()+'&msg='+ $("#action").val();
	// 	askAndSubmit(target,'Product','¿Desea crear el producto <b>'+$('#code').val()+'</b>?');
	// });
	// $("#BtnEdit").click(function(){
	// 	var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
	// 	askAndSubmit(target,'Product','¿Desea modificar el producto <b>'+$('#code').val()+'</b>?');
	// });
	$("#BtnPriceList").click(function(){
		var target		= 'list.php?element='+$('#TextAutoCompletecompany_id').val()+'&msg='+ $("#action").val()+'&company_id='+$("#company_id").val();
		askAndSubmit(target,'ProductRelationItem','¿Desea cargar y/o modificar todos los c&oacute;digos,precios y stocks relacionados a la empresa <b>'+$('#TextAutoCompletecompany_id').val()+'</b>? Los c&oacute;digos vac&iacute;os o los que no tengan una marca asociada ser&aacute;n descartados.','Ha ocurrido un error al intentar generar la lista de precios.','PriceList');
	});
	$("#BtnImport").click(function(){
		var target		= 'import_selection.php?id='+$('#id').val()+'&element='+$('#TextAutoCompleteid').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductRelationItem','¿Desea importar el archivo <b>'+$('#Fileprice_list').val()+'</b>?','Ha ocurrido un error durante la importaci&oacute;n. Revise el documento que está importando y aseg&uacute;rese que la columna "C&oacute;digo" se encuentre completa en todas las filas.');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnRelation").click();
		}
	});
	///////////////////////// CHECK PREVIOUS IMPORT FOR COMPANY ////////////////////////////////////
	$('#id').change(function()
	{
		if($(this).val())
		{
			var oldAction = $("#action").val();
			$("#action").val('Checkimport');
			var haveData = function(data)
			{
				if(!isNaN(data))
				{
					console.log(data);
					alertify.confirm("Una importaci&oacute; previa de un listado de precios para esta empresa se encuenta sin finalizar ¿Desea retomar la importación previa?", function(e){
						if(e)
						{
							document.location = "import_selection.php?id="+$('#id').val();
						}else{
							$("#action").val('Updateimportstatus');
							var UpdtImportStatus = function(data)
							{
								console.log(data);
								notifyError("Se ha producido un error al modificar el estado de la importaci&oacute;n previa.");
							}
							sumbitFields(process_url+'?object=ProductRelationItem',UpdtImportStatus,function(){});
							$("#action").val(oldAction);
						}
					});
				}else{
					notifyError("Se ha producido un error al consultar si existen importaciones previas.");
					console.log(data);
					$("#action").val(oldAction);
				}
			}
			sumbitFields(process_url+'?object=ProductRelationItem',haveData,function(){$("#action").val(oldAction);});
			$("#action").val(oldAction);
			if($('#date').val())
			{
				$('#date').change();
				toggleLoader();
			}
		}
	});
	
	///////////////////////// CHECKS IF THE DATE IS THE NEWEST ONE ////////////////////////////////////
	var lastDateVal;
	$('#date').change(function(e)
	{
		if(($("#date").val()+$("#id").val())!=lastDateVal)
		{
			lastDateVal = $("#date").val()+$("#id").val();
			if($('#date').val() && $('#id').val())
			{
				var oldAction = $("#action").val();
				$("#action").val('Checkimportdate');
				var haveData = function(data)
				{
					if(!isNaN(data))
					{
						notifyInfo("Existe una importaci&oacute;n de listado de precios m&aacute;s reciente para esta empresa. Se cargar&aacute;n o actualizar&aacute;n &uacute;nicamente aquellos art&iacute;culos que tengan una fecha de actualizaci&oacute;n anterior a la seleccionada.");
					}else{
						notifyError("Se ha producido un error al consultar si existen importaciones con fechas m&aacute;s recientes.");
						console.log(data);
					}
				}
				sumbitFields(process_url+'?object=ProductRelationItem',haveData,function(){});
				$("#action").val(oldAction);
			}
		}
	});
	
});




///////////// DATATABLE EXAMPLE////////
$(document).ready(function() {
	// if($('#table_import').length>0)
	// {
	//     var table = $('#table_import').DataTable({
	//     	"language": {
	//             "lengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
	//             "zeroRecords": "Sin resultados",
	//             "info": "Mostrando _PAGE_ de _PAGES_",
	//             "infoEmpty": "No se encontraron registros",
	//             "infoFiltered": "(filtered from _MAX_ total records)"
	//         }
	//     });
	// }
    // $('button').click( function() {
    //     var data = table.$('input, select').serialize();
    //     alert(
    //         "The following data would have been submitted to the server: \n\n"+
    //         data.substr( 0, 120 )+'...'
    //     );
    //     return false;
    // } );
});

function AdditionalSearchFunctions()
{
	SaveRowChanges();
}

function SaveRowChanges()
{
	$(".SaveRowChanges").on("click",function(){
		var ID = $(this).attr("item");
		var form = $("#form_"+ID).attr("id");
		if(validate.validateFields(form))
		{
			// var price = $("#price_"+ID).val();
			// var strock = $("#stock_"+ID).val();
			var code = $("#code_"+ID).val();
			// var brand = $("#brand_"+ID).val();
			// var abstract = $("#abstract_"+ID).val();
			
			
			var process	= process_url+'?action=Updateimportitem&object=ProductRelationItem&itemid='+ID;
			var haveData	= function(returningData)
			{
				notifyError("Hubo un error al querer guardar los datos");
				console.log(returningData);
			}
			var noData		= function()
			{
				notifySuccess("El c&oacute;digo <b>"+code+"</b> ha sido modificado correctamente.");
			}
			sumbitFields(process,haveData,noData);
			
		}
	});
}
