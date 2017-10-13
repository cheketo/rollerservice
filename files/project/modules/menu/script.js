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

			confirmText += " el menu '"+utf8_encode($("#title").val())+"'";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= process_url+'?object=CoreMenu';
					if(BtnID=="BtnCreate")
					{
						var target		= 'list.php?element='+utf8_encode($('#title').val())+'&msg='+ $("#action").val();
					}else{
						var target		= 'new.php?element='+utf8_encode($('#title').val())+'&msg='+ $("#action").val();
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

$(function(){
	$("#create").click(function(){
		if(validate.validateFields('')){
			var process		= 'process.php';
	 		var target		= 'list.php?msg='+ $("#action").val();
	 		var haveData	= function(returningData)
	 		{
	 			$("input,select").blur();
				notifyError(returningData);
	 		}
	 		var noData		= function()
	 		{
	 			document.location = target;
	 		}
			sumbitFields(process,haveData,noData);
		}
	});

	$("input").keypress(function(e){
		if(e.which==13){
			$("#create").click();
		}
	});

	// Active Inactive Switch
	//$("[name='public']").bootstrapSwitch();
});



/////////////// Bootstrap Switch ////////////////////
$(function(){
	if($("#public[type='checkbox']").length>0)
		$("#public").bootstrapSwitch();
	if($("#status[type='checkbox']").length>0)
		$("#status").bootstrapSwitch();
});


/////////////// ICON SELECTION //////////////////////

$('.IconInput').click(function() {
	$('#iconModal').modal('show');
});


////////// Menu Icon Selection Marker ///////////////
$('.iconModalContent i').click(function(e){
	$('#iconModal').modal('hide');
	$('.IconInput').html($(this));
	var iconClass = $(this).attr("class");
	var icon = iconClass.split(" ");
	$('#icon').val(icon[2]);
});