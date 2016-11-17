<?php
    include('../../includes/inc.main.php');
    $Head->setTitle($Menu->GetTitle());
    //DataTables CSS Files
    $Head->setStyle('https://cdn.datatables.net/1.10.12/css/jquery.dataTables.css');
    $Head->setStyle('https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/colreorder/1.3.2/css/colReorder.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/keytable/2.1.3/css/keyTable.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/rowreorder/1.1.2/css/rowReorder.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/scroller/1.4.2/css/scroller.dataTables.min.css');
    $Head->setStyle('https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css');
    $Head->setHead();
    include('../../includes/inc.top.php');
 ?>
  <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre y Apellido</th>
            <th>Usuario</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php
          $Users = $DB->fetchAssoc('admin_user','admin_id,user,first_name,last_name,email,img',"status='A' AND user <> 'null'");
          foreach($Users as $User){
        ?>
        <tr>
            <td>
              <?php 
              if($User['img'] && file_exists($User['img']))
              {
                $Image = $User['img'];
              }else{
                $Image = $Admin->DefaultImg;
              } 
              ?>
              <a href="javascript:;" class="user-profile">
                <img src="<?php echo $Image; ?>" alt="" id="UserImgCircle">
              </a>
            </td>
            <td><?php echo $User['first_name'].' '.$User['last_name']; ?></td>
            <td><?php echo $User['user']; ?></td>
            <td><?php echo $User['email']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php
    include('../../includes/inc.bottom.php');
    $Foot->setScript('https://cdn.datatables.net/1.10.12/js/jquery.dataTables.js');
    $Foot->setScript('https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js');
    $Foot->setScript('https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js');
    $Foot->setScript('https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js');
    $Foot->setScript('https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js');
    $Foot->setScript('https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js');
    $Foot->setScript('https://cdn.datatables.net/colreorder/1.3.2/js/dataTables.colReorder.min.js');
    $Foot->setScript('https://cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js');
    $Foot->setScript('https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js');
    $Foot->setScript('https://cdn.datatables.net/keytable/2.1.3/js/dataTables.keyTable.min.js');
    $Foot->setScript('https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js');
    $Foot->setScript('https://cdn.datatables.net/rowreorder/1.1.2/js/dataTables.rowReorder.min.js');
    $Foot->setScript('https://cdn.datatables.net/scroller/1.4.2/js/dataTables.scroller.min.js');
    $Foot->setScript('https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js');
    $Foot->setFoot();
?>