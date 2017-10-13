<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      .GoogleMap {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
    </style>
  </head>
  <body>
    <input id="map_id" type="hidden" value="">
    <input id="map_address" type="hidden" value="">
    <input id="map_address_short" type="hidden" value="">
    <input id="map_country" type="hidden" value="">
    <input id="map_country_short" type="hidden" value="">
    <input id="map_province" type="hidden" value="">
    <input id="map_province_short" type="hidden" value="">
    <input id="map_region" type="hidden" value="">
    <input id="map_region_short" type="hidden" value="">
    <input id="map_zone" type="hidden" value="">
    <input id="map_zone_short" type="hidden" value="">
    <input id="map_postal_code" type="hidden" value="">
    <input id="map_postal_code_suffix" type="hidden" value="">
    <input id="map_lat" type="hidden" value="">
    <input id="map_lng" type="hidden" value="">
    <input id="pac-input" class="controls" type="text" placeholder="Ingrese una dirección">
    <div id="map" class="GoogleMap"></div>

    <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initMap() {
        parent.getLatLng();
        var lat = parseFloat(document.getElementById('map_lat').value);
        var lng = parseFloat(document.getElementById('map_lng').value);
        if(lat && lng)
        {
          var myLatlng = {lat: lat, lng: lng};
          var zoom = 14;
        }else{
          lat = -34.6037;
          lng = -58.3816;
          var zoom = 11;
        }
        
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: lat, lng: lng},
          zoom: zoom,
          disableDefaultUI: true
        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

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
          emptyValues();
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          //console.log(place);
          if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            parent.addressNotFound(place.name);
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
        place_values.forEach(fillHiddenFields);
        
        
        
        // Send data to iframe parent page
        parent.getMapsValues();//// http://www.forosdelweb.com/f13/como-puedo-obtener-datos-iframe-1036765/http://www.forosdelweb.com/f13/como-puedo-obtener-datos-iframe-1036765/
        document.getElementById("map_lat").value = place.geometry.location.lat();
        document.getElementById("map_lng").value = place.geometry.location.lng();
        //parent.$('body').contents().find('#google_maps_button').trigger('click');
            
          infowindow.setContent('<div><strong>' + address + '</strong>');
          infowindow.open(map, marker);
        });
      }
      
      function fillHiddenFields(object)
      {
          switch(object.types[0])
          {
            case 'street_number':
                document.getElementById("map_address").value = document.getElementById("map_address").value +" "+ object.long_name;
                document.getElementById("map_address_short").value = document.getElementById("map_address_short").value +" "+ object.short_name;
            break;
            case 'route':
                document.getElementById("map_address").value = object.long_name;
                document.getElementById("map_address_short").value = object.short_name;
            break;
            case 'sublocality_level_1':
                document.getElementById("map_zone").value = object.long_name;
                document.getElementById("map_zone_short").value = object.short_name;
            break;
            case 'locality':
                document.getElementById("map_zone").value = object.long_name;
                document.getElementById("map_zone_short").value = object.short_name;
            break;
            case 'administrative_area_level_2':
                document.getElementById("map_region").value = object.long_name;
                document.getElementById("map_region_short").value = object.short_name;
            break;
            case 'administrative_area_level_1':
                document.getElementById("map_province").value = object.long_name;
                document.getElementById("map_province_short").value = object.short_name;
            break;
            case 'country':
                document.getElementById("map_country").value = object.long_name;
                document.getElementById("map_country_short").value = object.short_name;
            break;
            case 'postal_code':
                document.getElementById("map_postal_code").value = object.long_name;
            break;
            case 'postal_code_suffix':
                document.getElementById("map_postal_code_suffix").value = object.long_name;
            break;
          }
      }
      
      function emptyValues()
      {
        document.getElementById("map_lat").value = "";
        document.getElementById("map_lng").value = "";
        document.getElementById("map_address").value ="";
        document.getElementById("map_address_short").value ="";
        document.getElementById("map_zone").value = "";
        document.getElementById("map_zone_short").value = "";
        document.getElementById("map_region").value = "";
        document.getElementById("map_region_short").value = "";
        document.getElementById("map_province").value = "";
        document.getElementById("map_province_short").value = "";
        document.getElementById("map_country").value = "";
        document.getElementById("map_country_short").value = "";
        document.getElementById("map_postal_code").value = "";
        document.getElementById("map_postal_code_suffix").value = "";
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMB_Fpcn6USQEoumEHZB_s31XSQeKQc0&libraries=places&callback=initMap&language=es" async defer></script>
  </body>
</html>