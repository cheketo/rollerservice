                                            ////// JavaScript Document //////

////////////////// INITIALIZATION ///////////////////////
var process_url = "../../../core/resources/processes/proc.core.php";
$(document).ready(function(){
  // var sidebarMenu = getCookie("sidebarmenu");
  // if(sidebarMenu)
  // {
  //   $("body").addClass(sidebarMenu);
  // }
  setDatePicker();
  inputMask();
	chosenSelect();
	SetAutoComplete();
	closeWindow();
	if($("input[type='file']").length>0)
	{
	  CustomizedFilefield();
	}
});

///////// DATE PICKER //////////////////////
function datePicker(element)
{
  $(element).datepicker({
    autoclose:true,
    todayHighlight: true,
    language: 'es'
  });
}

function setDatePicker(element)
{
  if($(".datePicker").length>0)
  {
    $.fn.datepicker.dates['es'] = {
      days: ["Domingo", "Lunes", "Martes", "Miércoles", "Juves", "Viernes", "Sábado"],
      daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
      daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      today: "Hoy",
      clear: "Borrar",
      format: "dd/mm/yyyy",
      titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
      weekStart: 1
    };
    
    $(".datePicker").each(function(){
      datePicker(this);
    });
  }
	
}
//////////////////////// AUTOCOMPLETE ///////////////////////
function SetAutoComplete(selector,mode)
{
  if(typeof selector==="undefined")
  {
    selector = ".TextAutoComplete";
  }
  if(typeof mode==="undefined")
  {
    mode = "notags";
  }
	if($(selector).length>0)
	{
		$(selector).each(function(){
		  try {
		     $(this).destroy();
		  } catch (e) {}
			var id = $(this).attr('id').split("TextAutoComplete");
			
			if($(this).attr("cacheauto"))
			  var cache = ($(this).attr("cacheauto")=='true');
			if($(this).attr("iconauto")) var icon = $(this).attr("iconauto");
			else var icon = '';
			if($(this).attr("charsauto")) var minChars = parseInt($(this).attr("charsauto"));
			else var minChars = 1;
			if($(this).attr("placeholderauto")) var defaultSearchText = $(this).attr("placeholderauto");
			else var defaultSearchText = 'Sin resultados';
			AutoCompleteInput(id[1],cache,icon,minChars,defaultSearchText,mode)
		});
	}
}

var xhr;
function AutoCompleteInput(inputID,cache,icon,minChars,defaultSearchText,mode)
{
  if(typeof minChars==="undefined") {
    minChars = 1;
  }
  if(typeof cache==="undefined") {
    cache = false;
  }
  if(typeof defaultSearchText==="undefined") {
    defaultSearchText = 'Sin resultados';
  }
  if(typeof icon!=="undefined") {
    icon = '<i class="fa fa-'+icon+'"></i> ';
  }else{
  	icon = '';
  }
  
  $("#TextAutoComplete"+inputID).on('focusin', function(e){ if (!e.minChars) { $("#TextAutoComplete"+inputID).last_val = '\n'; $("#TextAutoCompleteasoc").trigger('keyup.autocomplete'); } });
  
	$("#TextAutoComplete"+inputID).autoComplete({
    minChars: minChars,
    delay: 600,
    cache: cache,
    // hideResults: false,
    source: function(term, response)
    {
      var target = $("#TextAutoComplete"+inputID).attr("targetauto");
      var object = $("#TextAutoComplete"+inputID).attr("objectauto");
			var action = $("#TextAutoComplete"+inputID).attr("actionauto");
      var variables		= "text="+term+"&object="+object+"&action="+action;
  	  var field;
      if($("#TextAutoComplete"+inputID).attr("varsauto")!=undefined)
  	  {
    		var properties	= $("#TextAutoComplete"+inputID).attr("varsauto").split('///');
    		for(var i=0;i<properties.length;i++)
    		{
    			field = properties[i].split(":=");
    			if(field[1])
    				variables	= variables + "&" + field[0] + "=" + field[1];
    			else
    				variables	= variables + "&" + properties[i] + "=" + $("#"+properties[i]).val();
    		}
  	  }
			
      $("#"+inputID).val('');
      $("#"+inputID).change();
      try { xhr.abort(); } catch(e){}
      xhr = $.getJSON(target,variables, function(data){
        // console.log(data);
        response(data);
        if (typeof autocompleteResponseFunction === "function") { 
            autocompleteResponseFunction();
        }
        $(".autocomplete-suggestion").click(function(){
          // console.log("entra");
        })
      });
    },
    renderItem: function (item, search)
    {
      var key = item.text;
      var text = icon+item.text;
      var id = item.id;
      if(key=="no-result")
      {
        key='';
        text='<i>'+defaultSearchText+'</i>'
      }
      return '<div class="autocomplete-suggestion" data-key="'+key+'" data-id="'+id+'" data-val="'+search+'">'+text+'</div>';
    },
    onSelect: function(e, term, item)
    {
      if (typeof autocompleteOnSelectBeforeFunction === "function") { 
          autocompleteOnSelectBeforeFunction(e,term,item);
      }
      
      if (typeof autocompleteOnSelectReplaceFunction === "function") { 
          autocompleteOnSelectReplaceFunction(e,term,item);
      }else{
        var textval = item.data('key');
        if(mode=="notags")
          textval = textval.replace(/(<([^>]+)>)/ig,"");
        $("#TextAutoComplete"+inputID).val(textval);
        $("#"+inputID).val(item.data('id'));
        $("#"+inputID).change();
      }
      if (typeof autocompleteOnSelectAfterFunction === "function") { 
          autocompleteOnSelectAfterFunction(e,term,item);
      }
    }
  });
	$("#TextAutoComplete"+inputID).focusout(function(){
	 // console.log(inputID);
	 // console.log($("#"+inputID).val());
    if(!$("#"+inputID).val())	$("#TextAutoComplete"+inputID).val('');
	});
	
	return false;
}

///////// CHOSEN FOR SELECT INPUTS ////////
function chosenSelect()
{
  if($('.chosenSelect').length>0)
  {
	  $('.chosenSelect').chosen({disable_search_threshold: 10,search_contains: true,max_shown_results:50});
	  $('select.chosenSelect').children("option[value=' ']").val('');
  }
}

///////// INPUT MASK FOR TEXT INPUTS /////////
function inputMask()
{
	if($(".inputMask").length>0)
	{
	  $(".inputMask").each(function(){
	    if(!$(this).inputmask("hasMaskedValue"))
	    {
	      $(this).inputmask();  //static mask  
	    }
	  })
	}
}

//////////////////////////////////////////////////// Notify //////////////////////////////////////////////////////
function notifyError(msgNotify)
{
    $.notify({
        // options
        message: '<div class="txC"><i class="fa fa-exclamation-circle"></i> '+msgNotify+'</div>'
    },{
        // settings
        type: 'danger',
        allow_dismiss: true,
        delay: 30000,
        placement: {
            from: "top",
            align: "center"
        }
    });
}

function notifySuccess(msgNotify)
{
    $.notify({
        // options
        message: '<div class="txC"><i class="fa fa-check-circle"></i><br>'+msgNotify+'</div>'
    },{
        // settings
        type: 'success',
        allow_dismiss: true,
        delay: 15000,
        placement: {
            from: "bottom",
            align: "left"
        }
    });
}

function notifyInfo(msgNotify)
{
    $.notify({
        // options
        message: '<div class="txC"><i class="fa fa-info-circle"></i><br>'+msgNotify+'</div>'
    },{
        // settings
        type: 'info',
        allow_dismiss: true,
        delay: 15000,
        placement: {
            from: "bottom",
            align: "left"
        }
    });
}

function notifyWarning(msgNotify)
{
    $.notify({
        // options
        message: '<div class="txC"><i class="fa fa-warning"></i><br>'+msgNotify+'</div>'
    },{
        // settings
        type: 'warning',
        allow_dismiss: true,
        delay: 30000,
        placement: {
            from: "bottom",
            align: "left"
        }
    });
}

function notifyMsg(typeMsg,msgNotify)
{
    $.notify({
        // options
        message: msgNotify
    },{
        // settings
        type: typeMsg
    });
}

/////////////////////////////////////////////////// Menu Sidebar //////////////////////////////////////////
$(function(){
  $('#SidebarToggle').click(function(){
    if($('body').hasClass('sidebar-collapse'))
    {
      setCookie("sidebarmenu",'', 365);
    }else{
      setCookie("sidebarmenu",'sidebar-collapse', 365);
    }
  });
});
/////////////////////////////////////////////////// iCheckbox /////////////////////////////////////////////
$(function(){
  $('.iCheckbox').iCheck({
    inheritID: true,
    cursor: true,
    checkboxClass: 'iCheckbox_changeable icheckbox_'+iCheckSkin(),
    radioClass: 'iRadio_changeable iradio_'+iCheckSkin()
    //increaseArea: '10%' // optional
  });
});

function iCheckSkin()
{

  switch(localStorage.getItem('skin'))
  {
    case "skin-green":
    case "skin-green-light":
      return "square-green";
    break;

    case "skin-red":
    case "skin-red-light":
      return "square-red";
    break;

    case "skin-purple":
    case "skin-purple-light":
      return "square-purple";
    break;

    case "skin-yellow":
    case "skin-yellow-light":
      return "square-orange";
    break;

    case "skin-blue":
    case "skin-blue-light":
      return "square-blue";
    break;

    case "skin-black":
    case "skin-black-light":
      return "square";
    break;

    default:
      return "square-grey";
    break;
  }
}

// function changeiCheckboxesSkin(iSkin)
// {
//   var newSkin = iCheckSkin(iSkin);
//
//   $(".iRadio_changeable").each(function(){
//     for(var i = 0; i < my_skins.length; i++)
//     {
//       $(this).removeClass("iradio_"+iCheckSkin(my_skins[i]));
//     }
//     $(this).addClass("iradio_"+newSkin);
//   });
//
//   $(".iCheckbox_changeable").each(function(){
//     for(var i = 0; i < my_skins.length; i++)
//     {
//       $(this).removeClass("icheckbox_"+iCheckSkin(my_skins[i]));
//     }
//     $(this).addClass("icheckbox_"+newSkin);
//   });
// }

/////////////////////////////////////// Change Skins ////////////////////////////////////////

var my_skins = [
  "skin-blue",
  "skin-black",
  "skin-red",
  "skin-yellow",
  "skin-purple",
  "skin-green",
  "skin-blue-light",
  "skin-black-light",
  "skin-red-light",
  "skin-yellow-light",
  "skin-purple-light",
  "skin-green-light"
];

setup();

/* Replace Skin */

function change_skin(cls) {
  $.each(my_skins, function (i) {
    $("body").removeClass(my_skins[i]);
  });

  $("body").addClass(cls);
  storeLocal('skin', cls);
  setCookie('renovatio-skin', cls, 365);
  return false;
}

/* Default Skin Configuration */

function setup() {
  var tmp = getLocal('skin');
  if (tmp && $.inArray(tmp, my_skins))
  {
    change_skin(tmp);
    //changeiCheckboxesSkin(tmp);
  }

  $("[data-skin]").on('click', function (e) {
    if($(this).hasClass('knob'))
      return;
    e.preventDefault();
    change_skin($(this).data('skin'));
    //changeiCheckboxesSkin($(this).data('skin'));
  });
}

//////////////////////////////////////////////////// Submit Data //////////////////////////////////////////////////////
function submitData()
{
    var formFiles;
    var checkValue;
    var checkID;
    var elementID;
    var checkbox    = [];
    var checkboxID  = [];
    var variables   = [];
    var data        = new FormData();
    var i           = 0;
    var element;
    var id;
    //tinyMCE.triggerSave(); // Save trigger for TinyMCE editor
    $('textarea,select,input[type!="checkbox"]').each(function()
    {
        elementID   = $(this).attr("id");
        if($(this).attr("type")=="file")
        {
            formFiles       = document.getElementById(elementID).files;
            element = {id:elementID,value:formFiles[0]}
            variables[variables.length] = element;
        }else{
            element = {id:elementID,value:$(this).val()};
            variables[variables.length] = element;
        }

    });

    $('input[type="checkbox"]:checked').each(function()
    {
        checkID = $(this).attr("id");
        if(checkboxID.indexOf(checkID)==-1)
        {
            checkboxID[checkboxID.length] = checkID;
            checkValue="";
            $('input[type="checkbox"][name="'+checkID+'"]:checked').each(function()
            {
                if(checkValue!="")
                {
                    checkValue = checkValue + "," + $(this).val();
                }else{
                    checkValue = $(this).val();
                }
            });
            //notifyError(checkValue);
            variables[variables.length] = {id:checkID,value:checkValue};
        }
    });

    while(element= variables[i++])
    {
      data.append(element.id,element.value);
    }
    return data;
}

function sumbitFields(process,haveData,noData){
    var data    = submitData();
    $.ajax({
        url: process,
        type:'POST',
        contentType:false,
        data:data,
        processData:false,
        cache:false,
        async:true,
        success: function(rs){
            if(rs)
            {
                haveData(rs);
            }else{
                noData();
            }
        }
    });
}

function askAndSubmit(target,object,qtext="¿Desea guardar la informaci&oacute;n?",etext="Ha ocurrido un error en el proceso de guardado.",form='*')
{
	if(validate.validateFields(form))
	{
		alertify.confirm(qtext, function(e){
			if(e)
			{
				var process		= process_url+'?object='+object;
				var haveData	= function(returningData)
				{
					$("input,select").blur();
					notifyError(etext);
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
}

///////////////////////////////////////////////////// Attach a Selector //////////////////////////////////////////////////
$(function(){
    $("select,input,textarea").change(function(){
        var attach = $(this).attr("attach");
        if(attach){
            var string = 'value=' + $(this).val();
            var data = attach.split("/");
            var target = $("#"+data[0]);
            var process = data[1];
            var noData = target.attr("default");
            if(target.prop("tagName")=="SELECT") noData = '<option value="' + target.attr("firstvalue") + '">' + target.attr("firsttext") + '</option>';
            $.ajax({
                url: process,
                type:'POST',
                contentType:false,
                data:string,
                processData:false,
                cache:false,
                success: function(rs){
                    if(rs)
                    {
                        target.html(rs);
                    }else{
                        target.html(noData);
                    }
                }
            });
        }

    });
});

//////////////////////////////////////////////////// Validation ///////////////////////////////////////////////////////////////
var validate    = new ValidateFields();
$(function(){
    validateDivChange();
});
function validateDivChange()
{
  validate.createErrorDivs();
  $(validateElements).change(function(){
        validate.validateOneField(this);
    });
}
//////////////////////////////////////////////////// Logout ////////////////////////////////////////////////////
$(function(){
  $("#Logout").click(function(){
      alertify.confirm("¿Desea salir?", function(e){
            if(e){
              var target      = '../../../core/modules/login/login.php';
              var process     = process_url+'?object=CoreLogin&action=logout';
              $.ajax({
                  type: "POST",
                  url: process,
                  cache: false,
                  success: function(){
                      document.location = target;
                  }
              });
          }
      }).set('labels', {ok:'Si', cancel:'No'});
  });
});

//////////////////////////////////////////////////// Value In Array ////////////////////////////////////////////////////
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle || (Array.isArray(haystack[i]) && inArray(needle,haystack[i])))
            return true;
    }
    return false;
}


//////////////////////////////////////////////////// Element Visible ////////////////////////////////////////////////////////

function isVisible(object)
{
    return $(object).is (':visible') && $(object).parents (':hidden').length == 0;
}

//////////////////////////////////////////////////// Hide/Show Element by Class ////////////////////////////////////////////////////////

function showElement(element)
{
    $(element).removeClass('Hidden');
}

function hideElement(element)
{
    $(element).addClass('Hidden');
}

function toggleElement(element)
{
    if($(element).hasClass('Hidden'))
    {
        $(element).removeClass('Hidden');
    }else{
        $(element).addClass('Hidden');
    }
}


//////////////////////////////////////////////////// Get Vars From URL ////////////////////////////////////////////////////
function getVars(){
    var loc = document.location.href;
    var getString = loc.split('?');
    if(getString[1]){
        var GET = getString[1].split('&');
        var get = {};//This object will be filled with the key-value pairs and returned.

        for(var i = 0, l = GET.length; i < l; i++){
            var tmp = GET[i].split('=');
            get[tmp[0]] = unescape(decodeURI(tmp[1]));
        }
        return get;
    }else{
        return "";
    }
}
var get = getVars();


//////////////////////////////////////////////////// UTF8_ENCODE ////////////////////////////////////////////////////
function utf8_encode (argString) {

  if (argString === null || typeof argString === "undefined") {
    return "";
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
         (c1 >> 6)        | 192,
        ( c1        & 63) | 128
      );
    } else if (c1 & 0xF800 != 0xD800) {
      enc = String.fromCharCode(
         (c1 >> 12)       | 224,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    } else { // surrogate pairs
      if (c1 & 0xFC00 != 0xD800) { throw new RangeError("Unmatched trail surrogate at " + n); }
      var c2 = string.charCodeAt(++n);
      if (c2 & 0xFC00 != 0xDC00) { throw new RangeError("Unmatched lead surrogate at " + (n-1)); }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
         (c1 >> 18)       | 240,
        ((c1 >> 12) & 63) | 128,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}


//////////////////////////////////////////////////// UTF8_DECODE ////////////////////////////////////////////////////
function utf8_decode (str_data) {

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      // http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1>>10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }

  return tmp_arr.join('');
}


///////////////////////////////////// COOKIES ////////////////////////////////////
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
//////////////////////////// COOKIE EXAMPLE //////////////////
// function checkCookie() {
//     var user = getCookie("username");
//     if (user != "") {
//         alert("Welcome again " + user);
//     } else {
//         user = prompt("Please enter your name:", "");
//         if (user != "" && user != null) {
//             setCookie("username", user, 365);
//         }
//     }
// }


/////////////////////////////////////////////// Local Storage ///////////////////////////////////////////

function storeLocal(name, val) {
  if (typeof (Storage) !== "undefined") {
    localStorage.setItem(name, val);
  } else {
    window.alert('Please use a modern browser to properly view this template!');
  }
}

function getLocal(name) {
  if (typeof (Storage) !== "undefined") {
    return localStorage.getItem(name);
  } else {
    window.alert('Please use a modern browser to properly view this template!');
  }
}

//////////////////////////////////////// LOADER ////////////////////////////////////////
$(document).ajaxStart(function(){
    $("#CloseAjaxLoader").addClass('Hidden');
    // $(".loader").removeClass("Hidden");
    // $('html').css({ 'overflow': 'hidden', 'height': '100%' });
    showLoader();
});

$(document).ajaxComplete(function(){
    // $(".loader").addClass("Hidden");
    // $('html').css({ 'overflow-Y': 'scroll', 'height': '100%' });
    chosenSelect();
    inputMask();
    hideLoader();
    // $("#CloseAjaxLoader").addClass('Hidden');
});

function toggleLoader()
{
  // $('.loader').toggleClass('Hidden');
  // $("#CloseAjaxLoader").addClass('Hidden');
  //   if (!$('.loader').hasClass('Hidden')) {
  //     // This prevents scroll on loader
  //     setTimeout(function() {
  //       $("#CloseAjaxLoader").removeClass('Hidden');
  //     },10000);
  //     $('html').css({ 'overflow': 'hidden', 'height': '100%' });
  //   } else {
  //     $('html').css({ 'overflow-Y': 'scroll', 'height': '100%' });
  //   }
  if($(".loader").hasClass('Hidden'))
  {
    showLoader();
  }else{
    hideLoader();
  }
}

function showLoader()
{
  $('.loader').removeClass('Hidden');
  $("#CloseAjaxLoader").addClass('Hidden');
  setTimeout(function() {
    $("#CloseAjaxLoader").removeClass('Hidden');
  },10000);
  $('html').css({ 'overflow': 'hidden', 'height': '100%' });
}

function hideLoader()
{
  $('.loader').addClass('Hidden');
  $("#CloseAjaxLoader").addClass('Hidden');
  $('html').css({ 'overflow-Y': 'scroll', 'height': '100%' });
}


$(function(){
  $("#CloseAjaxLoader").click(function(){
    toggleLoader();
  })
})

//////////////////////////////// CANCEL BUTTON /////////////////////////////////
$(function(){
	$("#BtnCancel").click(function(){
		window.history.back();
	});
});

/////////////////////// MONEY FORMAT /////////////////
Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 ///(123456789.12345).formatMoney(2);
 
 ////////////////////////////// ADD DAYS TO A DATE //////////////////////////////////////////////
function AddDaysToDate(days,adate)
{
  if(typeof(adate)==="undefined")
    adate = new Date();
  else
    adate = new Date(adate);
  var finaldate = adate.getTime()+parseInt(days)*24*60*60*1000;
  finaldate = new Date(finaldate);
  var day = finaldate.getUTCDate()
  if(day<10) day = "0"+day;
  var month = finaldate.getUTCMonth()+1;
  if(month<10) month = "0"+month;
  return day+"/"+month+"/"+finaldate.getUTCFullYear();
}
/////////////////////////// WINDOWS /////////////////////////////
function closeWindow()
{
  $(".window .window-border .window-close").click(function(){
    $(this).parent().parent().parent().parent().addClass("Hidden");
  });
}

//////////////////////////////////////////////////// Customized File Field ////////////////////////////////////////////////////
function CustomizedFilefield()
{
	$("input:file").change(function(){
		$("#File"+$(this).attr("id")).focus();
		$("#File"+$(this).attr("id")).val($(this).val().replace("C:\\fakepath\\",""));
		$("#File"+$(this).attr("id")).blur();
	});
	$(".CustomizedFileField").click(function(){
		if($(this).attr("id").substring(0,4)=="File"){
			$(this).blur();
			$("#"+$(this).attr("id").substring(4)).click();
		}
	});
}

//////////////////////////////////////////////////// Get File Icon by file extension ////////////////////////////////////////////////////
function GetFileIcon(ext)
{
  var url = "../../../../skin/images/body/icons/";
  switch (ext) {
    case 'pdf':
      return url+"pdf.png";
    break;
    
    case 'avi':
    case 'mp4':
      return url+"avi.png";
    break;
    
    case 'wav':
    case 'mp3':
      return url+"mp3.png";
    break;
    
    case "doc":
		case "dot":
		case "docx":
		case "docm":
		case "dotx":
		case "dotm":
      return url+"doc.png";
    break;
    
    case "xls":
		case "xlsx":
		case "xlt":
		case "xltx":
		case "csv":
      return url+"xls.png";
    break;
    
    case 'rar':
    case 'zip':
      return url+"rar.png";
    break;
    
    case "ppt":
		case "pot":
		case "pps":
      return url+"ppt.png";
    break;
    
    case "bmp":
		case "jpeg":
		case "jpg":
		case "png":
		  return "self";
		break;
    
    default: return url+"txt.png";
  }
}