if($('#DropzoneQuotation').length)
{
    Dropzone.options.myAwesomeDropzone = false;
    Dropzone.autoDiscover = false;
    var mainQuotationDropzone = new Dropzone("#DropzoneQuotation",{
        url: process_url+"?object=Quotation&action=Addnewfile",
        dictDefaultMessage:"Para subir un archivo haga click o arrastrelo hasta aqu&iacute;.",
        createImageThumbnails:true,
        thumbnailWidth:120,
        thumbnailHeight:107,
        accept: function(file, done) {
            
            var thumbnail = $('#DropzoneQuotation .dz-preview.dz-file-preview .dz-image:last').children("img");
     
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
    
    mainQuotationDropzone.on("sending", function(file, xhr, formData) {
        // formData.append("product", $("#product").val());
    });
    mainQuotationDropzone.on("addedfile", function(file) {
        var removeButton = Dropzone.createElement('<button id="lastremovebuttonq" class="btn btn-danger d-inline" style="cursor: pointer;"><i class="fa fa-times"></i></button>');
        var linkButton = Dropzone.createElement('<a id="lastlinkq" class="btn btn-primary d-inline" target="_blank" style="margin-left:5px;cursor: pointer;"><i class="fa fa-download"></i></a>');
        var _this = this;
        removeButton.addEventListener("click", function(e)
        {
            e.preventDefault();
            e.stopPropagation();
            DeleteQuotationFileFromWrapper($(this));
        });
        
        // linkButton.addEventListener("click", function(e)
        // {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     FileLinkClick($(this));
        // });
        file.previewElement.appendChild(removeButton);
        file.previewElement.appendChild(linkButton);
    });
    mainQuotationDropzone.on("success", function(event,data) {
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
            var count = parseInt($("#qfilecount").val())+1;
            var NewFileHTML = '<input type="hidden" id="qfileid_'+count+'" value="'+data.id+'" >';
            $("#QFileWrapper").append(NewFileHTML);
            $("#qfilecount").val(count);
            
            var HTMLRemoveBtn = $('#lastremovebuttonq');
            HTMLRemoveBtn.addClass('DeleteQuotationFileFromWrapper');
            HTMLRemoveBtn.attr("id",'qfile_'+count);
            HTMLRemoveBtn.attr("fid",data.id);
            HTMLRemoveBtn.attr("filename",data.name);
            HTMLRemoveBtn.attr("fileurl",data.url);
            
            $('#lastlinkq').attr("href",data.url);
            $('#lastlinkq').attr("id","qlink_"+count);
        }
    });
    mainQuotationDropzone.on("drop",function(file){
        $("#DropzoneQuotation .dz-default").hide();
    });
    mainQuotationDropzone.on("complete",function(file){
        if($(".DeleteQuotationFileFromWrapper").length==0)
        {
            $("#DropzoneQuotation .dz-default").show();
        }else{
            $("#DropzoneQuotation .dz-default").hide();
        }
    });
}

$(document).ready(function(){
    if($("#id").length && !isNaN($("#id").val()))
    {
        
        var process = process_url+'?object=Quotation&action=Getquotationfiles&quotation='+$("#id").val();
        $.ajax({
            type: "POST",
            url: process,
            cache: false,
            success: function(data)
            {
                try
                {
                data = JSON.parse(data);
                }
                catch(e)
                {
                    notifyError("Ha ocurrido un error al intentar subir el archivo seleccionado.");
                    console.log("Error: "+data);
                }
                if(data)
                {
                    $.each(data, function(key,value)
                    {
                        if(value.size>0)
                        {
                            var mockFile = { name: value.full_name, size: value.size };
                            mainQuotationDropzone.emit("addedfile", mockFile);
                            mainQuotationDropzone.emit("complete", mockFile);
                            
                            var count = parseInt($("#efilecount").val())+1;
                            var NewFileHTML = '<input type="hidden" id="efileid_'+count+'" value="'+value.id+'" >';
                            $("#QFileWrapper").append(NewFileHTML);
                            $("#efilecount").val(count);
                            
                            var HTMLRemoveBtn = $('#lastremovebuttonq');
                            HTMLRemoveBtn.addClass('DeleteQuotationFileFromWrapper');
                            HTMLRemoveBtn.attr("id",'efile_'+count);
                            HTMLRemoveBtn.attr("fid",value.id);
                            HTMLRemoveBtn.attr("filename",value.full_name);
                            HTMLRemoveBtn.attr("fileurl",value.url);
                            
                            $('#lastlinkq').attr("href",value.url);
                            $('#lastlinkq').attr("id","qlink_"+count);
                            
                            var imageurl = value.url;
                            
                            var thumbnail = $('#DropzoneQuotation .dz-preview.dz-file-preview .dz-image:last').children("img");
                            if(value.type!="jpg" && value.type!="png" && value.type!="bmp" && value.type!="jpeg")
                            {
                                thumbnail.attr('src',GetFileIcon(value.type,"big"));
                                thumbnail.attr("width","100%");
                                thumbnail.attr("height","100%");
                            }else{
                                thumbnail.attr('src',value.url);
                                thumbnail.attr("width","100%");
                                thumbnail.attr("height","100%");
                            }
                        }
                    });
                    $("#DropzoneQuotation .dz-default").hide();
                }
            }
        });
    }
});


///////////////////////////////// FilesFunctions /////////////////////////////////
function DeleteQuotationFileFromWrapper(btn)
{
        var filewrapper = btn;
        var filename = filewrapper.attr('filename');
        alertify.confirm("¿Desea eliminar el archivo <b>"+filename+"</b>?", function(e){
        if(e){
            var file = filewrapper.attr('fid');
            var elemid=filewrapper.attr('id');
            var type = elemid.split("_");
            if(type[0]=="efile")
                var process     = process_url+'?object=Quotation&action=Removequotationfile&fid='+file;
            else
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
                        filewrapper.parent().remove();
                        // var files = mainQuotationDropzone.files;
                        // for(i=0;i<files.length;i++)
                        // {
                        //     var FileHTML = files[i].previewElement;
                        //     if(FileHTML.childNodes[12].attributes[0].value==elemid)
                        //     {
                        //         FileHTML.parentNode.removeChild(files[i].previewElement);
                        //         var index = mainQuotationDropzone.files.indexOf(i);
                        //         if (index > -1) {
                        //             mainQuotationDropzone.files.splice(index, 1);
                        //         }
                        //     }
                        // }
                        // files = mainQuotationDropzone.getAcceptedFiles();
                        
                        if($(".DeleteQuotationFileFromWrapper").length==0)
                        {
                            $(".dz-default").show();
                        }
                    }
                }
            });
        }
    });
}

function FileLinkClick(btn)
{
    console.log(btn);
    window.open(btn.attr('fileurl'),'_blank');
}