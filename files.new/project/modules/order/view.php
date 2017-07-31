<?php
    include("../../../core/resources/includes/inc.core.php");
    
    $ID     = $_GET['id'];
    $View   = new ProviderOrder($ID);
    $Data   = $View->GetData();
    Core::ValidateID($Data['order_id']);
    $Status = $View->Data['status'];
    
    $Items  = $View->GetItems();
    $Currency = $Items[0]['currency'];
    $Deliverys = Core::Select('stock_entrance_item a INNER JOIN product b ON (b.product_id=a.product_id) INNER JOIN core_user c ON (c.user_id=a.created_by)','a.*,b.code,c.first_name,c.last_name,c.img','order_id='.$ID,'entrance_id ');
    $Invoices = Core::Select('invoice a INNER JOIN relation_invoice_order b ON (a.invoice_id=b.invoice_id) INNER JOIN invoice_operation c ON (a.operation_id=c.operation_id)','a.*',"c.operation='I' AND b.order_id=".$ID);
    
    
    if($View->Data['agent_id'])
    {
        $Agent = Core::Select('provider_agent','name','agent_id='.$View->Data['agent_id']);
        $Agent = $Agent[0]['name'];
        $Class = "label label-primary";
    }else{
        $Agent = 'Sin Contacto';
        $Class = "label label-default";
    }
    switch($Status)
    {
        case "A":
            $TextStatus = 'En Proceso';
            $ClassStatus = 'label label-warning';
        break;
        case "F":
            $TextStatus = 'Finalizado';
            $ClassStatus = 'label label-success';
        break;
    }
    
    foreach($Invoices as $Invoice)
    {
        if($Invoice['type_id']!=25 && $Invoice['status']!='I')
            $TotalInvoice += $Invoice['total'];
    }
    
    $Head->SetTitle($Menu->GetTitle());
    $Head->SetIcon($Menu->GetHTMLicon());
    $Head->SetSubTitle("Orden de Compra a Proveedor");
    $Head->setHead();
    include('../../../project/resources/includes/inc.top.php');
?>
<?php echo Core::InsertElement("hidden","id",$ID); ?>
<?php echo Core::InsertElement("hidden","type",'N'); ?>

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><strong><?php echo $Data['provider'] ?></strong></h3>
            <!--<div class="box-tools pull-right">-->
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!--</div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <h4><div class="col-xs-6 col col-sm-3">Estado: <span class="<?php echo $ClassStatus ?>"><strong><?php echo $TextStatus ?></strong></span></div></h4>
                <h4><div class="col-xs-6 col col-sm-3">Facturado a hoy: <span class="label bg-teal"><strong><?php echo $Currency.$TotalInvoice ?></strong></span></div></h4>
                <h4><div class="col-xs-6 col col-sm-3 margin-top1em">Total Mercader&iacute;a: <span class="label bg-olive"><strong><?php echo $Currency.$Data['total'] ?></strong></span></div></h4>
                <h4><div class="col-xs-6 col col-sm-3 margin-top1em">Contacto: <span class="<?php echo $Class ?>"><strong><?php echo $Agent ?></strong></span></div></h4>
            </div>
        </div><!-- /.box-header -->
        <hr>
        <div class="box-body">
            <h3 class="subTitleA"><i class="fa fa-cubes"></i> Art&iacute;culos</h3>
            <div class="innerContainer" style="border:0px;padding-top:0px!important;margin:-10px 0px!important;">
                <div class="row bg-black" style="margin-bottom:0px!important;padding:5px 0px;">
                    <div class="col-xs-4 txC">
                        <strong>Art&iacute;culo</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Precio</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Cantidad</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Fecha Acordada</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Total</strong>
                    </div>
                </div>
                <?php
                    foreach($Items as $Item)
                    {
                        $Class = $Class=='bg-gray'? 'bg-gray-light' : 'bg-gray';
                        $Date = explode(" ",$Item['delivery_date']); 
                        $Date = implode("/",array_reverse(explode("-",$Date[0])));
                ?>
                <div class="row <?php echo $Class ?>" style="padding:5px 0px;">
                    <div class="col-xs-4 txC">
                        <span class="label bg-navy"><?php echo $Item['code'] ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <span class="label label-info"><?php echo $Currency.$Item['price'] ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <span class="label label-primary"><?php echo $Item['quantity'] ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <?php echo $Date ?>
                    </div>
                    <div class="col-xs-2 txC">
                        <span class="label bg-olive"><strong><?php echo $Currency.($Item['price']*$Item['quantity']) ?></strong></span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <hr>
            <h3 class="subTitleA"><i class="fa fa-truck"></i> Entregas</h3>
            <div class="innerContainer" style="border:0px;padding-top:0px!important;margin:-10px 0px!important;">
                <div class="row bg-red" style="margin-bottom:0px!important;padding:5px 0px;">
                    <div class="col-xs-4 txC">
                        <strong>Art&iacute;culo</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Cantidad Recibida</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Fecha Recepci&oacute;n</strong>
                    </div>
                    <div class="col-xs-4 txC">
                        <strong>Receptor</strong>
                    </div>
                </div>
                <?php 
                    foreach($Deliverys as $Delivery)
                    {
                        if($Entrance!=$Delivery['entrance_id'])
                        {
                            $Entrance = $Delivery['entrance_id'];
                            $Class = $Class=='bg-gray'? 'bg-gray-light' : 'bg-gray';
                        }
                        $Date = explode(" ",$Delivery['creation_date']);
                        $Date = implode("/",array_reverse(explode("-",$Date[0])))." ".$Date[1];
                        $Name = $Delivery['last_name'].", ".$Delivery['first_name'];
                ?>
                <div class="row <?php echo $Class ?>" style="padding:5px 0px;">
                    <div class="col-xs-4 txC">
                        <span class="label bg-navy"><?php echo $Delivery['code'] ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <span class="label label-primary"><?php echo $Delivery['quantity'] ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <?php echo $Date ?>
                    </div>
                    <div class="col-xs-4 txC">
                        <img src="<?php echo $Delivery['img'] ?>" style="height:2em;width:2em;" class="img-circle" alt="User Image">
                        <?php echo $Name ?>
                    </div>
                </div>
                <?php } ?>
                <?php if(count($Deliverys)<1){ ?>
                    <h4 class="txC">No existen entregas hechas en esta orden.</h4>
                <?php } ?>
            </div>
            <hr>
            <h3 class="subTitleA"><i class="fa fa-file-text"></i> Facturas</h3>
            <div class="innerContainer" style="border:0px;padding-top:0px!important;margin:-10px 0px!important;">
                <div class="row bg-blue" style="margin-bottom:0px!important;padding:5px 0px;">
                    <div class="col-xs-2 txC">
                        <strong>Nro.</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Total</strong>
                    </div>
                    <div class="col-xs-3 txC">
                        <strong>Fecha Control</strong>
                    </div>
                    <div class="col-xs-3 txC">
                        <strong>Estado</strong>
                    </div>
                    <div class="col-xs-2 txC">
                        <strong>Acciones</strong>
                    </div>
                </div>
                <?php
                    foreach($Invoices as $Invoice)
                    {
                        $Class = $Class=='bg-gray'? 'bg-gray-light' : 'bg-gray';
                        $BackBtn ='<button invoice="'.$Invoice['invoice_id'].'" aria-label="Anular Factura" class="RemoveInvoice btn label label-danger hint--top hint--bounce hint--error"><i class="fa fa-times"></i></button>';
                        $CreditBtn ='<button invoice="'.$Invoice['invoice_id'].'" aria-label="Generar Nota de Cr&eacute;dito" class="GenerateCreditNote btn label label-warning hint--top hint--bounce hint--warning"><i class="fa fa-file-text"></i></button>';   
                        
                        $Date = explode(" ",$Invoice['creation_date']);
                        $Date = implode("/",array_reverse(explode("-",$Date[0])))." ".$Date[1];
                        switch ($Invoice['status']) {
                            case 'P':
                                $Status = 'Pendiente Carga';
                                $Label = 'info';
                                $Actions = $CreditBtn." ".$BackBtn;
                            break;
                            
                            case 'A':
                                $Status = 'Pendiente Pago';
                                $Label = 'warning';
                                $Actions = $CreditBtn." ".$BackBtn;
                            break;
                            
                            case 'F':
                                $Status = 'Pagada';
                                $Label = 'primary';
                                $Actions = $CreditBtn;
                            break;
                            
                            case 'I':
                                $Status = 'Anulada';
                                $Label = 'danger';
                                // $Actions = $CreditBtn;
                            break;
                        }
                ?>
                <div id="row_invoice_<?php echo $Invoice['invoice_id']; ?>" class="row <?php echo $Class ?>" style="padding:5px 0px;">
                    <div class="col-xs-2 txC">
                        <span class="label label-info" id="invoice_text_<?php echo $Invoice['invoice_id']; ?>"><?php echo sprintf("%08d", $Invoice['number']); ?></span>
                    </div>
                    <div class="col-xs-2 txC">
                        <span class="text-green"><strong><?php echo $Currency.$Invoice['total'] ?></span></strong>
                    </div>
                    <div class="col-xs-3 txC">
                        <?php echo $Date ?>
                    </div>
                    <div class="col-xs-3 txC" id="invoice_span_<?php echo $Invoice['invoice_id'] ?>">
                        <span class="label label-<?php echo $Label ?>"><?php echo $Status ?></span>
                    </div>
                    <div class="col-xs-2 txC" id="actions_span_<?php echo $Invoice['invoice_id'] ?>">
                        <?php echo $Actions ?>
                    </div>
                </div>
                <?php } ?>
                <?php if(count($Invoices)<1){ ?>
                    <h4 class="txC">No existen facturas relacionadas a esta orden.</h4>
                <?php } ?>
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer txC">
            <a href="list.php?status=A"><button type="button" class="btn btn-error btnBlue"><i class="fa fa-arrow-left"></i> Regresar</button></a>
        </div><!-- box-footer -->
    </div><!-- /.box -->
  
<?php
include('../../../project/resources/includes/inc.bottom.php');
?>