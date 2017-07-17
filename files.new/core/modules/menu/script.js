///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El menú <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El menú <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreMenu','¿Desea crear el menú <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreMenu','¿Desea crear el menú <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreMenu','¿Desea modificar el menú <b>'+$('#title').val()+'</b>?');
	});
	
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});

/////////////// Bootstrap Switch ////////////////////
	if($("#public[type='checkbox']").length>0)
		$("#public").bootstrapSwitch();
	if($("#status[type='checkbox']").length>0)
		$("#status").bootstrapSwitch();
		
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
});
