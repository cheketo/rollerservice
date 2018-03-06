<?php
    include('../../../core/resources/includes/inc.core.php');
    $_SESSION['batch_id']=1;
    $Email = new Mailer();
    $Email->SendBatchEmails($_GET['logs']);
?>