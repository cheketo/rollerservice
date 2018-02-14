///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function()
{
	if(get['msg']=='insert')
		notifySuccess('La moneda <b>'+get['element']+'</b> ha sido creada correctamente.');
	if(get['msg']=='update')
		notifySuccess('La moneda <b>'+get['element']+'</b> ha sido modificada correctamente.');
	if(get['error']=="status")
		notifyError('La moneda no puede ser editada ya que no se encuentra en estado activa.');
	if(get['error']=="user")
		notifyError('La moneda que desea editar no existe.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function()
{
	var role = 'Currency'
	var msg = $("#action").val();
	var element = $('#title').val();
	var prefix = $('#prefix').val();
	$("#BtnCreate").click(function()
	{
		var target	= 'list.php?element='+element+'&msg='+msg;
		askAndSubmit(target,role,'¿Desea crear la moneda <b>'+element+' ('+prefix+')</b>?');
	});
	$("#BtnEdit").click(function()
	{
		var target		= 'list.php?element='+element+'&msg='+msg;
		askAndSubmit(target,role,'¿Desea modificar la moneda <b>'+element+' ('+prefix+')</b>?');
	});
});