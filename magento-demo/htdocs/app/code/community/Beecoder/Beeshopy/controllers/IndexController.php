<?php
/**
 * Beetailer Shopping cart class
 *
 * @category   Beetailer
 * @package    Beecoder_Beeshopy
*/


class Beecoder_Beeshopy_IndexController extends Mage_Core_Controller_Front_Action
{
  public function indexAction()
  {
    if ($this->getRequest()->getParams()) {
      Mage::getModel('core/cookie')->set("beetailer_ref", $this->getRequest()->getParam('fb_ref'));
      Mage::getModel('core/cookie')->set("beetailer_ref_date", time());

      /* Fill shopping cart */
      $cart = Mage::getSingleton('checkout/cart');
      $products = $this->getRequest()->getParam('products'); 
      $coupons = $this->getRequest()->getParam('coupons');
      $sview = $this->getRequest()->getParam('store_view');

      foreach($products as $attrs){
        $product = Mage::getModel('catalog/product')
          ->setStoreId(Mage::app()->getStore($sview)->getId())
          ->load($attrs['idx']);

        $options = isset($attrs['options']) ? $attrs['options'] : array();
        $super_attributes = isset($attrs['super_attributes']) ? $attrs['super_attributes'] : array();
        $links = isset($attrs['links']) ? explode(",", $attrs['links'][0]) : array();
        
        try{
          $cart->addProduct($product, array(
            'qty' => $attrs["qty"],
            'super_attribute' => $super_attributes, 
            'options' => $options, 
            'links' => $links
          ));
        }catch (Mage_Core_Exception $e) { }
      }

      /* Add coupon codes, NOTE, It is ready for multiple, but we can only apply one (Magento limitaion)*/
      foreach($coupons as $couponCode){
        $cart->getQuote()->getShippingAddress()->setCollectShippingRates(true);
        $cart->getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();
      }

      $cart->save();
      Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

      $this->_redirect('checkout/cart', $this->gaParams());
    }else{
      $this->_redirect('/');
    }
  }

  /** Add Google analytics parameters */
  public function gaParams()
  {
    $redirect_params = array();
    foreach($this->getRequest()->getParams() as $k => $v){
      if(preg_match('/^utm_/', $k)){
      $redirect_params[$k] = $v;
      }
    }

    if(count($redirect_params)){
      $redirect_params = array('_query' => $redirect_params);
    }
    
    return $redirect_params;
  }
}
