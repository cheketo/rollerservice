// LOG OK MSG
//---------------------------------------------

function welcomeMessage()
{
  var Name = $("#userfullname").html().split(" ");
  $.notify({
        // options
        message: '<div class="txC"><img src="' + $(".img-circle").attr("src") + '" width="90" height="90" class="img-circle">' + "<br>" + "<br>" + '¡Bienvenido '+ Name[0] +'!</div>'
    },{
        // settings
        type: 'info',
        allow_dismiss: true,
        delay: 2000,
        placement: {
            from: "bottom",
            align: "right"
        }
    });
}

$(document).ready(function() {

  if(get['msg']=='logok')
  {
      welcomeMessage();
  }

  $('#meli_status').iCheck('disable');

});

$(function(){
  $("#melisync").click(function(){
    window.location = "process.meli.php";
  });

  $("#melinosync").click(function(){
    window.location = "process.meli.php?sync=no";
  });
});


/// Alert Demo ///
$('#alertDemoError').click(function(){
  notifyError();
});

$('#alertDemoSuccess').click(function(){
  notifySuccess();
});

$('#alertDemoInfo').click(function(){
  notifyInfo();
});

$('#alertDemoWarning').click(function(){
  notifyWarning();
});

$('.activateLoader').click(function(){
  toggleLoader();
});
