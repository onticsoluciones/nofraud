<?php
/**
 * Beetailer checkout tracking code
 *
 * @category   Beetailer
 * @package    Beecoder_Beeshopy
*/


class Beecoder_Beeshopy_Block_Track extends Mage_Core_Block_Template 
{
  
  private $_order = null;
 
  public function getOrder()
  {
    if ($this->_order === null) {
      $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
      if ($orderId) {
        $order = Mage::getModel('sales/order')->load($orderId);
        if ($order->getId()) {
          $this->_order = $order;
        }
      }
    }
    return $this->_order;
  }

  public function trackingCode()
  {
    $beetailerRef = Mage::getModel('core/cookie')->get('beetailer_ref');
    $beetailerRefDate = Mage::getModel('core/cookie')->get('beetailer_ref_date');

    if ($order = $this->getOrder()) {
      $res = '<script type="text/javascript" src=\'//www.beetailer.com/s.js'.
      '?p[order_number]='.$order->getIncrementId().
      '&p[amount]='.urlencode(sprintf("%.2f", $order->getSubtotal())).
      '&p[order_date]='.urlencode($order->getCreatedAt()).
      '&p[email]='.urlencode($order->getCustomerEmail()).
      '&p[beetailer_ref]='.urlencode($beetailerRef).
      '&p[beetailer_ref_date]='.urlencode($beetailerRefDate).
      '&p[shop_domain]='.urlencode(Mage::getBaseURL()).
      '\'></script>';

      Mage::getModel('core/cookie')->delete('beetailer_ref');
    }
    return $res;
  }
}
