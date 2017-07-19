// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El producto <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El producto <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea crear la l&iacute;nea <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea crear la l&iacute;nea <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea modificar la l&iacute;nea <b>'+$('#code').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});
//////////////////////////// CREATE/EDIT FUNCTIONS /////////////////////
function ShowCategoriesList(id)
{
    $('option[value="'+id+'"]').parent().parent().removeClass("Hidden");
    id = $('option[value="'+id+'"]').parent().parent().attr("category");
    if(id>0)
    {
        ShowCategoriesList(id);
    }
}

$(document).ready(function(){
    ////////////////////////// SET VALUES TO SELECT FIELDS ////////////
    if($('option[selected="selected"]').length>0)
    {
        var category = $('option[selected="selected"]');
        var categoryID = category.attr("value");
        var html = category.html();
        $("#category_selected").html(html);
        ShowCategoriesList(categoryID);
    }
});
/////////// Show or Hide Icons On subtop //////////////////////
$(document).ready(function() {
    $('#viewlistbt').removeClass('Hidden');
    $('#newprod').removeClass('Hidden');
    $('#showitemfilters').removeClass('Hidden');

////////////////////// NUMBERS MASKS ////////////////////////////
    // $('#price,#price_fob,#price_dispatch').mask('00000000.00',{reverse: true});
    if($('#stock').length>0)
      $('#stock,#stock_min,#stock_max').mask('000000000000',{reverse: true});
    if($('#price').length>0)
      $('#price').inputmask();
});

///////// Select Product/Item ////////////////////////

$(function(){
    $(".category_selector").on('change',function(){
      var id = $(this).val();
      var html = $('option[value="'+id+'"]').html();
      var level = parseInt($(this).parent().attr('level'));
      var nextLevel = level+1;
      $("#category_selected").html(html);
      $("#category").val(id);
      
      if(nextLevel<=$("#maxlevel").val())
      {
        HideLevels(nextLevel);
        $("#CountinueBtn").addClass("Hidden");
      }
      if($("#category_"+id).parent().length>0)
        $("#category_"+id).parent().removeClass('Hidden');
      else
        $("#CountinueBtn").removeClass("Hidden");
    });
    
    $('#dispatch_data').on('click',function(){
      $('#dispatch_data').addClass('Hidden');
      $('.Dispatch').removeClass('Hidden');
    });
  
});

function HideLevels(level)
{
  $('li[level="'+level+'"]').addClass('Hidden');
  $('li[level="'+level+'"]').children('select').val(0);
  level++;
  if(level<=$("#maxlevel").val())
    HideLevels(level);
}
  




//////////////////// Character Counter ///////////////////////////
$('input, textarea').keyup(function() {
  var max = $(this).attr('maxLength');
  var curr = this.value.length;
  var percent = (curr/max) * 100;
  var indicator = $(this).parent().children('.indicator-wrapper').children('.indicator').first();

  // Shows characters left
  indicator.children('.current-length').html(max - curr);

  // Change colors
  if (percent > 10 && percent <= 50) { indicator.attr('class', 'indicator low'); }
  else if (percent > 50 && percent <= 70) { indicator.attr('class', 'indicator med'); }
  else if (percent > 70 && percent < 100) { indicator.attr('class', 'indicator high'); }
  else if (percent == 100) { indicator.attr('class', 'indicator full'); }
  else { indicator.attr('class', 'indicator empty'); }
  indicator.width(percent + '%');
});


/////////////////////// Categories Behavior ///////////////////////////
$(function(){
    $(".BackToCategory").on('click',function(){
      $('.ProductDetails').addClass('Hidden');
      $('.CategoryMain').removeClass('Hidden');
    });
    
    $('.SelectCategory').click(function(){
      $('.CategoryMain').addClass('Hidden');
      $('.ProductDetails').removeClass('Hidden');
    });
});
