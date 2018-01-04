<?php
    include("../../../core/resources/includes/inc.core.php");
    $ID = $_GET['id'];
    $Edit = new ProductAbstract($ID);
    $Data = $Edit->Data;
    $Head->SetTitle($Menu->GetTitle().' Gen&eacute;rico');
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetStyle('../../../../vendors/autocomplete/jquery.auto-complete.css'); // Autocomplete
    $Head->setHead();
    
    $Products = count($Data['products']);
    
    $Checked = 'checked="checked"';
    
    if($Data['products'][0]['product_code'])
    {
      $ClassTable = '';
      $ClassButton = 'Hidden';
    }else{
      $ClassTable = 'Hidden';
      $ClassButton = '';
    }
    
    // $Category = new Category();
    include('../../../project/resources/includes/inc.top.php');
    
    // HIDDEN ELEMENTS
    echo Core::InsertElement("hidden","id",$ID);
    echo Core::InsertElement("hidden","action",'update');
    echo Core::InsertElement("hidden","codes",$Products);
?>
  <div class="ProductDetails box animated fadeIn">
    <div class="box-header flex-justify-center">
      <div class="col-md-8 col-sm-6 col-xs-12 ">
        <div class="innerContainer">
          <h4 class="subTitleB"><i class="fa fa-certificate"></i> Detalles del Art&iacute;culo Gen&eacute;rico</h4>
          
            <div class="form-group">
              L&iacute;nea:<?php echo Core::InsertElement('select','category',$Data['category_id'],'form-control chosenSelect','data-placeholder="Seleccionar L&iacute;nea" validateEmpty="Seleccione una l&iacute;nea."',Core::Select(Category::TABLE,Category::TABLE_ID.",title","status='A' AND ".CoreOrganization::TABLE_ID."=".$_SESSION[CoreOrganization::TABLE_ID]),'',' ') ?>
            </div>
            <div class="form-group">
              C&oacute;digo:
              <?php echo Core::InsertElement('text','code',$Data['code'],'form-control','placeholder="C&oacute;digo" validateEmpty="Ingrese un c&oacute;digo."') ?>
            </div>
            <hr>
            <div class="AsociateProduct animated fadeIn">
              <div class="box box-info <?php echo $ClassTable ?> animated fadeIn" id="CodeBox">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-exchange"></i> C&oacute;digos Asociados</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th class="txC">C&oacute;digo</th>
                        <th class="txC">L&iacute;nea</th>
                        <th class="txC">Marca</th>
                        <th class="txC">Acciones</th>
                      </tr>
                      </thead>
                      <tbody id="CodeWrapper">
                      <?php
                        $Row = 1;
                        foreach($Data['products'] as $Product)
                        {
                          if($Product['product_code'])
                          {
                            echo '<tr id="tr'.$Row.'"><input type="hidden" code="'.$Product['product_code'].'" class="CodeID" id="code_'.$Row.'" value="'.$Product[Product::TABLE_ID].'"><td class="text-blue txC"><b>'.$Product['product_code'].'</b></td><td class="txC">'.$Product['product_category'].'</td><td class="txC"><span class="label label-default">'.$Product['product_brand'].'</span></td><td class="txC"><button wrapper="tr'.$Row.'" type="button" class="DeleteCodeRelation btn btnRed hint--top hint--bounce hint--error" style="font-size:8px;" aria-label="Desasociar"><i class="fa fa-times"></i></button></td></tr>';
                            $Row++;
                          }
                        }
                      
                      ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
              </div>
          
              <div class="form-group">
                Asociar C&oacute;digo:
                <?php //echo Core::InsertElement('autocomplete','asoc','','form-control','placeholder="C&oacute;digo a asociar" placeholderauto="C&oacute;digo no encontrado" cacheauto="false" iconauto="cube" varsauto="category"','Product','SearchCodesForRelation') ?>
                <?php //echo Core::InsertElement('Hidden','prev_val'); ?>
                <?php echo Core::InsertElement('text','asoc','','form-control','placeholder="C&oacute;digo a asociar"') ?>
                <!--<br>-->
                <!--<div class="txC"><button type="button" class="btn btn-primary" id="BtnAsoc"><i class="fa fa-relation"></i> Asociar C&oacute;digo</button></div>-->
              </div>
              <hr>
            </div>
            <!--<div class="form-group">-->
            <!--  <?php echo Core::InsertElement('button','asoc_data','Asociar C&oacute;digos','btn btn-primary '.$ClassButton,'style="width:100%;"') ?>-->
            <!--</div>-->
            <div class="checkbox icheck txC">
              <label>
                <?php echo Core::InsertElement('checkbox','relation_status',1,'iCheckbox',$Checked); ?> <span>Todas las relaciones han sido establecidas</span>
              </label>
            </div>
            <hr>
            <div class="txC">
              <button type="button" class="btn btn-success btnGreen" id="BtnEdit"><i class="fa fa-check"></i> Guardar</button>
              <!--<button type="button" class="btn btn-primary btnBlue" id="BtnCreateNext"><i class="fa fa-plus"></i> Finalizar y Crear Otro</button>-->
              <button type="button" class="btn btn-error btnRed" id="BtnCancel" name="BtnCancel"><i class="fa fa-times"></i> Cancelar</button>
            </div>
        </div>
        <!-- Description (Character Counter) -->
      </div>
    </div><!-- box -->
  </div><!-- box -->
<?php
 // Bootstrap Select Input
$Foot->SetScript('../../../../vendors/autocomplete/jquery.auto-complete.js');
$Foot->SetScript('../../../../vendors/jquery-mask/src/jquery.mask.js');
$Foot->SetScript('../../../../vendors/inputmask3/jquery.inputmask.bundle.min.js');
include('../../../project/resources/includes/inc.bottom.php');
?>