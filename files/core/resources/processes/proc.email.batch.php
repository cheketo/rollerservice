<?php
    include('../../../core/resources/includes/inc.core.php');
    $Email = new Mailer();
    $Email->SendBatchEmails($_GET['logs']);
?>