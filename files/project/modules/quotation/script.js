///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	//$("#prueba").keydown();
	// $("#prueba").onfocus(function(){$(this).change(); alert('entra');});
	if(get['msg']=='insert')
		notifySuccess('La cotizaci&oacute;n de <b>'+get['element']+'</b> ha sido creada correctamente.');
	if(get['msg']=='update')
		notifySuccess('La cotizaci&oacute;n de <b>'+get['element']+'</b> ha sido modificada correctamente.');
	if(get['error']=="status")
		notifyError('La cotizaci&oacute;n no puede ser editada ya que no se encuentra en estado activo.');
	if(get['error']=="user")
		notifyError('La cotizaci&oacute;n que desea editar no existe.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	var role = 'Quotation'
	var msg = $("#action").val();
	var params = '';
	if(get['customer'])
		params += '&customer='+get['customer'];
	if(get['provider'])	
		params += '&provider='+get['provider'];
	if(get['international'])	
		params += '&international='+get['international'];
	$("#BtnCreate").click(function(){
		var element = $('#company option:selected').html();
		var target	= 'list.php?element='+element+'&msg='+msg+params;
		askAndSubmit(target,role,'¿Desea crear la cotizaci&oacute;n de <b>'+element+'</b>?','','QuotationForm');
	});
	$("#BtnCreateNext").click(function(){
		var element = $('#company option:selected').html();
		var target		= 'new.php?element='+element+'&msg='+msg+params;
		askAndSubmit(target,role,'¿Desea crear la cotizaci&oacute;n de <b>'+element+'</b>?','','QuotationForm');
	});
	$("#BtnEdit").click(function(){
		var element = $('#company option:selected').html();
		var target		= 'list.php?element='+element+'&msg='+msg+params;
		askAndSubmit(target,role,'¿Desea modificar la cotizaci&oacute;n de <b>'+element+'</b>?','','QuotationForm');
	});
	// $("input").keypress(function(e){
	// 	if(e.which==13){
	// 		$("#BtnCreate,#BtnEdit").click();
	// 	}
	// });
});

///////////////////////////// QUOTATION FUNCTIONS ///////////////////////////
$(document).ready(function(){
	addItem();
	saveItem();
	calculateRowPrice();
	editItem();
	changeDates();
	countItems();
	calculateTotalQuotationPrice();
	calculateTotalQuotationQuantity();
	deleteItem();
	setDatePicker();
	priceImputMask(1);
	updateDeliveryDateFromDays();
	updateAllDeliveryDates();
	showHistoryWindow();
	showHistoryButtons();
	checkHistoryButtons();
});

function setDatePicker()
{
	if($(".delivery_date").length>0)
	{
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
		setADatePicker();
	}
}

function setADatePicker()
{
	$(".delivery_date").datepicker({
		autoclose:true,
		todayHighlight: true,
		language: 'es'
	});
}

function priceImputMask(id)
{
	
	$("#price_"+id).change(function(){
		var decimal = $(this).val().split(".");
		if(decimal[1]=="__")
		{
			$("#price_"+id).val(decimal[0]+".00");
		}
	});	
}

function showHistoryButtons()
{
	$("#company,.itemSelect").change(function(){
		checkHistoryButtons();
	});
}

function checkHistoryButtons()
{
	$(".itemSelect").each(function(){
		var itemid = $(this).attr("item");
		if($("#item_"+itemid).val()>0 && $("#company").val())
		{
			$("#HistoryItem"+itemid).removeClass("Hidden");
		}else{
			$("#HistoryItem"+itemid).addClass("Hidden");
		}
	})
	
}

//////////////////////////// QUOTATION ITEMS //////////////////////////////////
function addItem()
{
	$("#add_quotation_item").click(function(){
		var id		= parseInt($("#items").val())+1;
		var string	= 'item='+ id +'&action=additem&object=Purchase';
		$.ajax({
	        type: "POST",
	        url: process_url,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	                $(".ItemRow:last-child").after(data);
	                $("#items").val(id);
	                setItemChosen(id);
	                saveItem();
	                editItem();
	                deleteItem();
	                setADatePicker();
	                validateDivChange();
	                countItems();
	                calculateRowPrice();
	                priceImputMask(id);
	                updateRowBackground();
	                // recalculateItemPrice();
	                updateDeliveryDateFromDays();
	                showHistoryWindow();
	                showHistoryButtons();
	                checkHistoryButtons();
	                $("#day_"+id).change();
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
			// var item_id = $("#item_"+id).val();
			var item = $("#TextAutoCompleteitem_"+id).val();
			var price = $("#price_"+id).val();
			var quantity = $("#quantity_"+id).val();
			var delivery = $("#date_"+id).val();
			var days = $("#day_"+id).val();
			if(!days) days="0";
			$("#Item"+id).html('<i class="fa fa-cube"></i> '+item);
			$("#Price"+id).html("$ "+price);
			$("#Quantity"+id).html(quantity);
			$("#Date"+id).html(delivery);
			$("#Day"+id).html(days);
			$("#SaveItem"+id+",.ItemField"+id).addClass('Hidden');
			$("#EditItem"+id+",.ItemText"+id).removeClass('Hidden');
			$("#item_"+id).next().addClass('Hidden');
		}
	});
}

function editItem()
{
	$(".EditItem").on("click",function(){
		var id = $(this).attr("item");
		$("#SaveItem"+id+",.ItemField"+id).removeClass('Hidden');
		$("#EditItem"+id+",.ItemText"+id).addClass('Hidden');
		$("#item_"+id).next().removeClass('Hidden');
	});
}

function deleteItem()
{
	$(".DeleteItem").click(function(){
		var id = $(this).attr("item");
		$("#item_row_"+id).remove();
		countItems();
		calculateTotalQuotationPrice();
		calculateTotalQuotationQuantity();
		updateRowBackground();
	});
}

function updateDeliveryDateFromDays()
{
	$(".DayPicker").change(function(){
		var id = $(this).attr("id").split("_");
		if(parseInt($(this).val())>-1)
		{
			var creation_date = $("#creation_date").val();
			var DeliveryDate = AddDaysToDate($(this).val(),creation_date);
			$("#date_"+id[1]).val(DeliveryDate);
		}
	});
}

function updateAllDeliveryDates()
{
	$(".DayPicker").each(function(){
		$(this).change();
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
		
		calculateTotalQuotationPrice();
		calculateTotalQuotationQuantity();
	});
}

function calculateTotalQuotationQuantity()
{
	var total = 0;
	$(".QuantityItem").each(function(){
		var val = parseInt($(this).val());
		if(val>0)
			total = total + val;
	});
	
	$("#TotalQuantity").html(total);
}

function calculateTotalQuotationPrice()
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
	$("#ChangeDays").click(function(){
		var days = $("#change_day").val();
		alertify.confirm(utf8_decode('¿Desea establecer '+days+' d&iacute;as de entrega para todos los art&iacute;culos ?'), function(e){
		if(e)
		{	
			$(".DayPicker").each(function(){
				if(!$(this).hasClass('Restricted'))
					$(this).val(days);
					$(this).change();
			});
			
			$(".OrderDay").each(function(){
				if(!$(this).hasClass('Restricted'))
					$(this).html(days);
			});
			
			$(".OrderDate").each(function(){
				if(!$(this).hasClass('Restricted'))
				{
					var id = $(this).attr("id").split("Date");
					$(this).html($("#date_"+id[1]).val());
				}
			});
		}
		});
	});
}


function showHistoryWindow()
{
	$(".HistoryItem").click(function(){
		var id = $(this).attr("item");
		$("#window_traceability").removeClass("Hidden");
		$("#product").val($("#item_"+id).val());
		$("#item").val(id);
		FillTraceabilityWindow();
	})	
}

/////////////////////////// LOAD AGENT SELECT ////////////////////////////////
$(function(){
	$("#company").change(function(){
		if($(this).val())
		{
			fillAgent();
		}
	});
});

function fillAgent()
{
	var company = $('#company').val();
	var process = process_url;
	var string      = 'id='+ company +'&action=fillagents&object=Purchase';
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
                
            }else{
                $('#agent-wrapper').html('<select id="agents" class="form-control chosenSelect" disabled="disabled" ><option value="0">Sin Contacto</option</select>');
                
            }
            chosenSelect();
        }
    });
}


////////////////////////////////////////// CALCULATE ITEM PRICE BY CUSTOMER ///////////////////////////////
$(document).ready(function(){
	if($(".itemSelect").length>0 && get['customer']=='Y')
	{
		$(".itemSelect").each(function(){
			var item = $(this).attr('item');
			setItemChosen(item);
		});
	}
});

function setItemChosen(id)
{
	SetAutoComplete('#TextAutoCompleteitem_'+id);
	$('#TextAutoCompleteitem_'+id).on('change',function(){
		getProductsPrices($('#item_'+id).val(),id);
	});
}

function getProductsPrices(values,ids)
{
	var string	= 'items='+values+'&action=Getitemprices&object=Purchase';
	if(values.length>0 && get['customer']=='Y')
	{
		if(ids)
		{
			ids = ids +'';
			$.ajax({
		        type: "POST",
		        url: process_url,
		        data: string,
		        success: function(data){
		            if(data)
		            {
		            	console.log(data);
		            	var prices = data.split(",");
		            	var items = ids.split(",");
		            	var decimal;
		            	prices.forEach(function(price,index){
		            		decimal = price.substr(price.indexOf("."));
			            	if(decimal.length==1)
			            	{
			            		price = price + ".00";
			            	}
			            	if(decimal.length==2)
			            	{
			            		price = price + "0";
			            	}
			            	$("#price_"+items[index]).val(price);
			            	$("#Price"+items[index]).html("$ "+price);
		            	});
		            }else{
		            	notifyError('Hubo un error al calcular el precio del producto');
		                console.log('Sin información devuelta. Item='+id);
		            }
		        }
		    });
		}
	}
}

///////////////////////////////////////////// LIST FUNCTIONS //////////////////////////////////////

//STORE QUOTATION
$(function(){
	$(".storeElement").click(function(){
		var ID = $(this).attr('id').split("_");
		ID = ID[1];
		var process = process_url+'';
		var string	= 'id='+ ID +'&action=store&object=Quotation';//&status='+status;
		$.ajax({
	        type: "POST",
	        url: process,
	        data: string,
	        cache: false,
	        success: function(data){
	            if(data)
	            {
	              notifyError('Se produjo un error al archivar la cotización');
	                console.log('Error al intentar cambiar de estado. Item='+ID+'. Error: '+data);
	            }else{
	                $(".searchButton").click();
	                notifySuccess('La cotización se archiv&oacute; correctamente');
	            }
	        }
	    });
	});
});