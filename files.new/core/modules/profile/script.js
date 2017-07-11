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

			confirmText += " el perfil '"+$("#title").val()+"'";

			alertify.confirm(utf8_decode('¿Desea '+confirmText+' ?'), function(e){
				if(e)
				{
					var process		= process_url+'?object=CoreProfile';
					if(BtnID=="BtnCreate")
					{
						var target		= 'list.php?element='+$('#title').val()+'&msg='+ $("#action").val();
					}else{
						var target		= 'new.php?element='+$('#title').val()+'&msg='+ $("#action").val();
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
		var selected = "0"
		$(".tw-control").each(function(){
			if($(this).is(":checked"))
			{
				selected += ","+$(this).parent().attr("data-value");
			}
		});
		$("#menues").val(selected);
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
