$(document).ready(function(){
	CloseProductWindow();
	CreateProduct();
	$("#BtnCreateProduct").click(function(){
		ShowProductWindow();
	});
});


///////// ABSTRACT FUNCTIONS
$(document).ready(function(){
    $("#abstract_new").click(function(){
      $("#new-abstract").removeClass("Hidden");
      $("#abstract-assoc").addClass("Hidden");
      $("#new_abstract").val("yes");
      $("#abstract_code").attr("validateEmpty","Ingrese un c&oacute;digo gen&eacute;rico");
      $("#abstract_code").attr("validateFromFile",process_url+"///El c&oacute;digo gen&eacute;rico ingresado ya existe///action:=validate///object:=ProductAbstract");
      
      if(!$("#abstract_code").val())
      {
        $("#abstract_code").val($("#new_product_code").val());
      }
      
    });
    
    $("#abstract_cancel").click(function(){
      $("#new-abstract").addClass("Hidden");
      $("#abstract-assoc").removeClass("Hidden");
      $("#new_abstract").val("");
      $("#abstract_code").attr("validateEmpty","");
      $("#abstract_code").attr("validateFromFile","");
      
    });
});


function ShowProductWindow()
{
	
	$("#ProductWindow").removeClass('Hidden');
}


function CreateProduct()
{
    $("#CreateProduct").click(function()
    {
        if(validate.validateFields('ProductWindowForm'))
	    {
            alertify.confirm(utf8_decode('¿Desea crear el art&iacute;culo <b>'+$("#new_product_code").val()+'</b>?'), function(e)
            {
                if(e)
                {
                    var string	=   'object=Product&action=Quickinsert&code='+$("#new_product_code").val()
                                    +'&brand='+$("#new_product_brand").val()
                                    +'&order_number='+$("#new_product_order_number").val()
                                    +'&category='+$("#new_product_category").val()
                                    +'&new_abstract='+$("#new_abstract").val()
                                    +'&abstract='+$("#abstract").val()
                                    +'&abstract_code='+$("#abstract_code").val()
                                    ;
                    $.ajax(
                    {
                        type: "POST",
                        url: process_url,
                        data: string,
                        cache: false,
                        success: function(data)
                        {
                            if(data)
                            {
                                notifyError("Ha ocurrido un error al intentar crear el art&iacute;culo.");
                                console.log(data);
                            }else{
                                notifySuccess("El art&iacute;culo <b>"+$("#new_product_code").val()+"</b> ha sido creado correctamente.");
                                $("#ProductWindow").addClass('Hidden');
		                        ResetProductForm();
                            }
                        }
                    });
                }
            });
	    }
    });
}

function CloseProductWindow()
{
	$(".CloseProductWindow").click(function(){
		$("#ProductWindow").addClass('Hidden');
		ResetProductForm();
	});
}

function ResetProductForm()
{
    $("#new_product_code").val('');
    $("#new_product_order_number").val('');
    $("#new_product_brand").val('0');
    $("#new_product_category").val('0');
}