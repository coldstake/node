<?php 
    session_start(); 
    require ('/var/secure/keys.php');
    require ('include/functions.php'); 
    require ('include/config.php');
    $wallet = new phpFunctions_Wallet();

    // Deal with the bots first
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
    
        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_response = $_POST['recaptcha_response'];
    
        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $captcha_secret_key . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if($recaptcha->success==true){

            // Take action based on the score returned:
            if ($recaptcha->score >= 0.5) {
                    $verified=true;
            } else {
                    $verified=false;
                    die (' Something went wrong! - please try again.');
            }
        } else { // there is an error /
            die (' Something went wrong! - please try again.');
        }
    }

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
    $OrderID = $ticker . '-' . $address;
    $_SESSION['OrderID']=$OrderID;
    
    // Full service description
    $serv=$_SESSION['Days_Online'].$service_desc;

    // Create invoice
    $inv = $wallet->CreateInvoice($OrderID,$_SESSION['Price'],$serv);
    $invoiceId= $inv['invoice_id'];
    $invoiceURL= $inv['invoice_url'];

    // Store the InvoiceID in session
    $_SESSION['InvoiceID']=$invoiceId;

    // Forwarding to payment page
    header('Location:' . $invoiceURL); //<<redirect to payment page
    //echo '<br><b>Invoice:</b><br>'.$invoiceId.'" created, see '.$invoiceURL .'<br>';

?>