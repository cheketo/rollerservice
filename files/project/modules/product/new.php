<?php
    include("../../../core/resources/includes/inc.core.php");
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
    $Head->setHead();
    
    $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","action",'insert');
    echo Core::InsertElement("hidden","category");

?>
  <!-- ///////// FIRST SCREEN ////////// -->
  <div class="CategoryMain box">
    <!--box-success-->
    <!-- <div class="box-header with-border">
      <h3 class="box-title">Complete el formulario</h3>
    </div>
    <! .box-header -->
    <div class="box-body categoryBoxBody">
      <div class="row">
        <!-- First Screen Row -->
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
                    $Level = $Category->CalculateCategoryLevel($Cat[Category::TABLE_ID]);
                    if($Level>$MaxLevel) $MaxLevel = $Level;
                    $Parent = $Cat['parent_id'];
                    if($Parent!=0)
                    {
                      $Class = 'Hidden';
                      echo '</select></li>';
                    }
                    echo '<li class="'.$Class.'" level="'.$Level.'" category="'.$Parent.'"><select class="category_selector" name="category_'.$Parent.'" id="category_'.$Parent.'" size="20">';
                  }
                  echo '<option value="'.$Cat[Category::TABLE_ID].'">'.$Cat['title'].'</option>';
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
            <?php echo Core::InsertElement('hidden','maxlevel',$MaxLevel); ?>
          <div class="txC">
            <button type="button" class="btn btn-error btnRed" id="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
          </div>
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
            <!--  <?php //echo Core::InsertElement('text','title','','form-control','placeholder="Nombre del Art&iacute;culo"') ?>-->
            <!--</div>-->
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-4">
                <?php //echo Core::InsertElement('text','short_title','','form-control','placeholder="Nombre Corto"') ?>
                <?php echo Core::InsertElement('text','code','','form-control','placeholder="C&oacute;digo" validateEmpty="Ingrese un c&oacute;digo."') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo Core::InsertElement('text','price','','form-control','placeholder="Precio" validateEmpty="Ingrese un precio." data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 2, \'digitsOptional\': false, \'prefix\': \'$\', \'placeholder\': \'0\'"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo Core::InsertElement('text','rack','','form-control','placeholder="Estanter&iacute;a"') ?>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <!--<div class="col-xs-12 col-sm-4">-->
              <!--  <?php //echo Core::InsertElement('text','rack','','form-control','placeholder="Estanter&iacute;a"') ?>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-12">
                <?php echo Core::InsertElement('select','brand','','form-control chosenSelect','data-placeholder="Seleccionar Marca" validateEmpty="Seleccione una marca." style="width:100%!important;"',Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),'',' '); ?>
              </div>
            </div>
            <div class="form-group">
              <?php echo Core::InsertElement('text','size','','form-control','placeholder="Medidas"') ?>
            </div>
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-4">
                <?php echo Core::InsertElement('text','stock','','form-control','placeholder="Stock Incial"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo Core::InsertElement('text','stock_min','','form-control','placeholder="Stock M&iacute;nimo"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <?php echo Core::InsertElement('text','stock_max','','form-control','placeholder="Stock M&aacute;ximo"') ?>
              </div>
            </div>
            <div class="form-group">
              <?php echo Core::InsertElement('button','dispatch_data','Agregar datos de &uacute;ltima importaci&oacute;n','btn btn-warning','style="width:100%;"') ?>
            </div>
            <div class="row form-group inline-form-custom Hidden Dispatch animated fadeIn">
              <div class="col-md-12">
                <?php echo Core::InsertElement('text','dispatch','','form-control','placeholder="Desp. Aduana"') ?>
              </div>
            </div>
            <div class="row form-group inline-form-custom Hidden Dispatch animated fadeIn">
              <div class="col-xs-12 col-sm-6">
                <?php echo Core::InsertElement('text','price_fob','','form-control','placeholder="Costo Fob"') ?>
              </div>
              <div class="col-xs-12 col-sm-6">
                <?php echo Core::InsertElement('text','price_dispatch','','form-control','placeholder="Costo Desp."') ?>
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
              <button type="button" class="btn btn-success btnGreen" id="BtnCreate"><i class="fa fa-check"></i> Finalizar</button>
              <button type="button" class="btn btn-success btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Finalizar y Crear Otro</button>
              <button type="button" class="BackToCategory btn btnRed">Regresar</button>
            </div>
        </div>
        <!-- Description (Character Counter) -->
      </div>
    </div><!-- box -->
  </div><!-- box -->

  <!-- //////////////// END SECOND SCREEN /////////////// -->

  <!-- Help Modal -->
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>