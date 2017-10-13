
$(document).ready(function() {
	if(get['error']=='login' || get['error']=='login#'){
		notifyError("Para ingresar a esta secci&oacute;n debe estar conectado.");
	}
	if(get['error']=='customer' || get['error']=='customer#'){
		notifyError("El cliente se encuentra deshabilitado por falta de pago.");
	}
});

// $(function () {
// 	$('input').iCheck({
// 		checkboxClass: 'icheckbox_square-blue',
// 		radioClass: 'iradio_square-blue',
// 		increaseArea: '20%' // optional
// 	});
// });

function sumbitLogin(){
	
		var rememberuser	= $("input:checked").val();
		var password 		= $("#password").val();
		var user			= $("#user").val();
		var target			= '../main/main.php?msg=logok';
		var errorLogin		= 'Verifique los datos ingresados.';
		var errorCustomer	= 'El cliente se encuentra deshabilitado por falta de pago.';
		var values			= 'user='+ user + '&password=' + password + '&loginaction=login&pagetarget=login' +  '&rememberuser=' + rememberuser;
		var	process			= process_url;
		$.ajax({
				type: "POST",
				url: process,
				data: values,
				cache: false,
				async: false,
				success: function(data){
					if(!data){
						document.location = target;
					}else{
						notifyError(errorLogin);
						console.log(data);
					}
				}
		});
	
}

$(function(){
	$(".ButtonLogin").click(function(){
		if(validate.validateFields('')){
			sumbitLogin();
		}
	});

	$("input").keypress(function(e){
		if(e.which==13){
			$(".ButtonLogin").click();
		}
	});

});
