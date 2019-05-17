<?php 
    session_start(); // start a session before any output
    require ('include/functions.php'); // standard functions
    require ('include/config.php'); // coin configuration
    $wallet = new phpFunctions_Wallet();

    //Check if node is online before further checks
    $check_server = $wallet->ping($scheme, $server_ip, $server_port);

    if ( $check_server == '' || empty($check_server) ) {
	    die (' The coind server located at '. $scheme.'://'.$server_port.' on Port:['.$server_port.'] appears to be unresponsive.');
    }

    // Grab the next unused address 
    $address = $wallet->rpc($scheme,$server_ip,$server_port,$rpc_user,$rpc_pass,'getnewstakeaddress') ;
    if ( $address == '' || empty($address) ) {
	    die (' Something went wrong! - please try again.');
    } else {
        $_SESSION['Address']=$address;
    }

    // Generate & store the InvoiceID in session
    $OrderID = $ticker . '-' . $wallet->crypto_rand(100000000000,999999999999);
    $_SESSION['OrderID']=$OrderID;

    // Create invoice
    $inv = $wallet->CreateInvoice($OrderID);
    $invoiceId= $inv['invoice_id'];
    $invoiceURL= $inv['invoice_url'];

    // Store the InvoiceID in session
    $_SESSION['InvoiceID']=$invoiceId;

    // Forwarding to payment page
    header('Location:' . $invoiceURL); //<<redirect to payment page
    //echo '<br><b>Invoice:</b><br>'.$invoiceId.'" created, see '.$invoiceURL .'<br>';