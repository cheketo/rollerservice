<iframe name="google_maps" src="../../library/frames/frame.map.autolocation.php" framepadding="0" frameborder="0" style="width:100%;height:25em;overflow:expand;"></iframe>
<div id="google_mapsErrorMsg" class="ErrorText Red Hidden">Seleccione una ubicaci&oacute;n</div>
<?php
  // $Map is an array that has to be defined at parent file
  echo insertElement('hidden','map_lat',$Map['lat']);
  echo insertElement('hidden','map_lng',$Map['lng']);
  echo insertElement('hidden','map_address',$Map['address']);
  echo insertElement('hidden','map_address_short',$Map['address_short']);
  echo insertElement('hidden','map_zone',$Map['zone']);
  echo insertElement('hidden','map_zone_short',$Map['zone_short']);
  echo insertElement('hidden','map_region',$Map['region']);
  echo insertElement('hidden','map_region_short',$Map['region_short']);
  echo insertElement('hidden','map_province',$Map['province']);
  echo insertElement('hidden','map_province_short',$Map['province_short']);
  echo insertElement('hidden','map_country',$Map['country']);
  echo insertElement('hidden','map_country_short',$Map['country_short']);
  echo insertElement('hidden','map_postal_code',$Map['postal_code']);
  echo insertElement('hidden','map_postal_code_suffix',$Map['postal_code_suffix']);
  
  //$Foot->setScript('../../js/script.map.autolocation.js');
?>