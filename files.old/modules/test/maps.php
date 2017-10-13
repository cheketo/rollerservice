<!DOCTYPE html>
<html>
  <head>
    <title>Example Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <div id="floating-panel">
        <input type="text" value="" id="lat" />
        <input type="text" value="" id="lng" />
    </div>
    <div id="map"></div>
    <script>
      function initMap() {
        //Posición en mapa (aplicable a otros objetos)
        var chicago = new google.maps.LatLng(41.850, -87.650);
        
        // Objeto de mapa, genera el mapa de google centrado en chicago con zoom 3
        var map = new google.maps.Map(document.getElementById('map'), {
          center: chicago,
          zoom: 3
        });

        // Objeto de popup de información (se nutre de la función createInfoWindowContent para crear el contenido y de 'chicago' para setear la psoción y de 'map' que es el objeto que lo va a contener)
        var coordInfoWindow = new google.maps.InfoWindow({map:map});
        coordInfoWindow.setContent(createInfoWindowContent(chicago, map.getZoom()));
        coordInfoWindow.setPosition(chicago);
        //coordInfoWindow.open(map);

        // Se agrega un evento al objeto 'map' para que cuando se cambie el zoom del mapa cambie el contenido de objeto cordInfoWindow (popup)
        map.addListener('zoom_changed', function() {
          coordInfoWindow.setContent(createInfoWindowContent(chicago, map.getZoom()));
          coordInfoWindow.open(map);
        });
        
        // Incluír un marcado al mapa, primero crea el marcador y después lo modifica por un evento
        var marker = new google.maps.Marker()
        //map.data.addGeoJson(cities);
        map.addListener('click', function(e) {
          //placeMarkerAndPanTo(e.latLng, map);
          marker.setPosition(e.latLng);
          marker.setMap(map);
          map.panTo(e.latLng);
          document.getElementById("lat").value = e.latLng.lat();
          document.getElementById("lng").value = e.latLng.lng();
        });
      }

      var TILE_SIZE = 256;

      function createInfoWindowContent(latLng, zoom) {
        var scale = 1 << zoom;

        var worldCoordinate = project(latLng);

        var pixelCoordinate = new google.maps.Point(
            Math.floor(worldCoordinate.x * scale),
            Math.floor(worldCoordinate.y * scale));

        var tileCoordinate = new google.maps.Point(
            Math.floor(worldCoordinate.x * scale / TILE_SIZE),
            Math.floor(worldCoordinate.y * scale / TILE_SIZE));

        return [
          'Chicago, IL',
          'LatLng: ' + latLng,
          'Zoom level: ' + zoom,
          'World Coordinate: ' + worldCoordinate,
          'Pixel Coordinate: ' + pixelCoordinate,
          'Tile Coordinate: ' + tileCoordinate
        ].join('<br>');
      }

      // The mapping between latitude, longitude and pixels is defined by the web
      // mercator projection.
      function project(latLng) {
        var siny = Math.sin(latLng.lat() * Math.PI / 180);

        // Truncating to 0.9999 effectively limits latitude to 89.189. This is
        // about a third of a tile past the edge of the world tile.
        siny = Math.min(Math.max(siny, -0.9999), 0.9999);

        return new google.maps.Point(
            TILE_SIZE * (0.5 + latLng.lng() / 360),
            TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
      }
      
    //   function placeMarkerAndPanTo(latLng, map) {
    //     var marker = new google.maps.Marker({
    //       position: latLng,
    //       map: map
    //     });
    //     map.panTo(latLng);
    //   }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuMB_Fpcn6USQEoumEHZB_s31XSQeKQc0&callback=initMap">
    </script>
  </body>
</html>