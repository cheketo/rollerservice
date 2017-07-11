// function getLatLng()
// {
//     $("iframe[name*=map]").each(function(){
//         var frame   = $(this).contents();
//         var id      = $(this).attr('map');
//         var lat = $("#map"+id+"_lat").val();
//         var lng = $("#map"+id+"_lng").val();
//         if(lat && lng)
//         {
//             frame.find("#map_lat").val(lat);
//             frame.find("#map_lng").val(lng);
//             //alert(frame.find("#map_lng").val());
//         }  
//     });
// }

// function getMapsValues()
// {
//     $("iframe[name*=map]").each(function(){
//         var frame   = $(this).contents();
//         var id      = $(this).attr('map');
        
//         $('#map'+id+'_lat').val(frame.find('#map_lat').val());
//         $('#map'+id+'_lng').val(frame.find('#map_lng').val());
//         $('#map'+id+'_address').val(frame.find('#map_address').val());
//         $('#map'+id+'_address_short').val(frame.find('#map_address_short').val());
//         $('#map'+id+'_zone').val(frame.find('#map_zone').val());
//         $('#map'+id+'_zone_short').val(frame.find('#map_zone_short').val());
//         $('#map'+id+'_region').val(frame.find('#map_region').val());
//         $('#map'+id+'_short').val(frame.find('#map_region_short').val());
//         $('#map'+id+'_province').val(frame.find('#map_province').val());
//         $('#map'+id+'_province_short').val(frame.find('#map_province_short').val());
//         $('#map'+id+'_country').val(frame.find('#map_country').val());
//         $('#map'+id+'_country_short').val(frame.find('#map_country_short').val());
//         $('#map'+id+'_postal_code').val(frame.find('#map_postal_code').val());
//         $('#map'+id+'_postal_code_suffix').val(frame.find('#map_postal_code_suffix').val());
        
//         
//     });
// }

function addressNotFound(place,map)
{
    notifyError("No ha sido posible encontrar \"<b>"+ place+"</b>\" en el mapa.");
    //getMapsValues();
    validateMap(map);
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
    var result = true;
    $(".GoogleMap").each(function(){
        var id      = $(this).attr('map');
        result = validateMap(id);
        if(!result)
            return result;
    });
    return result;
}

// function InsertAutolocationMap(id)
// {
//     var html ='';
// 	html += '<div id="map'+id+'_ErrorMsg" class="ErrorText Red Hidden">Seleccione una ubicaci&oacute;n</div>';
// 	html += '<input type="hidden" id="map'+id+'_lat" />';
// 	html += '<input type="hidden" id="map'+id+'_lng" />';
// 	html += '<input type="hidden" id="map'+id+'_address" />';
// 	html += '<input type="hidden" id="map'+id+'_address_short" />';
// 	html += '<input type="hidden" id="map'+id+'_zone" />';
// 	html += '<input type="hidden" id="map'+id+'_zone_short" />';
// 	html += '<input type="hidden" id="map'+id+'_region" />';
// 	html += '<input type="hidden" id="map'+id+'_region_short" />';
// 	html += '<input type="hidden" id="map'+id+'_province" />';
// 	html += '<input type="hidden" id="map'+id+'_province_short" />';
// 	html += '<input type="hidden" id="map'+id+'_country" />';
// 	html += '<input type="hidden" id="map'+id+'_country_short" />';
// 	html += '<input type="hidden" id="map'+id+'_postal_code" />';
// 	html += '<input type="hidden" id="map'+id+'_postal_code_suffix" />';
// 	html += '<input type="text" id="pac-input'+id+'" class="pac-input controls" />';
// 	html += '<div id="map'+id+'" class="GoogleMap" map="'+id+'"></div>';
// 	return html;
// }

function initMaps(){
    $(".GoogleMap").each(function(){
       var mapID = $(this).attr("map");
       initMap(mapID);
    });
}

function initMap(mapID) {
    //alert('Mapa '+mapID+' incializado');
    //parent.getLatLng();
    // var lat = document.getElementById('map'+mapID+'_lat').value;
    // var lng = document.getElementById('map'+mapID+'_lng').value;
    //console.log($('#map'+mapID+'_lat').val());
    var lat = parseFloat($('#map'+mapID+'_lat').val());
    var lng = parseFloat($('#map'+mapID+'_lng').val());
    if(lat && lng)
    {
      var myLatlng = {lat: lat, lng: lng};
      var zoom = 14;
    }else{
      lat = -34.6037;
      lng = -58.3816;
      var zoom = 11;
    }
    
    var map = new google.maps.Map(document.getElementById('map'+mapID), {
      center: {lat: lat, lng: lng},
      zoom: zoom,
      disableDefaultUI: true
    });
    var input = /** @type {!HTMLInputElement} */(
        document.getElementById('pac-input'+mapID));

    //var types = document.getElementById('type-selector');
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
      map: map,
      anchorPoint: new google.maps.Point(0, -29)
    });
    if(typeof myLatlng !== 'undefined')
    {
      marker.setPosition(myLatlng);
    }

    autocomplete.addListener('place_changed', function() {
      emptyValues(mapID);
      infowindow.close();
      marker.setVisible(false);
      var place = autocomplete.getPlace();
      //console.log(place);
      if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        addressNotFound(place.name,mapID);
        //window.alert("No ha sido posible encontrar: '" + place.name + "'");
        return;
      }

      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(13);
      }
      marker.setPosition(place.geometry.location);
      marker.setVisible(true);

      var address = '';
      if (place.address_components) {
        address = [
          (place.address_components[1] && place.address_components[1].short_name || ''),
          (place.address_components[0] && place.address_components[0].short_name || ''),
          (place.address_components[2] && place.address_components[2].short_name || ''),
          (place.address_components[3] && place.address_components[3].short_name || ''),
          (place.address_components[4] && place.address_components[4].short_name || ''),
          (place.address_components[5] && place.address_components[5].long_name || ''),
          (place.address_components[6] && place.address_components[6].long_name || '')
        ].join(', ');
      }
    
    var place_values = place.address_components
    place_values.reverse();
    //place_values.forEach(fillHiddenFields);
    place_values.forEach(function(value) {
        fillHiddenFields(value,mapID);
    });
    
    
    
    // Send data to iframe parent page
    // parent.getMapsValues();//// http://www.forosdelweb.com/f13/como-puedo-obtener-datos-iframe-1036765/http://www.forosdelweb.com/f13/como-puedo-obtener-datos-iframe-1036765/
    document.getElementById("map"+mapID+"_lat").value = place.geometry.location.lat();
    document.getElementById("map"+mapID+"_lng").value = place.geometry.location.lng();
    //parent.$('body').contents().find('#google_maps_button').trigger('click');
        
      infowindow.setContent('<div><strong>' + address + '</strong>');
      infowindow.open(map, marker);
    });
  }
  
    function fillHiddenFields(object,mapID)
    {
        switch(object.types[0])
        {
            case 'street_number':
                document.getElementById("map"+mapID+"_address").value = document.getElementById("map"+mapID+"_address").value +" "+ object.long_name;
                document.getElementById("map"+mapID+"_address_short").value = document.getElementById("map"+mapID+"_address_short").value +" "+ object.short_name;
            break;
            case 'route':
                document.getElementById("map"+mapID+"_address").value = object.long_name;
                document.getElementById("map"+mapID+"_address_short").value = object.short_name;
            break;
            case 'sublocality_level_1':
                document.getElementById("map"+mapID+"_zone").value = object.long_name;
                document.getElementById("map"+mapID+"_zone_short").value = object.short_name;
            break;
            case 'locality':
                document.getElementById("map"+mapID+"_zone").value = object.long_name;
                document.getElementById("map"+mapID+"_zone_short").value = object.short_name;
            break;
            case 'administrative_area_level_2':
                document.getElementById("map"+mapID+"_region").value = object.long_name;
                document.getElementById("map"+mapID+"_region_short").value = object.short_name;
            break;
            case 'administrative_area_level_1':
                document.getElementById("map"+mapID+"_province").value = object.long_name;
                document.getElementById("map"+mapID+"_province_short").value = object.short_name;
            break;
            case 'country':
                document.getElementById("map"+mapID+"_country").value = object.long_name;
                document.getElementById("map"+mapID+"_country_short").value = object.short_name;
            break;
            case 'postal_code':
                document.getElementById("map"+mapID+"_postal_code").value = object.long_name;
            break;
            case 'postal_code_suffix':
                document.getElementById("map"+mapID+"_postal_code_suffix").value = object.long_name;
            break;
        }
      
        if($("#postal_code_"+mapID).length>0)
        {
            if($('#map'+mapID+'_postal_code').val())
            {
                $("#postal_code_"+mapID).prop("disabled",true);
                var pc = $("#map"+mapID+"_postal_code").val();
                if($('#map'+mapID+'_postal_code_suffix').val())
                {
                    pc = $('#map'+mapID+'_postal_code_suffix').val() +" "+ pc;
                }
                $("#postal_code_"+mapID).val(pc);
            }else{
                $("#postal_code_"+mapID).prop("disabled",false);
                $("#postal_code_"+mapID).val('');
            }
        }
        
        if($("#address_"+mapID).length>0)
        {
            if($('#map'+mapID+'_address').val())
            {
                $("#address_"+mapID).prop("disabled",true);
                $("#address_"+mapID).val($("#map"+mapID+"_address").val());
            }else{
                $("#address_"+mapID).prop("disabled",false);
                $("#address_"+mapID).val('');
            }
        }
        validateMap(mapID);
    }
  
  function emptyValues(mapID)
  {
    document.getElementById("map"+mapID+"_lat").value = "";
    document.getElementById("map"+mapID+"_lng").value = "";
    document.getElementById("map"+mapID+"_address").value ="";
    document.getElementById("map"+mapID+"_address_short").value ="";
    document.getElementById("map"+mapID+"_zone").value = "";
    document.getElementById("map"+mapID+"_zone_short").value = "";
    document.getElementById("map"+mapID+"_region").value = "";
    document.getElementById("map"+mapID+"_region_short").value = "";
    document.getElementById("map"+mapID+"_province").value = "";
    document.getElementById("map"+mapID+"_province_short").value = "";
    document.getElementById("map"+mapID+"_country").value = "";
    document.getElementById("map"+mapID+"_country_short").value = "";
    document.getElementById("map"+mapID+"_postal_code").value = "";
    document.getElementById("map"+mapID+"_postal_code_suffix").value = "";
  }