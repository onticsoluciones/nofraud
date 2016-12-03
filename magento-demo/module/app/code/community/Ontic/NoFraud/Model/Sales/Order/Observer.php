<?php

class Ontic_NoFraud_Model_Sales_Order_Observer
{
    /*
     * Observer for the order saved event
     */
    public function sales_order_place_after( $observer )
    {
	try
	{
	    $order = $observer->getOrder();

	    $cardnumber = $order->getPayment->getCcNumber();

	    $host = Mage::getStoreConfig('ontic/nofraud/host'); // core_config_data
	    $username = Mage::getStoreConfig('ontic/nofraud/username');
	    $password = Mage::getStoreConfig('ontic/nofraud/password');

	    $data = array(
		'credit_card' => $cardnumber
	    );

	    $json = json_encode($data);

	    $client = new Zend_Http_Client($host);

	    $client->setAuth($username, $password, Zend_Http_Client::AUTH_BASIC);

	    $client->setRawData($json, 'application/json')->request('POST');

	    $result = $client->request()->getBody();

	    Mage::log('Response: '.$result);

	}
	catch ( Exception $e )
	{
	    Mage::log( "Error processing NoFraud transaction: " . $e->getMessage() );
	}
    }
}

?>