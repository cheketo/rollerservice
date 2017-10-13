///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('La marca <b>'+get['element']+'</b> ha sido creada correctamente.');
	if(get['msg']=='update')
		notifySuccess('La marca <b>'+get['element']+'</b> ha sido modificada correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#name').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Brand','¿Desea crear la marca <b>'+$('#name').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#name').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Brand','¿Desea crear la marca <b>'+$('#name').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#name').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Brand','¿Desea modificar la marca <b>'+$('#name').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});