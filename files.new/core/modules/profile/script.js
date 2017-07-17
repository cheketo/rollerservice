///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El perfil <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El perfil <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreProfile','¿Desea crear el perfil <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreProfile','¿Desea crear el perfil <b>'+$('#title').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreProfile','¿Desea modificar el perfil <b>'+$('#title').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});

///////////////// TREECHECKBOXES ///////////////////
$(document).ready(function(){
	if($('#treeview-checkbox').length)
	{
		$('#treeview-checkbox').treeview();
		fillCheckboxTree();
	}
});
$(function() {
	$(".tw-control").click(function(){
		var selected = [];
		$(".tw-control").each(function(){
			if($(this).is(":checked"))
			{
				selected.push($(this).parent().attr("data-value"));
			}
		});
		$("#menues").val(selected.join());
	});
});
function fillCheckboxTree()
{
	var menues = $("#menues").val().split(',');
	$(".tw-control").each(function(menu){
		if(inArray($(this).parent().attr("data-value"),menues))
		{
			//alert($(this).parent().attr("data-value"));
			$(this).click();
		}
	});
}

///////////////////////////// UPLOAD IMAGE /////////////////////////////////////
$(function(){
	$("#image").change(function(){
		var process		= process_url+'?action=newimage&object=CoreProfile';
		var haveData	= function(returningData)
		{
			$('#newimage').val(returningData);
			$(".MainImg").attr("src",returningData);
			$('.MainImg').addClass('pulse').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		      $(this).removeClass('pulse');
		    });
		}
		var noData		= function(){alert("No data");}
		sumbitFields(process,haveData,noData);
	});

	$('.imgSelectorContent').click(function(){
		$("#image").click();
	});
});
