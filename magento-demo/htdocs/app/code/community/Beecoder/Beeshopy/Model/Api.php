<?php
/**
 * Beetailer Custom API
 *
 * @category   Beetailer
 * @package    Beecoder_Beeshopy
*/


class Beecoder_Beeshopy_Model_Api extends Mage_Catalog_Model_Api_Resource
{
  /** Get beeshopy needed product information */
  public function productInfo($productId, $store = null, $identifierType = null)
  {
    /* Load Product */
    $product = $this->_getProduct($productId, $store, $identifierType);
    
    if (!$product->getId()) {
        $this->_fault('not_exists');
    }

    $result = $this->getProductInfo($product, $store);
    /* Children and related products*/
    $result += $this->children($product, $store);

    return $result;
  }

  public function categoryTree($parentId, $store = null)
  {
    if (is_null($parentId) && !is_null($store)) {
      $parentId = Mage::app()->getStore($this->_getStoreId($store))->getRootCategoryId();
    } elseif (is_null($parentId)) {
      $parentId = 1;
    }

    $tree = Mage::getResourceSingleton('catalog/category_tree')->load();

    $root = $tree->getNodeById($parentId);

    if ($root && $root->getId() == 1) {
        $root->setName(Mage::helper('catalog')->__('Root'));
    }

    $collection = Mage::getModel('catalog/category')->getCollection()
        ->setStoreId($this->_getStoreId($store))
        ->addAttributeToSelect('name')
        ->setLoadProductCount(true)
        ->addAttributeToSelect('is_active');

    $tree->addCollectionData($collection, true);

    return $this->_nodeToArray($root);
  }

  public function assignedProducts($categoryId, $store = null)
  {
    $category = Mage::getModel('catalog/category')->load($categoryId);

    $collection = $category->setStoreId($this->_getStoreId($store))
        ->getProductCollection()
        ->addAttributeToSelect('name')
        ->addAttributeToSelect('visibility')
        ->setOrder(Mage::getStoreConfig('catalog/frontend/default_sort_by'), 'asc');

    $result = array();

    foreach ($collection as $product) {
        $result[] = array(
            'product_id' => $product->getId(),
            'visibility' => $product->getVisibility(),
            'type'       => $product->getTypeId(),
            'sku'        => $product->getSku(),
            'title' => $product->getName()
        );
    }
    return $result;
  }

  /** Get system info such as currency, rates, ... */
  public function systemInfo()
  {
    $currencyModel = Mage::getModel('directory/currency'); 
    $store_views = array();
    $default_store_view = Mage::app()->getDefaultStoreView()->getId();
    /* Store views */
    foreach(Mage::app()->getStores() as $sview){
      if($sview->getIsActive() == 1 || $sview->getId() == $default_store_view){
        $includes_taxes = Mage::helper('tax')->priceIncludesTax($sview);
        $store_views[$sview->getId()] = array('id' => $sview->getId(), 'name' => $sview->getName(), 'code' => $sview->getCode(), 'root_category_id' => $sview->getRootCategoryId(), 'taxes_included' => $includes_taxes, 'base_url' => $sview->getBaseUrl('web'), 'secure_base_url' => $sview->getBaseUrl('web', true));
        /* Currencies */
        $store_views[$sview->getId()]['currencies'] = array();
        $base = $sview->getBaseCurrency()->getCode();

        foreach($sview->getAvailableCurrencyCodes() as $code){
          $default = $sview->getDefaultCurrencyCode() == $code ? true : false;
          $currency = $currencyModel->load($code);
          /* No ratio defined */
          $ratios = $currencyModel->getCurrencyRates($base, $code);
          if($ratios == NULL){$ratios = array($code => 1);}
          /* EO No ratio*/
          array_push($store_views[$sview->getId()]['currencies'],
            array("default" => $default, 'ratio' => $ratios, 'format' => $currency->getOutputFormat()));
        }
      }
    }
    return array('store_views' => $store_views, 'include_url_code' => Mage::getStoreConfig('web/url/use_store'));
  }

  /* Used to know if module is installed*/
  public function checkModule()
  {
    return array("api_version" => '3.1.5', "magento_version" => Mage::getVersion());
  }

  /*Auxiliar functions*/

  /** Get configurable/grouped and related children information */
  private function children($parent, $store = null, $identifierType = null)
  {
    try
    {
      $res['children'] = array();
      $res['related'] = array();

      /* Related products */
      foreach($parent->getRelatedProductIds() as $product_id){
        $product = $this->_getProduct($product_id, $store, $identifierType);

        if($product->isSalable() != false){
          array_push($res['related'], $this->getProductInfo($product, $store));
        }
      }

      switch($parent->getTypeId()){
        case("configurable"):
          $res["children"] = $this->configurableProducts($parent, $store, $identifierType);
          break;
        case("grouped"):
          $res["children"] = $this->groupedProducts($parent, $store, $identifierType);
          break;
      }

      return $res;
    }
    catch (Mage_Core_Exception $e)
    {
       $this->_fault('store_not_exists');
    }
  }

  private function getProductInfo($product, $store = null, $extra_params = array())
  {
    $images = $this->getImages($product);
    /* Custom Options */
    $options = $this->getCustomOptions($product);
    $links = $this->getDownloadableLinks($product);

    /* Regular price, or special price or cart rule discount */
    $final_price = $product->getFinalPrice();
    $regular_price = $product->getPrice();

    /* Prepare results */
    $in_stock = $product->getStockItem()->getIsInStock();

    /* Formatted and WYSIWYG ready descriptions */
    $_helper = Mage::helper('catalog/output');
    $short_description = $_helper->productAttribute($product, nl2br($product->getShortDescription()), 'short_description');
    $description = $_helper->productAttribute($product, nl2br($product->getDescription()), 'description');

    $result = array(
        'idx' => $product->getId(),
        'sku'        => $product->getSku(),
        'product_type'       => $product->getTypeId(),
        'body' => $product->getInDepth(),
        'description' => $description,
        'short_description' => $short_description,
        'title' => $product->getName(),
        'in_stock' => $in_stock,
        'qty' => $product->getStockItem()->getQty(),
        'price' => $regular_price,
        'permalink' => $product->getUrlPath(),
        /* 'url_in_store' => $product->getProductUrl(), */
        'url_in_store' => $product->getUrlInStore(array('_ignore_category' => true)),
        'images' => $images,
        'visibility' => $product->getVisibility(),
        'special_price' => ($final_price != $regular_price ? $final_price : NULL),
        'special_from_date' => $product->getSpecialFromDate(),
        'special_to_date' => $product->getSpecialToDate(),
        'custom_options' => $options,
        'status' => $product->getStatus(),
        'links' => $links,
        'manage_stock' => $product->getStockItem()->getManageStock(),
        'updated_at' => $product->getUpdatedAt(),
        'backorders' => ($in_stock != "0" ? NULL : Mage::getStoreConfig(Mage_CatalogInventory_Model_Stock_Item::XML_PATH_ITEM . "backorders", $store))
      );

    // Optional extra parameters
    return $result + $extra_params;
  }

  /** Get configurable products */
  private function configurableProducts($parent, $store = null, $identifierType = null)
  {
    $res = array();

    /* Check if using magento-configurable-simple module */
    $modules = Mage::getConfig()->getNode('modules')->children();
    $modulesArray = (array)$modules;
    $use_simple_configurable = (isset($modulesArray['OrganicInternet_SimpleConfigurableProducts']) && $modulesArray['OrganicInternet_SimpleConfigurableProducts']->is('active')) ? true : false;
    /* EO Check */

    /* Get configurable attributes */
    $attrs_codes = $parent->getTypeInstance()->getConfigurableAttributesAsArray();
    /* Get all children */
    $children = $parent->getTypeInstance()->getChildrenIds($parent->getId());

    foreach ($children[0] as $i => $value) {
      $product = $this->_getProduct($value, $store, $identifierType);
      /* Initial Price */
      $price = $use_simple_configurable ? $product->getFinalPrice() : $parent->getFinalPrice();
      /* Price Difference */
      $difference = 0;

      //Generate caption_name
      /* $caption = ""; */
      /* $configurable_options = array(); */

      $configurable_attributes = array();
      foreach($attrs_codes as $code){
        /* Product attribute and value */
        $attr_value = $product->getData($code['attribute_code']);
        $attribute = $product->getResource()->getAttribute($code['attribute_code'])->setStoreId($this->_getStoreId($store));
        $value_label = $attribute->getFrontend()->getValue($product);

        /* Attribute name */
        $labels = $attribute->getStoreLabels();
        $label = sizeOf($labels) > 0 ? $labels[$this->_getStoreId($store)] : NULL;


        $product_attribute = array('mgnt_id' => $code['attribute_id'], 'label' => ($label ? $label : $attribute->getFrontendLabel()), 'position' => $code['position']);
        /* Calculate price */
        foreach($code['values'] as $value){
          if($value['value_index'] == $attr_value) {
            /* Price */
            if($value["is_percent"] == 1){
              $absolute_price = ($price * $value['pricing_value']) / 100;
            }else{
              $absolute_price = $value['pricing_value'];
            }

            $difference += $absolute_price;
            /* Position */
            $pos = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->addFieldToFilter('option_id', $attr_value)->load()->getFirstItem()->getData("sort_order");

            $product_attribute['value'] = array('mgnt_id' => $value['value_index'], 'label' => $value_label, 'pricing_value' => $value['pricing_value'], 'is_percent' => $value['is_percent'], 'position' => $pos, 'absolute_price' => $absolute_price, 'admin_label' => $value['label']);
          }
        }
        array_push($configurable_attributes, $product_attribute);
      }
    if($product->isSalable() != false){
      array_push($res, $this->getProductInfo($product, $store, array( 
        'configurable_price' => $price + $difference, 'configurable_attributes' => $configurable_attributes)));
    }
    }
    return $res;
  }

  private function groupedProducts($parent, $store = null, $identifierType = null)
  {
    $res = array();
    $children = $parent->getTypeInstance()->getChildrenIds($parent->getId());
    foreach ($children[3] as $i => $value) {
      $product = $this->_getProduct($value, $store, $identifierType);
      if($product->isSalable() != false){
        array_push($res,$this->getProductInfo($product, $store));
      }
    }
    return $res;
  }

  private function getImages($product)
  {
    $image_class = Mage::getModel('catalog/product_attribute_media_api'); 
    $images = array();
    foreach ($image_class->items($product->getId(), $store) as $_image){
      $_image['url'] = (string)Mage::Helper('catalog/image')->init($product, 'image', $_image['file']);
      $images[] = $_image;
    }
    return $images;
  }

  private function getCustomOptions($product)
  {
    $options = array();
    $customOptions = $product->getOptions();
    foreach ($customOptions as $customOption) {
      $values = $customOption->getValues();
      $option = array(
        'mgnt_id' => $customOption->getId(),
        'title' => $customOption->getTitle(),
        'required' => $customOption->getIsRequire(),
        'input_type' => $customOption->getType(),
        'order' => $customOption->getSortOrder(),
        'price' => $customOption->getPrice(),
        'price_type' => $customOption->getPriceType(),
        'values' => array()
        );

      foreach ($values as $value) {
        array_push($option['values'], 
        array(
          'title' => $value->getTitle(),
          'price' => $value->getPrice(),
          'price_type' => $value->getPriceType(),
          'sku' => $value->getSku(),
          'mgnt_id' => $value->getId()
          )
        );
      }
      array_push($options, $option);
    }
    return $options;
  }

  /* Downloadable products links */
  private function getDownloadableLinks($product){
    if($product->getTypeId() != "downloadable") return array();
    $links = $product->getTypeInstance(true)->getLinks($product);
    $links_res = array();
    foreach($links as $link){
      array_push($links_res, array('price' => $link->getPrice(), 'mgnt_id' => $link->getId(), 'title' => $link->getTitle(), 'price_type' => "fixed"));
    }
    return $links_res;
  }

  protected function _nodeToArray(Varien_Data_Tree_Node $node)
  {
    $result = array();
    $result['category_id'] = $node->getId(); 
    $result['parent_id']   = $node->getParentId();
    $result['name']        = $node->getName();
    $result['is_active']   = $node->getIsActive();
    $result['position']    = $node->getPosition();
    $result['level']       = $node->getLevel();
    $result['product_count'] = $node->getProductCount();
    $result['children']    = array();

    foreach ($node->getChildren() as $child) {
        $result['children'][] = $this->_nodeToArray($child);
    }
    return $result;
  }
}
