function getLatLng()
{
    $("iframe[name*=map]").each(function(){
        var frame   = $(this).contents();
        var id      = $(this).attr('map');
        var lat = $("#map"+id+"_lat").val();
        var lng = $("#map"+id+"_lng").val();
        if(lat && lng)
        {
            frame.find("#map_lat").val(lat);
            frame.find("#map_lng").val(lng);
            //alert(frame.find("#map_lng").val());
        }  
    });
}

function getMapsValues()
{
    $("iframe[name*=map]").each(function(){
        var frame   = $(this).contents();
        var id      = $(this).attr('map');
        
        $('#map'+id+'_lat').val(frame.find('#map_lat').val());
        $('#map'+id+'_lng').val(frame.find('#map_lng').val());
        $('#map'+id+'_address').val(frame.find('#map_address').val());
        $('#map'+id+'_address_short').val(frame.find('#map_address_short').val());
        $('#map'+id+'_zone').val(frame.find('#map_zone').val());
        $('#map'+id+'_zone_short').val(frame.find('#map_zone_short').val());
        $('#map'+id+'_region').val(frame.find('#map_region').val());
        $('#map'+id+'_short').val(frame.find('#map_region_short').val());
        $('#map'+id+'_province').val(frame.find('#map_province').val());
        $('#map'+id+'_province_short').val(frame.find('#map_province_short').val());
        $('#map'+id+'_country').val(frame.find('#map_country').val());
        $('#map'+id+'_country_short').val(frame.find('#map_country_short').val());
        $('#map'+id+'_postal_code').val(frame.find('#map_postal_code').val());
        $('#map'+id+'_postal_code_suffix').val(frame.find('#map_postal_code_suffix').val());
        
        if($("#postal_code_"+id).length>0)
        {
            if($('#map'+id+'_postal_code').val())
            {
                $("#postal_code_"+id).prop("disabled",true);
                var pc = $("#map"+id+"_postal_code").val();
                if($('#map'+id+'_postal_code_suffix').val())
                {
                    pc = $('#map'+id+'_postal_code_suffix').val() +" "+ pc;
                }
                $("#postal_code_"+id).val(pc);
            }else{
                $("#postal_code_"+id).prop("disabled",false);
                $("#postal_code_"+id).val('');
            }
        }
        
        if($("#address_"+id).length>0)
        {
            if($('#map'+id+'_address').val())
            {
                $("#address_"+id).prop("disabled",true);
                $("#address_"+id).val($("#map"+id+"_address").val());
            }else{
                $("#address_"+id).prop("disabled",false);
                $("#address_"+id).val('');
            }
        }
       validateMap(id);
    });
}

function addressNotFound(place)
{
    notifyError("No ha sido posible encontrar \"<b>"+ place+"</b>\" en el mapa.");
    getMapsValues();
    validateMaps();
}

function validateMap(id)
{
    var total = 0;
    $('input[name*="map'+id+'_"]').each(function(){
       if($(this).val())
        total++;
    });
    if(total>3)
    {
        $("#map"+id+"_ErrorMsg").addClass("Hidden");
        return true;
    }else{
        $("#map"+id+"_ErrorMsg").removeClass("Hidden");
        return false;
    }
}

function validateMaps()
{
    var result;
    $("iframe[name*=map]").each(function(){
        var id      = $(this).attr('map');
        result = validateMap(id);
        if(!result)
            return false
    });
    return true;
}

function InsertAutolocationMap(id)
{
    var html ='';
	html += '<iframe name="map'+id+'" id="map'+id+'" map="'+id+'" src="../../library/frames/frame.map.autolocation.php" framepadding="0" frameborder="0" style="width:100%;height:25em;overflow:expand;"></iframe>';
	html += '<div id="map'+id+'_ErrorMsg" class="ErrorText Red Hidden">Seleccione una ubicaci&oacute;n</div>';
	
	html += '<input type="hidden" id="map'+id+'_lat" />';
	html += '<input type="hidden" id="map'+id+'_lng" />';
	html += '<input type="hidden" id="map'+id+'_address" />';
	html += '<input type="hidden" id="map'+id+'_address_short" />';
	html += '<input type="hidden" id="map'+id+'_zone" />';
	html += '<input type="hidden" id="map'+id+'_zone_short" />';
	html += '<input type="hidden" id="map'+id+'_region" />';
	html += '<input type="hidden" id="map'+id+'_region_short" />';
	html += '<input type="hidden" id="map'+id+'_province" />';
	html += '<input type="hidden" id="map'+id+'_province_short" />';
	html += '<input type="hidden" id="map'+id+'_country" />';
	html += '<input type="hidden" id="map'+id+'_country_short" />';
	html += '<input type="hidden" id="map'+id+'_postal_code" />';
	html += '<input type="hidden" id="map'+id+'_postal_code_suffix" />';
	
	return html;
}