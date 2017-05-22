$.fn.datepicker.dates['es'] = {
    days: ["Domingo", "Lunes", "Martes", "Miércoles", "Juves", "Viernes", "Sábado"],
    daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
    daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
    today: "Hoy",
    clear: "Borrar",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 1
};

$(document).ready(function(){
	
	if(get['msg']=='addok')
	{
		notifySuccess('Stock ingresado correctamente');
	}
	
	if(get['error']=="status")
	{
		notifyError('La orden no puede ser editada ya que no se encuentra en estado pendiente.');
	}
	
	if(get['error']=="user")
	{
		notifyError('La orden que desea editar no existe.');
	}
	
	
	////// PAYMENT FUNCTIONS////////
	//updateTotalPrice()
	
	
	////// ORDER FUNCTIONS ////////
	addOrderItem();
	saveItem();
	calculateRowPrice();
	editItem();
	changeDates();
	countItems();
	calculateTotalOrderPrice();
	calculateTotalOrderQuantity();
	
	setDatePicker();
	priceImputMask(1);
	
	if($('.selectTags').length>0)
	{
		// $('#province_select').select2({placeholder: {id: '0',text: 'Seleccione una Provincia'}});
		// $('#province_select').on("select2:select", function (e) {$("#province").val(e.params.data.id); fillZoneSelect();});
		// $('#province_select').on("select2:unselect", function (e) { $("#province").val(''); });
		
		setAgentSelect2();
		$(".itemSelect").each(function(){
			var item = $(this).attr('item');
			setItemSelect2(item);
		});
		
		$('#providers').select2({placeholder: {id: '',text: 'Seleccione un Proveedor'}});
		$('#providers').on("select2:select", function (e) { $("#provider").val(e.params.data.id);fillAgentSelect(); });
		$('#providers').on("select2:unselect", function (e) { $("#provider").val(''); });
		
		
		
		$('#currency_selector').select2({placeholder: {id: '',text: 'Seleccione una Moneda'}});
		$('#currency_selector').on("select2:select", function (e) { $("#currency").val(e.params.data.id); });
		$('#currency_selector').on("select2:unselect", function (e) { $("#currency").val(''); });
		
		
		
		// $('#province').on("change", function (event) {event.preventDefault();  });
		select2Focus();
		
	}
});

function setItemSelect2(id)
{
	$('#items_'+id).select2({placeholder: {id: '',text: 'Seleccione un Artículo'}});
	$('#items_'+id).on("select2:select", function (e) { $("#item_"+id).val(e.params.data.id); });
	$('#items_'+id).on("select2:unselect", function (e) { $("#item_"+id).val(''); });
}

function setAgentSelect2()
{
	$('#agents').select2({placeholder: {id: '',text: 'Seleccione un Contacto'}});
	$('#agents').on("select2:select", function (e) { $("#agent").val(e.params.data.id); });
	$('#agents').on("select2:unselect", function (e) { $("#agent").val(''); });
}

function setDatePicker()
{
	$(".delivery_date").datepicker({
		autoclose:true,
		todayHighlight: true,
		language: 'es'
	});
}

function setADatePicker(element)
{
	$(element).datepicker({
		autoclose:true,
		todayHighlight: true,
		language: 'es'
	});
}

function priceImputMask(id)
{
	if($("#price_"+id).length>0)
		$("#price_"+id).inputmask();  //static mask
		
	if($("#quantity_"+id).length>0)
		$("#quantity_"+id).inputmask();  //static mask
	
	$("#price_"+id).change(function(){
		var decimal = $(this).val().split(".");
		if(decimal[1]=="__")
		{
			$("#price_"+id).val(decimal[0]+".00");
		}
	});	
}

//////////////////////////// ORDER ITEMS //////////////////////////////////
function addOrderItem()
{
	$("#add_order_item").click(function(){
		var id		= parseInt($("#items").val())+1;
		var process = '../../library/processes/proc.common.php';
		var string	= 'item='+ id +'&action=addorderitem&object=ProviderOrder';
		$.ajax({
	        type: "POST",
	        url: process,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	                //$("#ItemWrapper").append(data);
	                //$("#item_row_"+$("#items").val()).after(data);
	                $(".ItemRow:last-child").after(data);
	                $("#items").val(id);
	                setItemSelect2(id);
	                saveItem();
	                editItem();
	                deleteItem();
	                setADatePicker("#date_"+id);
	                validateDivChange();
	                countItems();
	                calculateRowPrice();
	                priceImputMask(id);
	                updateRowBackground();
	            }else{
	                console.log('Sin información devuelta. Item='+id);
	            }
	        }
	    });
		
	});
}

function saveItem()
{
	$(".SaveItem").on("click",function(){
		var id = $(this).attr("item");
		if(validate.validateFields('item_form_'+id))
		{
			var item_id = $("#item_"+id).val();
			var item = $("#items_"+id).children('option[value="'+item_id+'"]').html();
			var price = $("#price_"+id).val();
			var quantity = $("#quantity_"+id).val();
			var delivery = $("#date_"+id).val();
			$("#Item"+id).html(item);
			$("#Price"+id).html("$ "+price);
			$("#Quantity"+id).html(quantity);
			$("#Date"+id).html(delivery);
			$("#SaveItem"+id+",.ItemField"+id).addClass('Hidden');
			$("#EditItem"+id+",.ItemText"+id).removeClass('Hidden');
			$("#items_"+id).next().addClass('Hidden');
		}
	});
}

function editItem()
{
	$(".EditItem").on("click",function(){
		var id = $(this).attr("item");
		$("#SaveItem"+id+",.ItemField"+id).removeClass('Hidden');
		$("#EditItem"+id+",.ItemText"+id).addClass('Hidden');
		$("#items_"+id).next().removeClass('Hidden');
	});
}

function deleteItem()
{
	$(".DeleteItem").click(function(){
		var id = $(this).attr("item");
		$("#item_row_"+id).remove();
		countItems();
		calculateTotalOrderPrice();
		calculateTotalOrderQuantity();
		updateRowBackground();
	});
	
}

function updateRowBackground()
{
	var bgClass = "bg-gray";
	$(".ItemRow").each(function(){
		$(this).removeClass("bg-gray");
		$(this).removeClass("bg-gray-active");
		$(this).addClass(bgClass);
		if(bgClass == "bg-gray")
			bgClass = "bg-gray-active";
		else
			bgClass = "bg-gray";
	});
}

function countItems()
{
	$("#TotalItems").html($(".ItemRow").length);
}

function calculateRowPrice()
{
	$(".calcable").change(function(){
		var element = $(this).attr("id").split("_");
		var id = element[1];
		
		var price = parseFloat($("#price_"+id).val());
		var quantity = parseInt($("#quantity_"+id).val())
		if(price>0 && quantity>0)
			var total = price*quantity;
		else
			var total = 0.00;
		$("#item_number_"+id).attr("total",total);
		$("#item_number_"+id).html("$ "+total.formatMoney(2));	
		
		calculateTotalOrderPrice();
		calculateTotalOrderQuantity();
	});
}

function calculateTotalOrderQuantity()
{
	var total = 0;
	$(".QuantityItem").each(function(){
		var val = parseInt($(this).val());
		if(val>0)
			total = total + val;
	});
	
	$("#TotalQuantity").html(total);
}

function calculateTotalOrderPrice()
{
	var total = 0.00;
	$(".item_number").each(function(){
		var val = parseFloat($(this).attr("total"));
		if(val>0)
			total = total + val;
	});
	$("#total_price").val(total);
	$("#TotalPrice").html("$ "+total.formatMoney(2));
}

function changeDates()
{
	$("#ChangeDates").click(function(){
		var date = $("#change_date").val();
		alertify.confirm(utf8_decode('¿Desea aplicar la fecha '+date+' a todos los art&iacute;culos ?'), function(e){
		if(e)
		{
			$(".delivery_date").each(function(){
				$(this).val(date);
			});
			
			$(".OrderDate").each(function(){
				$(this).html(date);
			});
		}
		});
	});
}


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
					var process		= '../../library/processes/proc.common.php?object=ProviderOrder';
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




// ///////////////////////// LOAD AGENT SELECT ////////////////////////////////
function fillAgentSelect()
{
	var provider = $('#provider').val();
	var process = '../../library/processes/proc.common.php';

	var string      = 'provider='+ provider +'&action=fillagents&object=ProviderOrder';

    var data;
    $.ajax({
        type: "POST",
        url: process,
        data: string,
        cache: false,
        success: function(data){
            if(data)
            {
                $('#agent-wrapper').html(data);
                $("#agent").val('');
            }else{
                $('#agent-wrapper').html('<select id="agents" class="form-control select2 selectTags" disabled="disabled" style="width: 100%;"><option value="0">Sin Contacto</option</select>');
                $("#agent").val(0);
            }
            if($('#agents').length)
			{
				setAgentSelect2();
	            select2Focus();
			}
        }
    });
}



///////////////////////////////////////////// LIST FUNCTIONS //////////////////////////////////////

$(function(){
	
	$(".Invoice").click(function(){
		var ID = $(this).attr('id').split("_");
		ID = ID[1];
		var status = $(this).attr('status');
		
		var process = '../../library/processes/proc.common.php';
		var string	= 'item='+ ID +'&action=payorder&object=ProviderOrder&status='+status;
		$.ajax({
	        type: "POST",
	        url: process,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	               notifyError('Se produjo un error al querer cambiar de estado la orden');
	                console.log('Error al intentar cambiar de estado. Item='+ID+'. Error: '+data);
	            }else{
	                $(".searchButton").click();
	                notifySuccess('La orden se envi&oacute; a facturaci&oacute;n');
	                
	            }
	        }
	    });
		
	});
	
	
// 	$("#BtnContinue").click(function(){
// 		$("#ItemSelection").addClass('Hidden');
// 		$("#FillData").removeClass('Hidden');
// 		//$("#BtnCancel").removeClass('Hidden');
// 	});
	
	
// 	////////////////////////////////// Checkbox ////////////////////////////////
// 	$(".iCheckbox").on('ifChecked', function(){
// 		var id = $(this).attr("id");
// 		var item = $(this).attr("item");
// 		$("#id"+item).val(id);
// 		//updateTotalAmount();
// 	});
	
// 	$(".iCheckbox").on('ifUnchecked',function(){
// 		var item = $(this).attr("item");
// 		$("#id"+item).val('');
// 		//updateTotalAmount();
// 	});
	
// 	////////////////// PAYMENT PROCESS ///////////
// 	$("#BtnAdd").on("click",function(e){
// 		e.preventDefault();
// 		if(validate.validateFields('*'))
// 		{
// 			var TotalAmount = $('#total_currency').html()+$('#total_payment').html();

// 			alertify.confirm(utf8_decode('Est&aacute; a punto de realizar un pogo por <strong>'+TotalAmount+'</strong> ¿Desea countinuar?'), function(e){
// 				if(e)
// 				{
// 					var process		= '../../library/processes/proc.common.php?object=ProviderOrder';
// 					var target		= 'list.php?status=A&msg='+ $("#action").val();
					
// 					var haveData	= function(returningData)
// 					{
// 						$("input,select").blur();
// 						if(returningData=="403")
// 						{
// 							notifyError("No es posible pagar esta orden. No se encuentra en el estado correcto.");
// 						}else{
// 							notifyError("Ha ocurrido un error durante el proceso de pago.");
// 						}
// 						console.log(returningData);
// 					}
// 					var noData		= function()
// 					{
// 						document.location = target;
// 					}
// 					sumbitFields(process,haveData,noData);
// 				}
// 			});
// 		}
// 	});
});

// function updateTotalAmount()
// {
// 	var total = 0;
// 	$('.ItemsToPay').each(function(){
// 		var ID = $(this).attr('item');
// 		if($("#paid"+ID).val()!='')
// 			total += parseFloat($('#TotalPrice'+ID).html());
// 	});
// 	$('#total_payment').html(total);
// }

// function updateTotalPrice()
// {
// 	$(".QuantityField").change(function(){
// 		var ID		= $(this).attr('item');
// 		var total	= parseFloat($("#Price"+ID).html()) * parseFloat($(this).val());
// 		$("#TotalPrice"+ID).html(total);
// 	});
// }
