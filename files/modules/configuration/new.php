<?php 
    include('../../includes/inc.main.php');
    $Head->SetHead();
    include('../../includes/inc.top.php');
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Default Box Example</h3>
                    <div class="box-tools pull-right">
                         <!--Buttons, labels, and many other things can be placed here! -->
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php echo insertElement('text','user','','form-text',' validateEmpty="Ingrese un email" validateFromFile="../../library/processes/proc.common.php///El usuario ya existe///action:=validate///object:=AdminData"'); ?>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <?php echo insertElement('button','BtnCreate','Crear','btn bg-aqua') ?>
                </div><!-- box-footer -->
            </div><!-- /.box -->
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Default Box Example</h3>
                    <div class="box-tools pull-right">
                        <!-- Buttons, labels, and many other things can be placed here! -->
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    The body of the box
                </div><!-- /.box-body -->
                <div class="box-footer">
                    The footer of the box
                </div><!-- box-footer -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
 
<?php 
    include('../../includes/inc.bottom.php');
?>
