// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='newcomparation')
		notifySuccess('La comparaci&oacute;n se ha generado correctamente.');
	// if(get['msg']=='updaterelation')
	// 	notifySuccess('La relaci&oacute;n del c&oacute;digo <b>'+get['element']+'</b> ha sido modificada correctamente.');
	// if(get['msg']=='relation')
	// 	notifySuccess('Los productos importados de <b>'+get['element']+'</b> han sido asociados correctamente.');
});

$(function(){
	///////////////////////// CREATE COMPARATION LIST ////////////////////////////////////
	$("#BtnCompare").click(function(){
		if(validate.validateFields('*'))
		{
			var process	= process_url+'?action=Compare&object=ProductComparation';
			var haveData	= function(returningData)
			{
				if(!isNaN(returningData))
				{
					document.location = "edit.php?msg=newcomparation&comparation="+returningData;	
				}else{
					notifyError("Hubo un error al querer guardar los datos");
					console.log(returningData);
				}
			}
			var noData		= function()
			{
				notifyError("Hubo un error al querer guardar los datos");
				console.log("No Data Returned");
			}
			sumbitFields(process,haveData,noData);
			
		}	
	});
	
	
});

///////////////////////// SAVE STOCK TO ORDER FROM ITEM LIST ////////////////////////////////////
function AdditionalSearchFunctions()
{
	SaveStockOrder();
}

function SaveStockOrder()
{
	$(".ItemStock").change(function(e){
		e.stopImmediatePropagation();
		var stock = $(this).val();
		var id = $(this).attr('item');
		var string	= 'item='+ id +'&stock='+stock+'&action=updateitemstock&object=ProductComparationItem';
		
		if(!isNaN(stock) && stock>=0)
		{
			$.ajax({
		        type: "POST",
		        url: process_url,
		        data: string,
		        cache: false,
		        success: function(data){
		            if(data)
		            {
		                notifyError('Ha sucedido un error al querer modificar el stock.');
		                console.log(data);
		            }else{
		            	// notifySuccess('Stock a ordenar guardado correctamente.');
		            }
		        }
		    });
		}else{
			notifyError('Ingrese un stock v&aacute;lido');
		}
	})
}