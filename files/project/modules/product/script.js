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
		askAndSubmit(target,'Product','¿Desea crear el producto <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea crear el producto <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'Product','¿Desea modificar el producto <b>'+$('#code').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});


///////////////////////////// LIST FUNCTION ////////////////////////////
function AdditionalSearchFunctions()
{
  reactivate();
  discontinue();
}

function reactivate()
{
  $(".reactivate").click(function(){
    var id = $(this).attr('id').split("_");
    alertify.confirm(utf8_decode('¿Desea normalizar el art&iacute;culo seleccionado?'), function(e){
      if(e){
        id = id[1];
    		var string	= 'id='+ id +'&action=reactivate&object=Product';
    		$.ajax({
    			type: "POST",
    			url: process_url,
    			data: string,
    			cache: false,
    			success: function(data)
    			{
    				if(data)
    				{
    				  notifyError("Ocurri&oacute; un error al intentar reactivar el art&iacute;culo.");
    				  console.log(data);
    				}else{
    				  notifySuccess("El art&iacute;culo ha sido reactivado correctamente.");
    				  $("#reactivate_"+id).addClass("Hidden");
    				  $("#discontinue_"+id).removeClass("Hidden");
    				}
    			}
    		});
      }
    });
  });
}

function discontinue()
{
  $(".discontinue").click(function(){
    var id = $(this).attr('id').split("_");
    alertify.confirm(utf8_decode('¿Desea discontinuar el art&iacute;culo seleccionado?'), function(e){
      if(e){
        id = id[1];
    		var string	= 'id='+ id +'&action=discontinue&object=Product';
    		$.ajax({
    			type: "POST",
    			url: process_url,
    			data: string,
    			cache: false,
    			success: function(data)
    			{
    				if(data)
    				{
    				  notifyError("Ocurri&oacute; un error al intentar reactivar el art&iacute;culo.");
    				  console.log(data);
    				}else{
    				  notifySuccess("El art&iacute;culo ha sido reactivado correctamente.");
    				  $("#reactivate_"+id).removeClass("Hidden");
    				  $("#discontinue_"+id).addClass("Hidden");
    				}
    			}
    		});
      }
    });
  });
}

//////////////////////////// CREATE/EDIT FUNCTIONS /////////////////////
// function ShowCategoriesList(id)
// {
//     $('option[value="'+id+'"]').parent().parent().removeClass("Hidden");
//     id = $('option[value="'+id+'"]').parent().parent().attr("category");
//     if(id>0)
//     {
//         ShowCategoriesList(id);
//     }
// }

$(document).ready(function(){
    ////////////////////////// SET VALUES TO SELECT FIELDS ////////////
    // if($('option[selected="selected"]').length>0)
    // {
    //     var category = $('option[selected="selected"]');
    //     var categoryID = category.attr("value");
    //     var html = category.html();
    //     $("#category_selected").html(html);
    //     ShowCategoriesList(categoryID);
    // }
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
    // $(".category_selector").on('change',function(){
    //   var id = $(this).val();
    //   var html = $('option[value="'+id+'"]').html();
    //   var level = parseInt($(this).parent().attr('level'));
    //   var nextLevel = level+1;
    //   $("#category_selected").html(html);
    //   $("#category").val(id);
      
    //   if(nextLevel<=$("#maxlevel").val())
    //   {
    //     HideLevels(nextLevel);
    //     $("#CountinueBtn").addClass("Hidden");
    //   }
    //   if($("#category_"+id).parent().length>0)
    //     $("#category_"+id).parent().removeClass('Hidden');
    //   else
    //     $("#CountinueBtn").removeClass("Hidden");
    // });
    
    $('#dispatch_data').on('click',function(){
      $('#dispatch_data').addClass('Hidden');
      $('.Dispatch').removeClass('Hidden');
    });
  
});

// function HideLevels(level)
// {
//   $('li[level="'+level+'"]').addClass('Hidden');
//   $('li[level="'+level+'"]').children('select').val(0);
//   level++;
//   if(level<=$("#maxlevel").val())
//     HideLevels(level);
// }
  




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
    // $(".BackToCategory").on('click',function(){
    //   $('.ProductDetails').addClass('Hidden');
    //   $('.CategoryMain').removeClass('Hidden');
    // });
    
    // $('.SelectCategory').click(function(){
    //   $('.CategoryMain').addClass('Hidden');
    //   $('.ProductDetails').removeClass('Hidden');
    // });
});



/////////////////////////////// RELATION FUNCTIONS //////////////////////////////////
$(document).ready(function(){
  if(get['product_id'])
  {
    $("#product_id").change();
  }
  // if(get['company_id'])
  // {
  //   $("#company_id").change();
  // }
  
  // updateProductInfo();
  updateBrandCode();
  
  $("#RelationBtn").click(function(){
		var target		= 'list.relation.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductRelation','¿Desea relacionar a <b>'+$('#code').val()+'</b>?');
	});
	$("#RelationNext").click(function(){
		var target		= 'new.relation.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductRelation','¿Desea relacionar a <b>'+$('#code').val()+'</b>?');
	});
  
});

// function updateProductInfo()
// {
//   $("#product_id").on("change",function(){
    
//     var id = $("#product_id").val();
// 		var process = process_url;
// 		var string	= 'id='+ id +'&action=consult&object=ProductCompany';
// 		$.ajax({
// 			type: "POST",
// 			url: process,
// 			data: string,
// 			cache: false,
// 			success: function(data)
// 			{
// 				if(!data)
// 				{
// 					console.log('AjaxError: No information returned');
// 				}else{
// 				// 	$("#product_detail").html();
// 				// 	$("#product_detail").html(data);
//     //       $("#product_detail").removeClass('Hidden');
//           $("#product_relation").removeClass('Hidden');
//           $("#Relation").removeAttr("disabled");
//           $("#RelationNext").removeAttr("disabled");
// 				}
// 			}
// 		});
    
//   });
// }

function updateBrandCode()
{
  $("#product_id").on("change",function(){
    var id = $(this).val();
		var string	= 'id='+ id +'&action=checkrelation&object=ProductRelation';
		$.ajax({
			type: "POST",
			url: process_url,
			data: string,
			cache: false,
			success: function(data)
			{
				if(data && data!="///")
				{
				  var info = data.split("///");
					$("#code").val(info[0]);
					$("#relation").val(info[1]);
				}else{
				  $("#code").val('');
				  $("#relation").val('');
				}
			}
		});
    
  });
}