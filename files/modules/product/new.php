<?php
    include("../../includes/inc.main.php");
    $Head->setTitle($Menu->GetTitle());
    $Head->setStyle('../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setStyle('../../../vendors/select2/select2.min.css'); // Select Inputs With Tags
    $Head->setHead();
    
    $Category = new Category();
    include('../../includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo insertElement("hidden","action",'insert');
    echo insertElement("hidden","category");
    echo insertElement("hidden","brand");

?>
  <!-- ///////// FIRST SCREEN ////////// -->
  <div class="CategoryMain box">
    <!--box-success-->
    <!-- <div class="box-header with-border">
      <h3 class="box-title">Complete el formulario</h3>
    </div>
    <! .box-header -->
    <div class="box-body categoryBoxBody">
      <div class="row"><!-- First Screen Row -->
        <!-- CONTENT -->
        <!-- Categories Menu -->
        <!--<div class="container productCategory1">-->
        <!--  <h4>Seleccione una categor&iacute;a</h4>-->
        <!--  <div class="squareMenuMain">-->
        <!--    <div class="CategoryVehicleTrigger col-lg-3 col-md-6 col-xs-12 squareItemMenu squareYellow">-->
        <!--      <span>Veh&iacute;culos</span>-->
        <!--      <img src="../../../skin/images/body/pictures/category-vehicles.jpg" alt="" />-->
        <!--      <span class="arrow-css Hidden"><div class="squareItemArrowYellow"></div></span>-->
        <!--    </div>-->
        <!--    <div class="CategoryRealStateTrigger col-lg-3 col-md-6 col-xs-12 squareItemMenu squareRed">-->
        <!--      <span>Inmuebles</span>-->
        <!--      <img src="../../../skin/images/body/pictures/category-real-estate.jpg" alt="" />-->
        <!--      <span class="arrow-css Hidden"><div class="squareItemArrowRed"></div></span>-->
        <!--    </div>-->
        <!--    <div class="CategoryServicesTrigger col-lg-3 col-md-6 col-xs-12 squareItemMenu squareBlue">-->
        <!--      <span>Servicios</span>-->
        <!--      <img src="../../../skin/images/body/pictures/category-services.jpg" alt="" />-->
        <!--      <span class="arrow-css Hidden"><div class="squareItemArrowBlue"></div></span>-->
        <!--    </div>-->
        <!--    <div class="CategoryProductsTrigger col-lg-3 col-md-6 col-xs-12 squareItemMenu squareGreen">-->
        <!--      <span>Productos</span>-->
        <!--      <img src="../../../skin/images/body/pictures/category-products.jpg" alt="" />-->
        <!--      <span class="arrow-css Hidden"><div class="squareItemArrowGreen"></div></span>-->
        <!--    </div>-->
        <!--  </div>-->
        <!--</div>-->
        <!-- / Categories Menu -->
        <!-- Categories -->
        <div class="container productCategory2 animated fadeIn">
          <!-- Item -->
          <div class="categoryList">
            <div class="categoryTitle"><span><b>L&iacute;neas</b> | Seleccione una L&iacute;nea</span></div>
            <ul>
              <?php 
                $Categories = $Category->GetAllCategories();
                
                foreach($Categories as $Cat)
                {
                  if($Parent!=$Cat['parent_id'])
                  {
                    $Level = $Category->CalculateCategoryLevel($Cat['category_id']);
                    if($Level>$MaxLevel) $MaxLevel = $Level;
                    $Parent = $Cat['parent_id'];
                    if($Parent!=0)
                    {
                      $Class = 'Hidden';
                      echo '</select></li>';
                    }
                    echo '<li class="'.$Class.'" level="'.$Level.'" category="'.$Parent.'"><select class="category_selector" name="category_'.$Parent.'" id="category_'.$Parent.'" size="20">';
                  }
                  echo '<option value="'.$Cat['category_id'].'">'.$Cat['title'].'</option>';
                }
                echo '</select></li>';
              ?>
              <li id="CountinueBtn" class="Hidden">
                <span>
                  <i class="fa fa-check"></i>
                  <button type="button" class="SelectCategory btn btnBlue categorySelectBtn">Continuar</button>
                </span>
              </li>
            </ul>
            <?php echo insertElement('hidden','maxlevel',$MaxLevel); ?>
          </div>
          <!-- / Item -->
        </div>
        <!-- Categories -->
      </div><!-- Firs Screen Row -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
  <!-- ///////// END FIRST SCREEN ////////// -->


  <!-- ////////// SECOND SCREEN ////////////////// -->
  <div class="ProductDetails box animated fadeIn Hidden">
    <div class="box-header flex-justify-center">
      <div class="col-md-6 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-cube"></i> Detalles del Art&iacute;culo</h4>
          
            <div class="form-group">
              L&iacute;nea: <b><span id="category_selected"></span></b>
            </div>
            <!--<div class="form-group">-->
            <!--  <?php //echo insertElement('text','title','','form-control','placeholder="Nombre del Art&iacute;culo"') ?>-->
            <!--</div>-->
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-4">
                <?php //echo insertElement('text','short_title','','form-control','placeholder="Nombre Corto"') ?>
                <?php echo insertElement('text','code','','form-control','placeholder="C&oacute;digo"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo insertElement('text','price','','form-control','placeholder="Precio"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo insertElement('text','rack','','form-control','placeholder="Estanter&iacute;a"') ?>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <!--<div class="col-xs-12 col-sm-4">-->
              <!--  <?php //echo insertElement('text','rack','','form-control','placeholder="Estanter&iacute;a"') ?>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-12">
                <?php echo insertElement('select','brand_select','','form-control  select2 selectTags','style="width:100%;height:auto!important;"',Utf8EncodeArray($DB->fetchAssoc("product_brand","brand_id,name","status='A' AND company_id=".$_SESSION['company_id'])),'0','Seleccionar Marca') ?>
              </div>
            </div>
            <div class="form-group">
              <?php echo insertElement('text','size','','form-control','placeholder="Medidas"') ?>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-4">
                <?php echo insertElement('text','stock','','form-control','placeholder="Stock Incial"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo insertElement('text','stock_min','','form-control','placeholder="Stock M&iacute;nimo"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo insertElement('text','stock_max','','form-control','placeholder="Stock M&aacute;ximo"') ?>
              </div>
            </div>
            <div class="form-group">
              <?php echo insertElement('button','dispatch_data','Agregar datos de &uacute;ltima importaci&oacute;n','btn btn-warning','style="width:100%;"') ?>
            </div>
            <div class="row form-group inline-form-custom Hidden Dispatch animated fadeIn">
              <div class="col-md-12">
                <?php echo insertElement('text','dispatch','','form-control','placeholder="Desp. Aduana"') ?>
              </div>
            </div>
            <div class="row form-group inline-form-custom Hidden Dispatch animated fadeIn">
              <div class="col-xs-12 col-sm-6">
                <?php echo insertElement('text','price_fob','','form-control','placeholder="Costo Fob"') ?>
              </div>
              <div class="col-xs-12 col-sm-6">
                <?php echo insertElement('text','price_dispatch','','form-control','placeholder="Costo Desp."') ?>
              </div>
            </div>
            <!-- Description (Character Counter)-->
            <div class="form-group textWithCounter">
              <textarea id="description" name="description" class="text-center" placeholder="Descripción" rows="4" maxlength="150"></textarea>
              <div class="indicator-wrapper">
                <p>Caracteres restantes</p>
                <div class="indicator"><span class="current-length">150</span></div>
              </div>
            </div>
            <div class="txC">
              <button type="button" class="ProductDescBtn btn btnBlue">Continuar</button>
              <button type="button" class="BackToCategory btn btnRed">Regresar</button>
            </div>
        </div>
        <!-- Description (Character Counter) -->
      </div>
    </div><!-- box -->
  </div><!-- box -->

  <!-- //////////////// END SECOND SCREEN /////////////// -->
  <!-- ///////////////// THIRD SCREEN ///////////////  -->
  <div class="box ColorSizeStockMain animated fadeIn Hidden">
    <div class="box-header flex-justify-center">
      <div class="col-lg-6 col-md-12">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-cube"></i> Colores | Talle | Stock</h4>
          <div class="colorSizeStock"><!-- This must be HIDDEN -->
            <!-- Color Picker -->
            <span><b>Variante 1</b></span><br>
            <span><a href="#">Eliminar</a></span>

            <div class="ColorPicker1 colorPickerContainer">
              <div class="row colorPicker">
                <div id="cpLibrary1" class="col-md-8 col-sm-12 col-xs-12 cpBoxLibrary">
                  <ul class="">
                    <li style="background-color: #000000" data-hex="#000000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #666666" data-hex="#666666"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #990000" data-hex="#990000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FF0000" data-hex="#FF0000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #B45F06" data-hex="#B45F06"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #E06666" data-hex="#E06666"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #38761D" data-hex="#38761D"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #0C9800" data-hex="#0C9800"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #BF9000" data-hex="#BF9000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FF9900" data-hex="#FF9900"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #93C47D" data-hex="#93C47D"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #F6B26B" data-hex="#F6B26B"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFD966" data-hex="#FFD966"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #741B47" data-hex="#741B47"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #134F5C" data-hex="#134F5C"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #0B5394" data-hex="#0B5394"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #1717FF" data-hex="#1717FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #7600FF" data-hex="#7600FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #E828FF" data-hex="#E828FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #8E7CC3" data-hex="#8E7CC3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #C27BA0" data-hex="#C27BA0"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #76A5AF" data-hex="#76A5AF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #6FA8DC" data-hex="#6FA8DC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #83DDFF" data-hex="#83DDFF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D9D2E9" data-hex="#D9D2E9"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #EAD1DC" data-hex="#EAD1DC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #CFE2F3" data-hex="#CFE2F3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #CCCCCC" data-hex="#CCCCCC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFFF00" data-hex="#FFFF00"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #F4CCCC" data-hex="#F4CCCC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FCE5CD" data-hex="#FCE5CD"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFF2CC" data-hex="#FFF2CC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D0E0E3" data-hex="#D0E0E3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D9EAD3" data-hex="#D9EAD3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFFFFF" data-hex="#FFFFFF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                  </ul>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12 cpBoxSelected">
                  Color Primario
                  <div id="selectedColor1" class="cpBoxSelectedItem"></div>
                </div>
              </div>
            </div><!-- /ColorPicker -->
            <div class="txC">
              <button type="button" class="ShowCP2 btn btnGreen smallBtn animated fadeIn">Combinar con otro color</button>
            </div>

            <!-- Color Picker -->
            <div class="ColorPicker2 Hidden colorPickerContainer animated fadeIn">
              <div class="row colorPicker">
                <div id="cpLibrary2" class="col-md-8 col-sm-12 col-xs-12 cpBoxLibrary">
                  <ul class="">
                    <li style="background-color: #000000" data-hex="#000000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #666666" data-hex="#666666"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #990000" data-hex="#990000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FF0000" data-hex="#FF0000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #B45F06" data-hex="#B45F06"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #E06666" data-hex="#E06666"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #38761D" data-hex="#38761D"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #0C9800" data-hex="#0C9800"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #BF9000" data-hex="#BF9000"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FF9900" data-hex="#FF9900"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #93C47D" data-hex="#93C47D"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #F6B26B" data-hex="#F6B26B"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFD966" data-hex="#FFD966"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #741B47" data-hex="#741B47"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #134F5C" data-hex="#134F5C"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #0B5394" data-hex="#0B5394"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #1717FF" data-hex="#1717FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #7600FF" data-hex="#7600FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #E828FF" data-hex="#E828FF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #8E7CC3" data-hex="#8E7CC3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #C27BA0" data-hex="#C27BA0"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #76A5AF" data-hex="#76A5AF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #6FA8DC" data-hex="#6FA8DC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #83DDFF" data-hex="#83DDFF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D9D2E9" data-hex="#D9D2E9"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #EAD1DC" data-hex="#EAD1DC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #CFE2F3" data-hex="#CFE2F3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #CCCCCC" data-hex="#CCCCCC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFFF00" data-hex="#FFFF00"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #F4CCCC" data-hex="#F4CCCC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FCE5CD" data-hex="#FCE5CD"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFF2CC" data-hex="#FFF2CC"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D0E0E3" data-hex="#D0E0E3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #D9EAD3" data-hex="#D9EAD3"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                    <li style="background-color: #FFFFFF" data-hex="#FFFFFF"><div class="cpIcon"><i class="fa fa-check"></i></div></li>
                  </ul>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12 cpBoxSelected">
                  Color Secundario
                  <div id="selectedColor2" class="cpBoxSelectedItem"></div>
                    <div class="CloseColorPicker closeColorPicker"><i class="fa fa-times"></i></div>
                </div>
              </div>
            </div><!-- /ColorPicker -->
            <!-- Size And Stock -->
            <div class="row sizes">
              <table class="table">
                <tbody>
                  <tr>
                    <th>Talle</th>
                    <th>Stock</th>
                    <th class="sizesActionDelete">Acci&oacute;n</th>
                  </tr>
                  <tr>
                    <td>
                      <select class="selectpicker">
                        <option disabled="" selected="">Talle...</option>
                        <option>XS</option>
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                        <option>XXL</option>
                      </select>
                    </td>
                    <td><input type="name" name="name" placeholder="Cantidad"></td>
                    <td ><i class="fa fa-trash"></i></td>
                  </tr>
                  <tr>
                    <td><i class="fa fa-plus"></i></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="multipleImg horizontal-list">
              <ul>
                <li class="mainImg"><span>Imagen Principal</span> <br><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
                <li><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
                <li><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
                <li><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
                <li><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
                <li><img src="../../../skin/images/body/icons/add-img.png" alt="" /></li>
              </ul>
            </div>
            <hr>
          </div>
          <div class="txC">
            <span>¿Tienes otro color de este producto? <a href="#">Agr&eacute;galo</a></span>
          </div>

          <!-- /Size And Stock -->
        </div><!-- inner-container -->
        <div class="txC">
          <button type="button" class="btn btnGreen"><i class="fa fa-check"></i> Publicar</button>
          <button type="button" class="btn btnBlue"><i class="fa fa-plus"></i> Publicar y Crear Otro</button>
        </div>
      </div><!-- col-md-6 -->
    </div><!-- box -->
  </div><!-- box -->

  <!-- Help Modal -->
<?php
$Foot->setScript('../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
$Foot->setScript('../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->setScript('../../../vendors/select2/select2.min.js');
include('../../includes/inc.bottom.php');
?>