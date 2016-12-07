$(document).ready(function(){
	if($('.selectTags').length>0)
	{
		$('.selectTags').select2({placeholder: {id: '0',text: 'Seleccionar Marca'},allowClear: true});
		$('.selectTags').on("select2:select", function (e) { $("#brand").val(e.params.data.id); });
		$('.selectTags').on("select2:unselect", function (e) { $("#brand").val(''); });
	}
});
/////////// Show or Hide Icons On subtop //////////////////////
$(document).ready(function() {
    // $('#showitemfilters').click(function() {
    //     $('#filteritem').toggle("slide");
    // });
    $('#viewlistbt').removeClass('Hidden');
    $('#newprod').removeClass('Hidden');
    $('#showitemfilters').removeClass('Hidden');
    
    $('#price').mask('00000000.00',{reverse: true});
    $('#stock,#stock_min,#stock_max').mask('000000000000',{reverse: true});
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
  
    // $('.overlayDetails').click(function(){
    //   $('.selectedItem').removeClass('Hidden');
    // });

    // $('.selectedItem').click(function(){
    //   $('.selectedItem').addClass('Hidden');
    // });
});

function HideLevels(level)
{
  $('li[level="'+level+'"]').addClass('Hidden');
  $('li[level="'+level+'"]').children('select').val(0);
  level++;
  if(level<=$("#maxlevel").val())
    HideLevels(level);
}

////////////////// Select Products / Items/////////////////////////////////////////

  $(function () {
    // $('#selectItemProd').click(function(){
    //   $('#itemProdDiv').toggleClass('selectItemProd');
    // });

    // $('#selectItemProd').click(function() {
    //   if($('#itemProdDiv').hasClass('selectItemProd1')) {
    //       $('#itemProdDiv').removeClass('selectItemProd1').addClass('selectItemProd2');
    //   }
    //   else{
    //       $('#itemProdDiv').removeClass('selectItemProd2').addClass('selectItemProd1');
    //   };
    //   // Select Button Styles
    //   if($('#itemProdDiv').hasClass('selectItemProd2')) {
    //       $('#selectItemProd').removeClass('notSelectedBtn').addClass('itemProdSelectedBtn');
    //   }
    //   else{
    //       $('#selectItemProd').removeClass('itemProdSelectedBtn').addClass('notSelectedBtn');
    //   };
    // });
  });
  
/// Bootstrap Switch ///
// $("[name='status']").bootstrapSwitch();

///////////// Color picker ///////////////////

//Select Primary Color
// $('#cpLibrary1 ul li').click(function(){
//   $('#cpLibrary1 ul li').removeClass('cpSelected liSelected');
//   $(this).toggleClass('cpSelected liSelected');
// });

// Select Secondary Color
// $('#cpLibrary2 ul li').click(function(){
//   $('#cpLibrary2 ul li').removeClass('cpSelected liSelected');
//   $(this).toggleClass('cpSelected liSelected');
// });

// Show Selected Primary Color
// $('#cpLibrary1 ul li').click(function(){
//   var colorPC = $(this).data('hex');
//   $('#selectedColor1').css("background-color", colorPC);
// });

// Show Selectes Secondary Color
// $('#cpLibrary2 ul li').click(function(){
//   var colorPC = $(this).data('hex');
//   $('#selectedColor2').css("background-color", colorPC);
// });

// $('.ShowCP2').click(function(){
//   $(this).addClass('Hidden');
//   $('.ColorPicker2').removeClass('Hidden');
// });

// $('.CloseColorPicker').click(function(){
//   $('.ColorPicker2').addClass('Hidden');
//   $('.ShowCP2').removeClass('Hidden');
// });



//////////////////// Character Counter ///////////////////////////
$('input, textarea').keyup(function() {
  var max = $(this).attr('maxLength');
  var curr = this.value.length;
  var percent = (curr/max) * 100;
  var indicator = $(this).parent().children('.indicator-wrapper').children('.indicator').first();

  // Shows characters left
  indicator.children('.current-length').html(max - curr);

  // Change colors
  if (percent > 30 && percent <= 50) { indicator.attr('class', 'indicator low'); }
  else if (percent > 50 && percent <= 70) { indicator.attr('class', 'indicator med'); }
  else if (percent > 70 && percent < 100) { indicator.attr('class', 'indicator high'); }
  else if (percent == 100) { indicator.attr('class', 'indicator full'); }
  else { indicator.attr('class', 'indicator empty'); }
  indicator.width(percent + '%');
});


/////////////////////// Categories Behavior DEMO ///////////////////////////


//-----  Highlight Selected MAIN Category----- //
$(".squareMenuMain").children().click(function() {
  $('.squareItemMenu').addClass('squareItemDisabled');
  $('.squareItemMenu').removeClass('squareItemActive');
  $('.squareItemMenu .arrow-css').addClass('Hidden');

  $(this).removeClass('squareItemDisabled');
  $(this).addClass('squareItemActive');
  $('.arrow-css', this).removeClass('Hidden');
})


// $('.CategoryVehicleTrigger').click(function(){
//   $('.CategoryVehicles').removeClass('Hidden');
//   $('.CategoryRealState').addClass('Hidden');
//   $('.CategoryServices').addClass('Hidden');
//   $('.CategoryProducts').addClass('Hidden');
// })
// $('.CategoryRealStateTrigger').click(function(){
//   $('.CategoryVehicles').addClass('Hidden');
//   $('.CategoryRealState').removeClass('Hidden');
//   $('.CategoryServices').addClass('Hidden');
//   $('.CategoryProducts').addClass('Hidden');
// })
// $('.CategoryServicesTrigger').click(function(){
//   $('.CategoryVehicles').addClass('Hidden');
//   $('.CategoryRealState').addClass('Hidden');
//   $('.CategoryServices').removeClass('Hidden');
//   $('.CategoryProducts').addClass('Hidden');
// })
// $('.CategoryProductsTrigger').click(function(){
//   $('.CategoryVehicles').addClass('Hidden');
//   $('.CategoryRealState').addClass('Hidden');
//   $('.CategoryServices').addClass('Hidden');
//   $('.CategoryProducts').removeClass('Hidden');
// })

$(".BackToCategory").on('click',function(){
  $('.ProductDetails').addClass('Hidden');
  $('.CategoryMain').removeClass('Hidden');
});

$('.SelectCategory').click(function(){
  $('.CategoryMain').addClass('Hidden');
  $('.ProductDetails').removeClass('Hidden');
})

$('.ProductDescBtn').click(function(){
  $('.ProductDetails').addClass('Hidden');
  $('.ColorSizeStockMain').removeClass('Hidden');
})


/////////  DATE MASK ////////////

// $(function () {
//   //Datemask dd/mm/yyyy
//   $(".Datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
// });

// //Date picker
// $('.Datepicker').datepicker({
//   autoclose: true
// });
