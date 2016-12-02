<?php
/**
 * Admin menu
 *
 * @category   Beetailer
 * @package    Beecoder_Beeshopy
*/


class Beecoder_Beeshopy_AdminController extends Mage_Adminhtml_Controller_Action
{
  public function indexAction()
  {
    $this->loadLayout();
    $this->_setActiveMenu('facebook-store');  

    $block = $this->getLayout()
    ->createBlock('core/text', 'beetailer-admin')
    ->setText("<iframe src='https://www.beetailer.com?from=iframe' width=1124 height='7350' frameborder='0' scrolling='no' style='margin:0px auto;display:block;'></iframe>");

    $this->_addContent($block);
    $this->renderLayout();
  }

  protected function _isAllowed()
  {
    return Mage::getSingleton('admin/session')->isAllowed('facebook-store');
  }
}
