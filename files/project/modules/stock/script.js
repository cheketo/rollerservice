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
		notifyError('El stock no puede ser ingresado ya que no se encuentra en estado pendiente de ingreso.');
	}
	
	if(get['error']=="user")
	{
		notifyError('La orden correspondiente a los art&iacute;culos que desea ingresar no existe.');
	}
	
	
	
	setDatePicker();
	
	////////////////////////////////// Checkbox ////////////////////////////////
	$(".iCheckbox").on('ifChecked', function(){
		var id = $(this).attr("id");
		var item = $(this).attr("item");
		$("#received"+item).val(id);
	});
	
	$(".iCheckbox").on('ifUnchecked',function(){
		var item = $(this).attr("item");
		$("#received"+item).val('');
	});
	
	$("#confrim_sign").on('ifChecked',function(){
		$("#confirmed").val($(this).val());
	});
	
	$("#confrim_sign").on('ifUnchecked',function(){
		$("#confirmed").val('');
	});
	
	
});



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


///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnAdd").on("click",function(e){
		e.preventDefault();
		if(validate.validateFields('*'))
		{
			alertify.confirm(utf8_decode('¿Ha verificado correctamente todo el stock que est&aacute; a punto de ingresar?'), function(e){
				if(e)
				{
					var process		= process_url+'?object=Stock';
					if(get['view']=='order')
						var target		= '../provider_national_order/list.php?status=A&msg=addok';
					else
						var target		= 'stock_pending.php?msg=addok';
					
					var haveData	= function(returningData)
					{
						$("input,select").blur();
						notifyError("Ha ocurrido un error durante el ingreso de los artículos.");
						
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
			$("#BtnAdd").click();
		}
	});
});