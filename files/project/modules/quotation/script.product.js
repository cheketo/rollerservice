$(document).ready(function(){
	CloseProductWindow();
	CreateProduct();
	$("#BtnCreateProduct").click(function(){
		ShowProductWindow();
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
                    var string	= 'object=Product&action=Quickinsert&code='+$("#new_product_code").val()+'&brand='+$("#new_product_brand").val()+'&order_number='+$("#new_product_order_number").val()+'&category='+$("#new_product_category").val();
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