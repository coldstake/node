<?php 

class phpFunctions_Wallet {

public function ping($scheme,$ip,$port){

    $url = $scheme.'://'.$ip;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt ($ch, CURLOPT_PORT , $port);
    curl_setopt ($ch, CURLOPT_TIMEOUT , 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

public function rpc($scheme,$ip,$port,$rpcuser,$rpcpass,$command,$params=null){

    $url = $scheme.'://'.$ip.':'.$port.'/';
    $request = '{"jsonrpc": "1.0", "id":"trustake-co-uk", "method": "'.$command.'", "params": ['.$params.'] }';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
    curl_setopt($ch, CURLOPT_USERPWD, "$rpcuser:$rpcpass");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    $response = curl_exec($ch);
    $response = json_decode($response,true);
    $result = $response['result'];
    $error  = $response['error'];
    curl_close($ch);

        switch($command){

         case "validateaddress":
            return ($result['isvalid']);
            break;
         case "walletpassphrase":
            if( empty($result) && empty($error) ) {
                return true;
            } else {
                return '<span class="ui-state-error" style="padding-left:2px;">'.nl2br(htmlentities($response['error']['message'])).'</span>';
            }
            break;
          default:
            if (!is_null($error) ) {
                return $response['error']['message'];
            } else {
                return $result;
            }
        }
    }

    public function GetInvoiceStatus($invoiceId,$orderID) {
        require ('/var/secure/keys.php'); //secured location - sensitive keys
        require ('include/config.php'); // coin configuration
        require ('vendor/autoload.php'); //loads the btcpayserver library
      
        $storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage($encryt_pass);
        $privateKey    = $storageEngine->load('/var/secure/btcpayserver.pri');
        $publicKey     = $storageEngine->load('/var/secure/btcpayserver.pub');
        $client        = new \BTCPayServer\Client\Client();
        $adapter       = new \BTCPayServer\Client\Adapter\CurlAdapter();
        
        $client->setPrivateKey($privateKey);
        $client->setPublicKey($publicKey);
        $client->setUri($btcpayserver);
        $client->setAdapter($adapter);
        
        $token = new \BTCPayServer\Token();
        $token->setToken($pair_token);
        $token->setFacade('merchant');
        $client->setToken($token);
      
        $invoice = $client->getInvoice($invoiceId);
        
        $OrderIDCheck = $invoice->getOrderId();
        $OrderStatus = $invoice->getStatus();
        $ExcStatus = $invoice->getExceptionStatus();
          
        if (($OrderStatus == 'complete' || $OrderStatus == 'paid') && $OrderIDCheck == $orderID) {
          $result = "PASS";
        } else { //TODO: Handle partial payments
          $result = "FAIL";
        }
        
        return $result;
      }
      
      public function CreateInvoice($OrderID,$Price,$Description,$redirectURL) {
        require ('/var/secure/keys.php'); //secured location - sensitive keys
        require ('vendor/autoload.php'); //loads the btcpayserver library
      
        $storageEngine = new \BTCPayServer\Storage\EncryptedFilesystemStorage($encryt_pass);
        $privateKey    = $storageEngine->load('/var/secure/btcpayserver.pri');
        $publicKey     = $storageEngine->load('/var/secure/btcpayserver.pub');
        $client        = new \BTCPayServer\Client\Client();
        $adapter       = new \BTCPayServer\Client\Adapter\CurlAdapter();
        
        $client->setPrivateKey($privateKey);
        $client->setPublicKey($publicKey);
        $client->setUri($btcpayserver);
        $client->setAdapter($adapter);
        
        $token = new \BTCPayServer\Token();
        $token->setToken($pair_token);
        $token->setFacade('merchant');
        $client->setToken($token);
      
        // * This is where we will start to create an Invoice object, make sure to check
        // * the InvoiceInterface for methods that you can use.
        $invoice = new \BTCPayServer\Invoice();
        $buyer = new \BTCPayServer\Buyer();
        //$buyer->setEmail($email);
      
        // Add the buyers info to invoice
        $invoice->setBuyer($buyer);
      
        // Item is used to keep track of a few things
        $item = new \BTCPayServer\Item();
        $item
        //    ->setCode('skuNumber')
            ->setDescription($Description)
            ->setPrice($Price);
        $invoice->setItem($item);
      
        // Setting this to one of the supported currencies will create an invoice using
        // the exchange rate for that currency.
        $invoice->setCurrency(new \BTCPayServer\Currency('USD'));
      
        // Configure the rest of the invoice
        $invoice
            ->setOrderId($OrderID)
            //->setNotificationUrl('https://store.example.com/btcpayserver/callback')
            ->setRedirectURL($redirectURL);
      
        // Updates invoice with new information such as the invoice id and the URL where
        // a customer can view the invoice.
        try {
        echo "Creating invoice at BTCPayServer now.".PHP_EOL;
        $client->createInvoice($invoice);
        } catch (\Exception $e) {
            echo "Exception occured: " . $e->getMessage().PHP_EOL;
            $request  = $client->getRequest();
            $response = $client->getResponse();
            echo (string) $request.PHP_EOL.PHP_EOL.PHP_EOL;
            echo (string) $response.PHP_EOL.PHP_EOL;
            exit(1); // We do not want to continue if something went wrong
        }
      
        return array('invoice_id' => $invoice->getId(), 'invoice_url' => $invoice->getUrl());
      }      

}