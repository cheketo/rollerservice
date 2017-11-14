// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El producto <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El producto <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

$(function(){
	///////////////////////// CREATE/EDIT ////////////////////////////////////
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea crear el producto <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea crear el producto <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea modificar el producto <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnImport").click(function(){
		var target		= 'import_selection.php?id='+$('#id').val()+'&element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductRelation','¿Desea importar el archivo <b>'+$('#Fileprice_list').val()+'</b>?','Ha ocurrido un error durante la importaci&oacute;n. Revise el documento que está importando y aseg&uacute;rese que la columna "C&oacute;digo" se encuentre completa en todas las filas.');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
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
							var updateImportStatus = function(data)
							{
								console.log(data);
								notifyError("Se ha producido un error al modificar el estado de la importaci&oacute;n previa.");
							}
							sumbitFields(process_url+'?object=ProductRelation',haveData,function(){});
						}
					});
				}else{
					notifyError("Se ha producido un error al consultar si existen importaciones previas.");
					console.log(data);
				}
			}
			sumbitFields(process_url+'?object=ProductRelation',haveData,function(){});
			$("#action").val(oldAction);
		}
	})
});




///////////// DATATABLE EXAMPLE////////
$(document).ready(function() {
    var table = $('#table_import').DataTable({
    	"language": {
            "lengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No se encontraron registros",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
 
    // $('button').click( function() {
    //     var data = table.$('input, select').serialize();
    //     alert(
    //         "The following data would have been submitted to the server: \n\n"+
    //         data.substr( 0, 120 )+'...'
    //     );
    //     return false;
    // } );
} );
