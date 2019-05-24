<?php

/**
 * ----------------------------------------------
 * VALIDATE THIRD PARTY PAYMENT GATEWAY.... RETURN
 * @author Pradipta Sarkar
 * ----------------------------------------------- 
 */
//if (isset($_POST['txn_id']) && !isset($_REQUEST['action'])) {
if (isset($_POST['txn_id'])) {
    $Paypal = new Paypal_Standard();
    $Paypal->ipnValidate($_POST);
}