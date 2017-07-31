<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID = $_GET['id'];
    $Edit = new Product($ID);
    $Data = $Edit->Data;
    
    $Head->SetTitle($Data['code']);
    $Head->SetSubTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/bootstrap-switch/bootstrap-switch.css'); // Switch On Off
     
    $Head->setHead();
    
    
    
    $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","id",$ID);
    echo Core::InsertElement("hidden","action",'update');
    echo Core::InsertElement("hidden","category",$Data[Category::TABLE_ID]);

?>
  <!-- ///////// FIRST SCREEN ////////// -->
  <div class="CategoryMain box Hidden">
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
                  $Selected = $Cat[Category::TABLE_ID]==$Data[Category::TABLE_ID]? 'selected="selected"' : '';
                  echo '<option value="'.$Cat[Category::TABLE_ID].'" '.$Selected.'>'.$Cat['title'].'</option>';
                }
                echo '</select></li>';
              ?>
              <li id="CountinueBtn" class="">
                <span>
                  <i class="fa fa-check"></i>
                  <button type="button" class="SelectCategory btn btnBlue categorySelectBtn">Continuar</button>
                </span>
              </li>
            </ul>
            <?php echo Core::InsertElement('hidden','maxlevel',$MaxLevel); ?>
          </div>
          <!-- / Item -->
        </div>
        <!-- Categories -->
      </div><!-- Firs Screen Row -->
    </div><!-- /.box-body -->
  </div><!-- /.box -->
  <!-- ///////// END FIRST SCREEN ////////// -->


  <!-- ////////// SECOND SCREEN ////////////////// -->
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-6 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-cube"></i> Detalles del Art&iacute;culo</h4>
          
            <div class="form-group">
              L&iacute;nea: <b><span id="category_selected"></span></b> <button type="button" class="BackToCategory btn btn-warning"><i class="fa fa-pencil"></i></button>
            </div>
            <!--<div class="form-group">-->
            <!--  <?php //echo Core::InsertElement('text','title','','form-control','placeholder="Nombre del Art&iacute;culo"') ?>-->
            <!--</div>-->
            <div class="row form-group inline-form-custom">
              <div class="col-xs-12 col-sm-4">
                <label for="code">C&oacute;digo:</label>
                <?php echo Core::InsertElement('text','code',$Data['code'],'form-control','placeholder="C&oacute;digo" validateEmpty="Ingrese un c&oacute;digo."') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <label for="price">Precio:</label>
                <?php echo Core::InsertElement('text','price',$Data['price'],'form-control','placeholder="Precio" validateEmpty="Ingrese un precio."  data-inputmask="\'alias\': \'numeric\', \'groupSeparator\': \'\', \'autoGroup\': true, \'digits\': 2, \'digitsOptional\': false, \'prefix\': \'$\', \'placeholder\': \'0\'"') ?>
              </div>
              <div class="col-xs-12 col-sm-4">
                <label for="rack">Estanter&iacute;a:</label>
                <?php echo Core::InsertElement('text','rack',$Data['rack'],'form-control','placeholder="Estanter&iacute;a"') ?>
              </div>
            </div>
            <div class="row form-group inline-form-custom">
              <!--<div class="col-xs-12 col-sm-4">-->
              <!--  <?php //echo Core::InsertElement('text','rack','','form-control','placeholder="Estanter&iacute;a"') ?>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-12">
                <label for="brand_select">Marca:</label>
                <?php echo Core::InsertElement('select','brand',$Data['brand_id'],'form-control  chosenSelect','validateEmpty="Ingrese una marca." data-placeholder="Seleccionar Marca"',Core::Select(Brand::TABLE,Brand::TABLE_ID.",name","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),' ','') ?>
                
              </div>
            </div>
            <div class="form-group">
              <label for="size">Medidas:</label>
              <?php echo Core::InsertElement('text','size',$Data['size'],'form-control','placeholder="Medidas"') ?>
            </div>
            <div class="row form-group inline-form-custom">
              <!--<div class="col-xs-12 col-sm-4">-->
              <!--  <?php //echo Core::InsertElement('text','stock',$Data['stock'],'form-control','placeholder="Stock"') ?>-->
              <!--</div>-->
              <div class="col-xs-12 col-sm-6">
                <label for="stock_min">Stock M&iacute;nimo:</label>
                <?php echo Core::InsertElement('text','stock_min',$Data['stock_min'],'form-control','placeholder="Stock M&iacute;nimo"') ?>
              </div>
              <div class="col-xs-12 col-sm-6">
                <label for="stock_max">Stock M&aacute;ximo:</label>
                <?php echo Core::InsertElement('text','stock_max',$Data['stock_max'],'form-control','placeholder="Stock M&aacute;ximo"') ?>
              </div>
            </div>
            <!-- Description (Character Counter)-->
            <label for="description">Descripci&oacute;n:</label>
            <div class="form-group textWithCounter">
              <?php echo Core::InsertElement('textarea','description',$Data['description'],'text-center','placeholder="Descripción" rows="4" maxlength="150"'); ?>
              <!--<textarea id="description" name="description" class="text-center" placeholder="Descripción" rows="4" maxlength="150"></textarea>-->
              <div class="indicator-wrapper">
                <p>Caracteres restantes</p>
                <div class="indicator"><span class="current-length">150</span></div>
              </div>
            </div>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-check"></i> Finalizar Edici&oacute;n</button>
              <button type="button" class="btn btn-error btnRed" id="BtnCancel" name="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
        <!-- Description (Character Counter) -->
      </div>
    </div><!-- box -->
  </div><!-- box -->

  <!-- //////////////// END SECOND SCREEN /////////////// -->

  <!-- Help Modal -->
<?php
$Foot->SetScript('../../../../vendors/bootstrap-switch/script.bootstrap-switch.min.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');

include('../../../project/resources/includes/inc.bottom.php');
?>