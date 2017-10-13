$(document).ready(function(){
	
	if(get['msg']=='addok')
	{
		notifySuccess('Factura cargada correctamente');
	}
	
	if(get['error']=="status")
	{
		notifyError('La factura no puede ser editada ya que no se encuentra en estado pendiente.');
	}
	
	if(get['error']=="user")
	{
		notifyError('La factura que desea editar no existe.');
	}
	
	
	////// LIST FUNCTIONS ////////
	// removeInvoice();
	
	
});

//////////////////////////// REMOVE INVOICE //////////////////////////////////
// function removeInvoice()
// {
// 	$(".RemoveInvoice").on('click',function(){
// 		var id=$(this).attr('id');
// 		var invoice = $("#invoice_text_"+id).html();
// 		alertify.confirm(utf8_decode('Está a punto de anula la factura N°'+invoice+' ¿Desea continuar?'), function(e)
// 		{
// 			if(e)
// 			{
// 				var order=$("#id").val();
// 				var process = process_url+'';
// 				var string	= 'order='+order+'&id='+ id +'&action=degenerateinvoice&object=ProviderOrder';
// 				$.ajax({
// 					type: "POST",
// 					url: process,
// 					data: string,
// 					cache: false,
// 					success: function(data)
// 					{
// 						if(!data)
// 						{
// 							// $("#row_invoice_"+id).remove();
// 							$("#invoice_span_"+id).html('<span class="label label-danger">Anulada</span>');
// 							$("#actions_span_"+id).html('');
// 						}else{
// 							console.log(data);
// 						}
// 					}
// 				});
// 			}
// 		});
// 	});
// }


///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate,#BtnCreateNext").on("click",function(e){
		e.preventDefault();
		if(validate.validateFields('*'))
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

			confirmText += " la orden de compra";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= process_url+'?object=Invoice';
					if(BtnID=="BtnCreate")
					{
						var target		= 'list.php?msg='+ $("#action").val();
					}else{
						var target		= 'new.php?msg='+ $("#action").val();
					}
					var haveData	= function(returningData)
					{
						$("input,select").blur();
						if(returningData=="403")
						{
							notifyError("No es posible editar esta orden. No se encuentra en el estado correcto.");
						}else{
							notifyError("Ha ocurrido un error durante el proceso de "+procText+".");
						}
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

	// $("input").keypress(function(e){
	// 	if(e.which==13){
	// 		if($("#BtnCreate").is(":disabled"))
	// 		{
	// 			$("#agent_new").click();
	// 		}else{
	// 			$("#BtnCreate").click();
	// 		}
	// 	}
	// });
});
