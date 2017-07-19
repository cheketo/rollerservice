// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('La l&iacute;nea <b>'+get['element']+'</b> ha sido creada correctamente.');
	if(get['msg']=='update')
		notifySuccess('La l&iacute;nea <b>'+get['element']+'</b> ha sido modificada correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Category','¿Desea crear la l&iacute;nea <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Category','¿Desea crear la l&iacute;nea <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Category','¿Desea modificar la l&iacute;nea <b>'+$('#title').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});