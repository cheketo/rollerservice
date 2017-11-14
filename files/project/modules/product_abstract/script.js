// ///////////////////////// ALERTS ////////////////////////////////////
$(document).ready(function(){
	if(get['msg']=='insert')
		notifySuccess('El art&iacute;culo gen&eacute;rico <b>'+get['element']+'</b> ha sido creado correctamente.');
	if(get['msg']=='update')
		notifySuccess('El art&iacute;culo gen&eacute;rico <b>'+get['element']+'</b> ha sido modificado correctamente.');
});

///////////////////////// CREATE/EDIT ////////////////////////////////////
$(function(){
	$("#BtnCreate").click(function(){
		var target		= 'list.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductAbstract','¿Desea crear el art&iacute;culo gen&eacute;rico <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnCreateNext").click(function(){
		var target		= 'new.php?element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductAbstract','¿Desea crear el art&iacute;culo gen&eacute;rico <b>'+$('#code').val()+'</b>?');
	});
	$("#BtnEdit").click(function(){
		var target		= 'list.php?relation_status=A&element='+$('#code').val()+'&msg='+ $("#action").val();
		askAndSubmit(target,'ProductAbstract','¿Desea modificar el art&iacute;culo gen&eacute;rico <b>'+$('#code').val()+'</b>?');
	});
	$("input").keypress(function(e){
		if(e.which==13){
			$("#BtnCreate,#BtnEdit").click();
		}
	});
});


$(document).ready(function(){
  deleteCodeRelation();
  clickAutocomplete();
});

///////// Select Product/Item ////////////////////////

$(function(){
    $('#asoc_data').on('click',function(){
      $('#asoc_data').addClass('Hidden');
      $('.AsociateProduct').removeClass('Hidden');
    });
  
    // $("#BtnAsoc").click(function(){
    //   var id = $("#asoc").val();
    //   if(id && checkCodeId(id))
    //   {
    //     var text = $("#TextAutoCompleteasoc").val().split(" - ").reverse();
    //     var stock = text[0];
    //     var category = text[1];
    //     var brand = text[2];
    //     var code = "";
    //     var i;
    //     for(i=3;i<text.length;i++)
    //     {
    //       if(text[i].trim())
    //       {
    //         if(code)
    //           code = text[i] + '-' +code;
    //         else
    //           code = text[i];
    //       }
    //     }
    //     var rowid = $("#CodeWrapper tr").length+1;
    //     $("#CodeWrapper").append('<tr id="tr'+rowid+'"><input type="hidden" code="'+code+'" class="CodeID" id="code_'+rowid+'" value="'+id+'"><td class="text-blue txC"><b>'+code+'</b></td><td class="txC">'+category+'</td><td class="txC"><span class="label label-default">'+brand+'</span></td><td class="txC"><button wrapper="tr'+rowid+'" type="button" class="DeleteCodeRelation btn btnRed hint--top hint--bounce hint--error" style="font-size:8px;" aria-label="Desasociar"><i class="fa fa-times"></i></button></td></tr>')
    //     deleteCodeRelation();
    //     $("#codes").val(rowid);
    //     $("#CodeBox").removeClass("Hidden");
    //     $("#asoc").val('');
    //     // $("#TextAutoCompleteasoc").val($("#prev_val").val());
    //     $("#TextAutoCompleteasoc").val(code);
    //     console.log(code);
    //     $("#TextAutoCompleteasoc").focus();
    //     $("#TextAutoCompleteasoc").keyup();
    //     $("#TextAutoCompleteasoc").change();
    //     // $("#TextAutoCompleteasoc").search(code);
    //   }
    // })
    
    // $("#TextAutoCompleteasoc").keypress(function(){
    //   $("#prev_val").val($(this).val());
    // })
});

function autocompleteOnSelectReplaceFunction(e,term,item)
{
  // var textval = item.data('key');
  // textval = textval.replace(/(<([^>]+)>)/ig,"");
  // $("#products").val(term);
  // var id = item.data('id');
  // if(id && checkCodeId(id))
  // {
  //   var text = textval.split(" - ").reverse();
  //   var stock = text[0];
  //   var category = text[1];
  //   var brand = text[2];
  //   var code = "";
  //   var i;
  //   for(i=3;i<text.length;i++)
  //   {
  //     if(text[i].trim())
  //     {
  //       if(code)
  //         code = text[i] + '-' +code;
  //       else
  //         code = text[i];
  //     }
  //   }
  //   var rowid = $("#CodeWrapper tr").length+1;
  //   $("#CodeWrapper").append('<tr id="tr'+rowid+'"><input type="hidden" code="'+code+'" class="CodeID" id="code_'+rowid+'" value="'+id+'"><td class="text-blue txC"><b>'+code+'</b></td><td class="txC">'+category+'</td><td class="txC"><span class="label label-default">'+brand+'</span></td><td class="txC"><button wrapper="tr'+rowid+'" type="button" class="DeleteCodeRelation btn btnRed hint--top hint--bounce hint--error" style="font-size:8px;" aria-label="Desasociar"><i class="fa fa-times"></i></button></td></tr>')
  //   deleteCodeRelation();
  //   $("#codes").val(rowid);
  //   $("#CodeBox").removeClass("Hidden");
  //   $("#products").blur();
    
  // }
  // $("#asoc").val(item.data('id'));
  // var textval = item.data('key');
  // textval = textval.replace(/(<([^>]+)>)/ig,"");
  // var id = item.data('id');
  // if(id && checkCodeId(id))
  // {
  //   var text = textval.split(" - ").reverse();
  //   var stock = text[0];
  //   var category = text[1];
  //   var brand = text[2];
  //   var code = "";
  //   var i;
  //   for(i=3;i<text.length;i++)
  //   {
  //     if(text[i].trim())
  //     {
  //       if(code)
  //         code = text[i] + '-' +code;
  //       else
  //         code = text[i];
  //     }
  //   }
  //   var rowid = $("#CodeWrapper tr").length+1;
  //   $("#CodeWrapper").append('<tr id="tr'+rowid+'"><input type="hidden" code="'+code+'" class="CodeID" id="code_'+rowid+'" value="'+id+'"><td class="text-blue txC"><b>'+code+'</b></td><td class="txC">'+category+'</td><td class="txC"><span class="label label-default">'+brand+'</span></td><td class="txC"><button wrapper="tr'+rowid+'" type="button" class="DeleteCodeRelation btn btnRed hint--top hint--bounce hint--error" style="font-size:8px;" aria-label="Desasociar"><i class="fa fa-times"></i></button></td></tr>')
  //   deleteCodeRelation();
  //   $("#codes").val(rowid);
  //   $("#CodeBox").removeClass("Hidden");
    // $("#TextAutoCompleteasoc").search(term);
    
    // $("#prev_val").val(term)
    // $("#TextAutoCompleteasoc").focus();
    // $("#TextAutoCompleteasoc").trigger('keyup');
    
    // $("#TextAutoCompleteasoc").change();
  // }
}

$("#asoc").autoComplete({
    minChars: 0,
    delay: 600,
    cache: false,
    source: function(term, response)
    {
      var target = "../../../core/resources/processes/proc.core.php";
      var object = "Product";
			var action = "SearchCodesForRelation";
      var variables		= "text="+term+"&object="+object+"&action="+action+"&category="+$("#category").val();
  	    
      
      try { xhr.abort(); } catch(e){}
      xhr = $.getJSON(target,variables, function(data){
        response(data);
      });
    },
    renderItem: function (item, search)
    {
      var key = item.text;
      var text = item.text;
      var id = item.id;
      if(key=="no-result")
      {
        var defaultSearchText = 'Sin resultados';
        key='';
        text='<i>'+defaultSearchText+'</i>'
      }
      return '<div class="autocomplete-suggestion" data-key="'+key+'" data-id="'+id+'" data-val="'+search+'">'+text+'</div>';
    },
    onSelect: function(e, term, item)
    {
        var textval = item.data('key');
        textval = textval.replace(/(<([^>]+)>)/ig,"");
        $("#asoc").val(term);
        var id = item.data('id');
        if(id && checkCodeId(id))
        {
          var text = textval.split(" - ").reverse();
          var stock = text[0];
          var category = text[1];
          var brand = text[2];
          var code = "";
          var i;
          for(i=3;i<text.length;i++)
          {
            if(text[i].trim())
            {
              if(code)
                code = text[i] + '-' +code;
              else
                code = text[i];
            }
          }
          var rowid = $("#CodeWrapper tr").length+1;
          $("#CodeWrapper").append('<tr id="tr'+rowid+'"><input type="hidden" code="'+code+'" class="CodeID" id="code_'+rowid+'" value="'+id+'"><td class="text-blue txC"><b>'+code+'</b></td><td class="txC">'+category+'</td><td class="txC"><span class="label label-default">'+brand+'</span></td><td class="txC"><button wrapper="tr'+rowid+'" type="button" class="DeleteCodeRelation btn btnRed hint--top hint--bounce hint--error" style="font-size:8px;" aria-label="Desasociar"><i class="fa fa-times"></i></button></td></tr>')
          deleteCodeRelation();
          $("#codes").val(rowid);
          $("#CodeBox").removeClass("Hidden");
          $("#asoc").blur();
          
        }
    }
});

function clickAutocomplete()
{
  // $("#products").mouseover(function(){
  //     if($("#products").val())
  //     {
  //       $("#products").keyup();
  //     }
  // });
  
}

function deleteCodeRelation()
{
  $(".DeleteCodeRelation").on("click",function(){
    var id = $(this).attr("wrapper");
    $("#"+id).remove();
    var codes = $("#CodeWrapper tr").length;
    //$("#codes").val(codes);
    if(codes<1)
      $("#CodeBox").addClass("Hidden");
  })
}

function checkCodeId(id)
{
  var valid = true;  
  $(".CodeID").each(function(){
    if($(this).val()==id)
    {
      var code = $(this).attr("code");
      valid = false;
      notifyError("El c&oacute;digo '"+code+"' ya se encuentra asociado al producto gen&eacute;rico.");
    }
  });
  return valid;
}