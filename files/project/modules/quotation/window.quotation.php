<div class="window Hidden" id="window_traceability">
    <div class="window-border"><h4><div class="pull-left"><i class="fa fa-book"></i> Historial de Cotizaciones y Trazabilidad <span id="ProductName" class="font-weight-bold"></span></div><div class="pull-right"><div id="WindowClose" class="BtnWindowClose text-red"><i class="fa fa-times"></i></div></div></h4></div>
    <div class="window-body">
      <?php echo Core::InsertElement('hidden','product',0); ?>
      <?php if($Customer=="Y"){ ?>
      <div id="NewQuotationBox" class="box box-success txC">
        <div class="box-header">
          <h3 class="box-title QuotationBoxTitle cursor-pointer">Nueva Cotización de Proveedor</h3>

          <div class="box-tools">
            
            <button id="CollapseNewForm" type="button" class="btn btn-box-tool NewQuotationBoxToggle" data-widget="collapse"><i class="fa fa-minus"></i></button>
              
          </div>
        </div>
         
        <div class="box-body">
          <?php echo Core::InsertElement('hidden','new_quotation_dir'); ?>
          <?php //echo Core::InsertElement('hidden','last_product',0); ?>
          <?php echo Core::InsertElement('hidden','item',0); ?>
          <?php echo Core::InsertElement('hidden','filecount',0); ?>
          <form id="tform">
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement("autocomplete","tprovider",'','txC form-control','validateEmpty="Ingrese un Proveedor" placeholder="Seleccione un Proveedor" placeholderauto="Proveedor no encontrado" item="1" iconauto="shopping-cart"','Company','SearchProviders');?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('select','tcurrency','','form-control chosenSelect','validateEmpty="Seleccione una Moneda" data-placeholder="Seleccione una Moneda"',Core::Select('currency','currency_id,title',"",'title DESC'),' ',''); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tprice','','form-control txC inputMask','data-inputmask="\'mask\': \'9{+}[.99]\'" placeholder="Precio" validateEmpty="Ingrese un precio"'); ?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tquantity','',' form-control txC inputMask','data-inputmask="\'mask\': \'9{+}\'" placeholder="Cantidad" validateEmpty="Ingrese una cantidad"'); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tdate','','form-control txC delivery_date','placeholder="Fecha" validateEmpty="Ingrese una fecha"'); ?>
              </div>
              <div class="col-sm-6 col-xs-12">
                <?php echo Core::InsertElement('text','tday',"",'form-control txC inputMask','placeholder="D&iacute;as Entrega" data-inputmask="\'mask\': \'9{+}\'" validateEmpty="Ingrese una cantidad de d&iacute;as"'); ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12">
                <div id="DropzoneContainer" class="dropzone">
                  <?php echo Core::InsertElement('file','dropzonefile','','Hidden form-control','placeholder="Cargar Archivo"'); ?>
                </div>
              </div>
            </div>
            <div class="row txC" id="FileWrapper">
              <!--<div class="col-md-4 col-sm-6 col-xs-12 txC FileInfoDiv" style="margin-top:10px;" id="tfile_1" filename="Cotizaci&oacute;nRoller1" fileurl="../../../../skin/files/quotation/file.pdf">-->
              <!--  <span class="btn btn-danger DeleteFileFromWrapper" style="padding:0px 3px;"><i class="fa fa-times"></i></span>-->
              <!--  <img src="../../../../skin/images/body/icons/pdf.png" height="64" width="64"> <a href="../../../../skin/files/quotation/file.pdf" target="_blank">CotizaciónRoller1</a>-->
              <!--  <?php echo Core::InsertElement('hidden','fileid_1','20'); ?>-->
              <!--</div>-->
              
            </div>
            <br>
            <div class="row">
              <div class="col-xs-12">
                <?php echo Core::InsertElement('textarea','textra','','form-control',' placeholder="Datos adicionales"'); ?>
              </div>
            </div>
          </form>
          
        </div>
         
        <div class="box-footer clearfix">
          <div class="input-group input-group-sm txC">
            <div class="input-group-btn">
              <button type="button" class="btn btn-success btnGreen BtnSaveQuotation" id="BtnSaveQuotation"><i class="fa fa-check"></i> Guardar Cotizaci&oacute;n</button>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
      <div id="ProvidersBox" class="box box-warning txC">
          <div class="box-header">
            <h3 class="box-title QuotationBoxTitle cursor-pointer">Cotizaciones de Proveedores</h3>
            <div class="box-tools pull-right">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input name="table_search" class="form-control pull-right" placeholder="Buscar" type="text">
                <div class="input-group-btn">
                  <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  <button type="button" id="CollapseQuotations" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
              </div>
            </div>
          </div>
           
          <div class="box-body table-responsive no-padding">
            <table id="QuotationWrapper" class="table table-hover">
              <tbody><tr id="QuotationWrapperTh" name="QuotationWrapperTh">
                <th class="txC">Fecha</th>
                <th class="txC">Proveedor</th>
                <th class="txC">Precio</th>
                <th class="txC">Cantidad</th>
                <th class="txC">Total</th>
                <th class="txC">Entrega</th>
                <th class="txC">Datos Adicionales</th>
                <th class="txC">Archivos</th>
              </tr>
              <!--<tr class="ClearWindow">-->
              <!--  <td>18/10/2017</td>-->
              <!--  <td>SNK Australia</td>-->
              <!--  <td><span class="label label-success">$200</span></td>-->
              <!--  <td>20</td>-->
              <!--  <td>$200</td>-->
              <!--  <td>2 D&iacute;as</td>-->
              <!--  <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>-->
              <!--  <td><div><a href="../../../../skin/files/quotation/file.pdf" target="_blank"><img src="../../../../skin/images/body/icons/pdf.png"> CotizaciónRoller</a></div></td>-->
              <!--</tr>-->
              
            </tbody></table>
          </div>
           
        </div>
      <?php if($Customer=="Y"){ ?>
      <div id="QuotationsBox" class="box box-primary">
        <div class="box-header with-border txC">
          <h3 class="box-title QuotationBoxTitle cursor-pointer">&Uacute;ltimas cotizaciones al cliente</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
         
        <div class="box-body">
          <div class="table-responsive txC">
            <table id="CustomerQuotationWrapper" class="table no-margin">
              <thead>
              <tr>
                <th class="txC">Fecha</th>
                <th class="txC">Precio</th>
                <th class="txC">Cantidad</th>
                <th class="txC">Total</th>
                <th class="txC">Entrega</th>
                <th class="txC">Acciones</th>
              </tr>
              </thead>
              <tbody>
                <!--<tr class="ClearWindow">-->
                <!--  <td><span class="label label-default">18/10/2017</span></td>-->
                <!--  <td><span class="label label-success">$312.87</span></td>-->
                <!--  <td>10</td>-->
                <!--  <td><span class="label label-success">$3128.70</span></td>-->
                <!--  <td><span class="label label-warning">2 D&iacute;as</span></td>-->
                <!--  <td>-->
                <!--    <button type="button" class="btn btn-github SeeQuotation hint--bottom hint--bounce" aria-label="Ver Cotizaci&oacute;n" style="margin:0px;" item="1"><i class="fa fa-eye"></i></button>-->
                <!--    <button type="button" class="btn btn-primary CopyQuotation hint--bottom hint--bounce hint--info" aria-label="Copiar Datos" style="margin:0px;" item="1"><i class="fa fa-files-o"></i></button>-->
                <!--  </td>-->
                <!--</tr>-->
                <!--<tr class="ClearWindow">-->
                <!--  <td><span class="label label-default">02/01/2017</span></td>-->
                <!--  <td><span class="label label-success">$206.44</span></td>-->
                <!--  <td>5</td>-->
                <!--  <td><span class="label label-success">$1032.20</span></td>-->
                <!--  <td><span class="label label-warning">3 D&iacute;as</span></td>-->
                <!--  <td>-->
                <!--    <button type="button" class="btn btn-github SeeQuotation hint--bottom hint--bounce" aria-label="Ver Cotizaci&oacute;n" style="margin:0px;" item="1"><i class="fa fa-eye"></i></button>-->
                <!--    <button type="button" class="btn btn-primary CopyQuotation hint--bottom hint--bounce hint--info" aria-label="Copiar Datos" style="margin:0px;" item="1"><i class="fa fa-files-o"></i></button>-->
                <!--  </td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--  <td></td>-->
                <!--</tr>-->
              </tbody>
            </table>
          </div>
      
        </div>
      
        <!--<div class="box-footer clearfix">-->
          <!--<a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>-->
          <!--<a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>-->
        <!--</div>-->
      
      </div>
      <?php } ?>
      
    </div>
    <div class="window-border txC">
        <button type="button" class="btn btn-primary btnBlue BtnWindowClose"><i class="fa fa-check"></i> OK</button>
        <!--<button type="button" class="btn btn-success btnBlue"><i class="fa fa-dollar"></i> Save & Pay</button>-->
        <!--<button type="button" class="btn btn-error btnRed"><i class="fa fa-times"></i> Cancel</button>-->
    </div>
  </div>