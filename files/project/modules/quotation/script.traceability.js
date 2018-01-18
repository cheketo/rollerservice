if($('#DropzoneContainer').length)
{
    Dropzone.options.myAwesomeDropzone = false;
    // Disable auto discover for all elements:
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#DropzoneContainer",{
        url: process_url+"?object=Quotation&action=Addnewfile",
        dictDefaultMessage:"Para subir un archivo haga click o arrastrelo hasta aqu&iacute;.",
        // addRemoveLinks: true,
        // dictRemoveFile:"Eliminar",
        // dictRemoveFileConfirmation:"¿Desea eliminar este archivo?",
        // dictCancelUploadConfirmation:"¿Desea eliminar este archivo?",
        createImageThumbnails:true,
        thumbnailWidth:120,
        thumbnailHeight:107,
        accept: function(file, done) {
            
            var thumbnail = $('.dropzone .dz-preview.dz-file-preview .dz-image:last').children("img");
     
            switch (file.type)
            {
                case 'application/pdf':
                    thumbnail.attr('src',GetFileIcon("pdf","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    thumbnail.attr('src',GetFileIcon("doc","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    thumbnail.attr('src',GetFileIcon("xls","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/vnd.ms-excel':
                    thumbnail.attr('src',GetFileIcon("xls","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/zip, application/x-compressed-zip':
                    thumbnail.attr('src',GetFileIcon("zip","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/vnd.ms-powerpointtd>':
                    thumbnail.attr('src',GetFileIcon("ppt","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                    thumbnail.attr('src',GetFileIcon("ppt","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'application/octet-stream':
                    thumbnail.attr('src',GetFileIcon("eml","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
                break;
                case 'image/jpeg':
                break;
                case 'image/png':
                break;
                default:
                    thumbnail.attr('src',GetFileIcon("txt","big"));
                    thumbnail.attr("width","100%");
                    thumbnail.attr("height","100%");
         }
            // if (!file.type.match(/image.*/)) {
            //     this.createThumbnailFromUrl(file, GetFileIcon("doc","big"));
            // }
            done();
        }
    });
    
    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("product", $("#product").val());
    });
    myDropzone.on("addedfile", function(file) {
        // Create the remove button
        var removeButton = Dropzone.createElement('<button id="lastremovebutton" class="btn btn-danger" style="margin-top:5px;cursor: pointer;"><i class="fa fa-times"></i></button>');
        // Capture the Dropzone instance as closure.
        var _this = this;
        // Listen to the click event
        removeButton.addEventListener("click", function(e) {
          // Make sure the button click doesn't submit the form:
            e.preventDefault();
            e.stopPropagation();
            DeleteFileFromWrapper($(this));
          // Remove the file preview.
            // _this.removeFile(file);
          // If you want to the delete the file on the server as well,
          // you can do the AJAX request here.
        });
        // Add the button to the file preview element.
        file.previewElement.appendChild(removeButton);
    });
    myDropzone.on("success", function(event,data) {
        try
        {
        data = JSON.parse(data);
        }
        catch(e)
        {
            notifyError("Ha ocurrido un error al intentar subir el archivo seleccionado.");
            console.log("Error: "+data);
        }
        if(data.id)
        {
            var count = parseInt($("#filecount").val())+1;
            // var FileURLarray = data.url.split(".");
            // var FileIconURL = GetFileIcon(FileURLarray[FileURLarray.length-1]);
            // if(FileIconURL == 'self')
            // {
            //     FileIconURL = data.url;
            // }
            // notifySuccess("El archivo <b>"+data.name+"</b> ha sido cargado correctamente");
            var NewFileHTML = //'<div class="col-md-4 col-sm-6 col-xs-12 txC FileInfoDiv" style="margin-top:10px;" fid="'+data.id+'" id="tfile_'+count+'" filename="'+data.name+'" fileurl="'+data.url+'">'+
            //                     '<span class="btn btn-danger DeleteFileFromWrapper" style="padding:0px 3px;"><i class="fa fa-times"></i></span>'+
            //                     '&nbsp;<img src="'+FileIconURL+'" height="32" width="32"> <a href="'+data.url+'" target="_blank">'+data.name+'</a>'+
                                '<input type="hidden" id="fileid_'+count+'" value="'+data.id+'" >';
                            //   '</div>';
            $("#FileWrapper").append(NewFileHTML);
            // $("#Filetfile").val('');
            $("#filecount").val(count);
            
            var HTMLRemoveBtn = $('#lastremovebutton');
            HTMLRemoveBtn.addClass('DeleteFileFromWrapper');
            HTMLRemoveBtn.attr("id",'tfile_'+count);
            HTMLRemoveBtn.attr("fid",data.id);
            HTMLRemoveBtn.attr("filename",data.name);
            HTMLRemoveBtn.attr("fileurl",data.url);
            
            // $('#tfile_'+count).on("click", function(e) {
            // e.preventDefault();
            // e.stopPropagation();
            // DeleteFileFromWrapper($('#tfile_'+count));
            // });
            
            // DeleteFileFromWrapper();
            
        }
    });
    myDropzone.on("drop",function(file){
        $(".dz-default").hide();
    });
    myDropzone.on("complete",function(file){
        if($(".DeleteFileFromWrapper").length==0)
        {
            $(".dz-default").show();
        }
    });
}


$(function(){
    TraceabilityFunctions();
});

function TraceabilityFunctions()
{
    // DeleteFileFromWrapper();
    SaveNewQuotation();
    // UploadQuotationFile();
    CloseTraceabilityWindow();
    ToggleBox();
}


///////////////////////////////// New Quotation Form Functions /////////////////////////////////
function DeleteFileFromWrapper(btn)
{
    // $(".DeleteFileFromWrapper").click(function(e){
        // e.preventDefault();
        // e.stopPropagation();
        var filewrapper = btn;
        var filename = filewrapper.attr('filename');
        alertify.confirm("¿Desea eliminar el archivo <b>"+filename+"</b>?", function(e){
        if(e){
            var file = filewrapper.attr('fid');
            var elemid=filewrapper.attr('id');
            var process     = process_url+'?object=Quotation&action=Removenewfile&fid='+file;
            $.ajax({
                type: "POST",
                url: process,
                cache: false,
                success: function(data)
                {
                    if(data)
                    {
                        notifyError("Ha ocurrido un error al intentar borrar el archivo seleccionado.");
                        console.log(data);
                    }else{
                        notifySuccess("El archivo <b>"+filename+"</b> ha sido eliminado correctamente");
                        // console.log(myDropzone.getAcceptedFiles());
                        var files = myDropzone.getAcceptedFiles();
                        for(i=0;i<files.length;i++)
                        {
                            
                            var FileHTML = files[i].previewElement;
                            if(FileHTML.childNodes[12].attributes[0].value==elemid)
                                FileHTML.parentNode.removeChild(files[i].previewElement);
                        }
                        // myDropzone.files.each(function(file){
                        //   if(file.previewElement.children("#tfile_"+fileid).length>0)
                        //     myDropzone.removeFile(file);
                        // });
                        files = myDropzone.getAcceptedFiles();
                        if($(".DeleteFileFromWrapper").length==0)
                        {
                            $(".dz-default").show();
                        }
                    }
                }
            });
        }
        });
    // })
}


// function UploadQuotationFile()
// {
//     $("#tfile").change(function(){
//         if($("#tfile").val())
//         {
//             var MainAction = $("#action").val();
//             $("#action").val('Addnewfile');
//             var process     = process_url+'?object=Quotation';
//             var haveData = function(data)
//             {
//                 // console.log(data);
//                 try
//                 {
//                 data = JSON.parse(data);
//                 }
//                 catch(e)
//                 {
//                     notifyError("Ha ocurrido un error al intentar borrar el archivo seleccionado.");
//                     console.log("Error: "+data);
//                 }
//                 if(data.id)
//                 {
//                     var count = parseInt($("#filecount").val())+1;
//                     var FileURLarray = data.url.split(".");
//                     var FileIconURL = GetFileIcon(FileURLarray[FileURLarray.length-1]);
//                     if(FileIconURL == 'self')
//                     {
//                         FileIconURL = data.url;
//                     }
//                     // notifySuccess("El archivo <b>"+data.name+"</b> ha sido cargado correctamente");
//                     var NewFileHTML = '<div class="col-md-4 col-sm-6 col-xs-12 txC FileInfoDiv" style="margin-top:10px;" fid="'+data.id+'" id="tfile_'+count+'" filename="'+data.name+'" fileurl="'+data.url+'">'+
//                                         '<span class="btn btn-danger DeleteFileFromWrapper" style="padding:0px 3px;"><i class="fa fa-times"></i></span>'+
//                                         '&nbsp;<img src="'+FileIconURL+'" height="32" width="32"> <a href="'+data.url+'" target="_blank">'+data.name+'</a>'+
//                                         '<input type="hidden" id="fileid_'+count+'" value="'+data.id+'" >'+
//                                       '</div>';
//                     $("#FileWrapper").append(NewFileHTML);
//                     $("#Filetfile").val('');
//                     $("#filecount").val(count);
//                     DeleteFileFromWrapper();
                    
//                 }
//             }
//             var noData = function()
//             {
//                 notifyError("Ha ocurrido un error al intentar borrar el archivo seleccionado.");
//                 console.log("Sin error asociado");    
//             }
//             sumbitFields(process,haveData,noData);
//             $("#action").val(MainAction);
//         }
//     })
// }

function CloseTraceabilityWindow()
{
    $(".BtnWindowClose").click(function(){
        $("#window_traceability").addClass('Hidden');
        // ClearTraceabilityWindow();
        //$("#last_product").val($("#product").val());
        $(".ClearWindow").remove();
    });
}

function ClearTraceabilityWindow()
{
    $(".ClearWindow").each(function(){
        $(this).remove();
    });
    ClearNewQuotationForm();
}

function FillTraceabilityWindow()
{
    // if($("#product").val()!=$("#last_product").val())
    // {
    $(".QuotationDeleteable").remove();
    ClearTraceabilityWindow();
    SetTraceabilityProductName();
    FillProviderQuotations();
    FillCustomerQuotations();
    // }
}

function SetTraceabilityProductName()
{
    var item = $("#item").val();
    var name = $("#TextAutoCompleteitem_"+item).val();
    // var name = $("#TextAutoCompleteitem_"+item).val().split("-").reverse();
    // for(i=0;i<count(name);i++)
    // {
    //     if(i>2)
    //     {
            
    //     }
    // }
    $("#ProductName").html('<kbd>'+name+'</kbd>');
}

function FillProviderQuotations()
{
    var MainAction = $("#action").val();
    $("#action").val('Fillproviderquotations');
    var process     = process_url+'?object=Quotation';
    var haveData = function(data)
    {
        $("#QuotationWrapper").children("tbody").append(data);
        // notifySuccess("La cotizaci&oacute;n ha sido cargado correctamente");
        // console.log(data);
    }
    var noData = function()
    {
        // notifyError("Ha ocurrido un error al obtener las cotizaciones de este producto.");
        var HTML = '<tr class="QuotationDeleteable"><td colspan="8"><div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron cotizaciones de proveedores para este art&iacute;culo.</h4><p>Puede crear una nueva cotizaci&oacute;n completando los campos de "Nueva Cotizaci&oacute;n de Proveedor"</p></div></td></tr>';
        $("#QuotationWrapper").children("tbody").append(HTML);
    }
    sumbitFields(process,haveData,noData);
    $("#action").val(MainAction);
}

function FillCustomerQuotations()
{
    if(get['customer']=='Y' && $("#company").val())
    {
        var MainAction = $("#action").val();
        $("#action").val('Fillcustomerquotations');
        var process     = process_url+'?object=Quotation';
        var haveData = function(data)
        {
            $("#CustomerQuotationWrapper").children("tbody").append(data);
            // notifySuccess("La cotizaci&oacute;n ha sido cargado correctamente");
            // console.log(data);
        }
        var noData = function()
        {
            // notifyError("Ha ocurrido un error al obtener las cotizaciones de este producto.");
            var HTML = '<tr class="QuotationDeleteable"><td colspan="8"><div class="callout callout-info"><h4><i class="icon fa fa-info-circle"></i> No se encontraron cotizaciones anteriores del cliente para este art&iacute;culo.</h4></div></td></tr>';
            $("#CustomerQuotationWrapper").children("tbody").append(HTML);
        }
        sumbitFields(process,haveData,noData);
        $("#action").val(MainAction);
    }
}

function ToggleBox()
{
    $(".QuotationBoxTitle").click(function(e){
        e.stopImmediatePropagation();
        e.preventDefault();
        $(this).parent().children('.box-tools').children('button').click();
        $(this).parent().children('.box-tools').children('.input-group').children('.input-group-btn').children('button').click();
        
        e.stopImmediatePropagation();
        return false;
    })
}

///////////////////////////////// Save New Quotation /////////////////////////////
var NewQuotationData;
function SaveNewQuotation()
{
    $("#BtnSaveQuotation").click(function(){
        if(validate.validateFields('tform'))
        {
            alertify.confirm("¿Desea ingresar una nueva cotizaci&oacute;n?", function(e){
            if(e)
            {
                CreateNewQuotation();
                // $("#CollapseNewForm").click();
                // $("#CollapseQuotations").click();
            }
            });
        }
    });
}

function ClearNewQuotationForm()
{
    if(get['customer']=='Y')
    {
        $("#tform input,#tform textarea").each(function(){
            $(this).val('');
        });
        myDropzone.removeAllFiles(true);
        $("#FileWrapper").html('');
        $("#filecount").val("0");
        validate.createErrorDivs();
    }
}

function CreateNewQuotation()
{
    var MainAction = $("#action").val();
    $("#action").val('Newquotation');
    var process     = process_url+'?object=Quotation';
    var haveData = function(data)
    {
        try
        {
            NewQuotationData =  JSON.parse(data);
        }
        catch(e)
        {
            notifyError("Ha ocurrido un error al intentar borrar el archivo seleccionado.");
            console.log(data);
        }
        InsertQuotationHTML(NewQuotationData);
        ClearNewQuotationForm();
    }
    var noData = function()
    {
        notifyError("Ha ocurrido un error al intentar borrar el archivo seleccionado.");
        console.log("No data");
    }
    sumbitFields(process,haveData,noData);
    $("#action").val(MainAction);
}

function InsertQuotationHTML(data)
{
    var FilesHTML = '';
    $(".DeleteFileFromWrapper").each(function(){
        var FileURLarray = $(this).attr("fileurl").split("/");
        var File = FileURLarray[FileURLarray.length-1];
        var FileURL = data.filepath+File;
        var FileNameArray = File.split(".");
        var FileIconURL = GetFileIcon(FileNameArray[FileNameArray.length-1]);
        if(FileIconURL == 'self')
        {
            FileIconURL = FileURL;
        }
        FilesHTML = FilesHTML + '<div><a href="'+FileURL+'" target="_blank"><img src="'+FileIconURL+'" height="32" width="32"> '+$(this).attr("filename")+'</a></div>';
    })
    var total = (parseFloat($("#tprice").val())*parseInt($("#tquantity").val())).toFixed(2);
    var HTML = '<tr class="ClearWindow">'+
                '<td>'+$("#tdate").val()+'</td>'+
                '<td>'+$("#TextAutoCompletetprovider").val()+'</td>'+
                '<td><span class="label label-success">'+data.currency+$("#tprice").val()+'</span></td>'+
                '<td>'+$("#tquantity").val()+'</td>'+
                '<td>'+data.currency+' '+total+'</td>'+
                '<td>'+$("#tday").val()+' D&iacute;as</td>'+
                '<td>'+$("#textra").val()+'</td>'+
                '<td>'+FilesHTML+'</td>'+
              '</tr>';
    $(".QuotationDeleteable").remove();
    $("#QuotationWrapperTh").after(HTML);
}