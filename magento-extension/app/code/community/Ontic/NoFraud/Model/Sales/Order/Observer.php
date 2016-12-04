<?php

class Ontic_NoFraud_Model_Sales_Order_Observer
{
    /*
     * Observer for the place order saved event
     * 
     * Runs only the first time an order is placed
     */
    public function sales_order_place_after( $observer )
    {
	try
	{
	    Mage::log('----- OBSERVER PLACE -----');

	    //collect data
	    $order = $observer->getOrder();
	    $address = $order->getShippingAddress();

	    $name = $order->getCustomerName();
	    $email = $order->getShippingAddress()->getEmail();
	    $city = $address->getCity();
	    $street = $address->getStreet();
	    $region = $address->getRegion();
	    $country = $address->getCountryId();
	    $telephone = $address->getTelephone();
	    $zip = $address->getPostcode();
	    $vat = $order->getCustomer()->getTaxvat();
	    $total = $order->getGrandTotal();

	    $cardnumber = $order->getPayment()->getCcNumber();
	    $expirydate = substr($order->getPayment()->getCcExpYear(), -2).sprintf('%02d', $order->getPayment()->getCcExpMonth());

	    $ip = Mage::helper('core/http')->getRemoteAddr();

	    //create transaction array
	    $data = array(
		'name' => $name,
		'email' => $email,
		'city' => $city,
		'street' => $street,
		'region' => $region,
		'shipping_country' => $country,
		'telephone' => $telephone,
		'zip' => $zip,
		'vat' => $vat,
		'order_amount' => $total,
		'credit_card' => $cardnumber,
		'expirydate' => $expirydate,
		'client_ip_address' => $ip
	    );

	    //get module config
	    $host = Mage::getStoreConfig('ontic/nofraud/host'); // core_config_data
	    Mage::log('Host: '.$host);
	    $username = Mage::getStoreConfig('ontic/nofraud/username');
	    $password = Mage::getStoreConfig('ontic/nofraud/password');
	    $json = json_encode($data);

	    //send transaction to get assessment
	    $client = new Zend_Http_Client($host);
	    $client->setAuth($username, $password, Zend_Http_Client::AUTH_BASIC);
	    $client->setRawData($json, 'application/json');
	    $result = $client->request(Zend_Http_Client::POST)->getBody();
	    $val = json_decode($result);
	    $assessment = $val->assessment;

	    Mage::log('ASSESSMENT: '.$assessment);

	    $threshold_min = Mage::getStoreConfig('ontic/threshold/min');
	    Mage::log('Threshold_min: '.$threshold_min);

	    $threshold_max = Mage::getStoreConfig('ontic/threshold/max');
	    Mage::log('Threshold_max: '.$threshold_max);

	    //if assessment under min threshold cancel order
	    if(!is_null($assessment) && $assessment < $threshold_min)
	    {
		Mage::log('FRAUD DETECTED: Order canceled.');
		$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
		$history = $order->addStatusHistoryComment('NoFraud: transaction cannot be processed.', false);
		$history->setIsCustomerNotified(false);
		$order->save();
	    }

	}
	catch ( Exception $e )
	{
	    Mage::log( "Error processing NoFraud transaction: " . $e->getMessage() );
	}
    }

    /*
     * Observer for the order saved event
     *
     * Runs whenever an order is updated
     */
    public function sales_order_save_after( $observer )
    {
	try
	{
	    Mage::log('----- OBSERVER SAVE -----');

	    //collect data
	    $order = $observer->getOrder();
	    $address = $order->getShippingAddress();

	    $name = $order->getCustomerName();
	    $email = $order->getShippingAddress()->getEmail();
	    $city = $address->getCity();
	    $street = $address->getStreet();
	    $region = $address->getRegion();
	    $country = $address->getCountryId();
	    $telephone = $address->getTelephone();
	    $zip = $address->getPostcode();
	    $total = $order->getGrandTotal();

	    $cardnumber = $order->getPayment()->getCcNumber();
	    $expirydate = substr($order->getPayment()->getCcExpYear(), -2).sprintf('%02d', $order->getPayment()->getCcExpMonth());

	    $ip = Mage::helper('core/http')->getRemoteAddr();

	    $stateCancel = $order::STATE_CANCELED;
	    $stateComplete = $order::STATE_COMPLETE;

	    if ($order->getState() == $stateCancel)
	    {
		$learn = 1;
		$condition = 0; //bad
	    }
	    elseif ($order->getState() == $stateComplete)
	    {
		$learn = 1;
		$condition = 1; //good
	    }
	    else
	    {
		$learn = 0;
		$condition = -1;
	    }

	    Mage::log('Learn: '.$learn);
	    Mage::log('Condition: '.$condition);

	    //create transaction array
	    $data = array(
		'name' => $name,
		'email' => $email,
		'city' => $city,
		'street' => $street,
		'region' => $region,
		'shipping_country' => $country,
		'telephone' => $telephone,
		'zip' => $zip,
		'order_amount' => $total,
		'credit_card' => $cardnumber,
		'expirydate' => $expirydate,
		'client_ip_address' => $ip,
		'learn' => $learn,
		'condition' => $condition
	    );

	    //get module config
	    $host = Mage::getStoreConfig('ontic/nofraud/host'); // core_config_data
	    Mage::log('Host: '.$host);
	    $username = Mage::getStoreConfig('ontic/nofraud/username');
	    $password = Mage::getStoreConfig('ontic/nofraud/password');
	    $json = json_encode($data);

	    //send transaction for learning
	    $client = new Zend_Http_Client($host);
	    $client->setAuth($username, $password, Zend_Http_Client::AUTH_BASIC);
	    $client->setRawData($json, 'application/json');
	    $result = $client->request(Zend_Http_Client::POST)->getBody();
	}
	catch ( Exception $e )
	{
	    Mage::log( "Error processing NoFraud teaching: " . $e->getMessage() );
	}
    }

}

?>
