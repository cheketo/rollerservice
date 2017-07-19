///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El usuario <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El usuario <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#user').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreUser','¿Desea crear el usuario <b>'+$('#user').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#user').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreUser','¿Desea crear el usuario <b>'+$('#user').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#user').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'CoreUser','¿Desea modificar el usuario <b>'+$('#user').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});
///////////////////////////// Upload Image /////////////////////////////////////
$(function(){
	$("#image").change(function(){
		var process		= process_url+'?action=newimage&object=CoreUser';
		var haveData	= function(returningData)
		{
			$('#newimage').val(returningData);
			$(".MainImg").attr("src",returningData);
			$('.MainImg').addClass('pulse').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		      $(this).removeClass('pulse');
		    });
			$('#UserImages').append('<li><img src="'+returningData+'" class="ImgSelecteable"></li>');
			selectImg();
		}
		var noData		= function(){}
		sumbitFields(process,haveData,noData);
	});

	$('.imgSelectorContent').click(function(){
		$("#image").click();
	});

	selectImg();
});

function selectImg()
{
	$(".ImgSelecteable").click(function(){
		var src = $(this).attr("src");
		$(".MainImg").attr("src",src);
		$('.MainImg').addClass('pulse').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
	      $(this).removeClass('pulse');
	    });
		$("#newimage").val(src);
	});
}

// /////////////////////////// Fill Groups /////////////////////////////////////
$(document).ready(function(){
	fillGroups();
});

$(function(){
	$('#profile').change(function(){
		fillGroups();
	});
});

function fillGroups()
{
	var profile = $('#profile').val();
	var id 		= $('#id').val();
	var process = process_url+'';

	var string      = 'profile='+ profile +'&id='+ id +'&action=fillgroups&object=CoreUser';

    var data;
    $.ajax({
        type: "POST",
        url: process,
        data: string,
        cache: false,
        success: function(data){
            if(data)
            {
                $('#groups-wrapper').html(data);
            }else{
                $('#groups-wrapper').html('<h4 class="subTitleB"><i class="fa fa-users"></i> Grupos</h4><select id="groups" class="form-control chosenSelect" multiple="multiple" disabled="disabled" data-placeholder="Seleccione los grupos"></select>');
            }
            chosenSelect();
        }
    });
}

///////////////// TreeCheckboxes Multiple Select ///////////////////
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
			$(this).click();
		}
	});
}

