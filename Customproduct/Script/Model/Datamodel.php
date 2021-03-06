<?php
/**
 * Customproduct Script Module
 *
 * @category  Customproduct
 * @package   Customproduct_Script
 */
namespace Customproduct\Script\Model;


use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Customproduct\Script\Model
 */
class Datamodel 
{

    const NEW_URL_DATA = 'new';

    const USED_URL_DATA = 'used';

    const NEW_TITLE_DATA = 'new';

    const NEW_KEY_DATA = 'New';

    const USED_KEY_DATA = 'used';

    const REFURBISHED_KEY_DATA = 'refurbished';

    const USED_SKU_DATA = 'NEW';

    const NEW_REFURBISHED_KEY_DATA = 'REFURBISHED';

    const NEW_SKU_DATA = 'USED';

    const USED_TITLE_DATA = 'used';

    const USED_CUSTOM_ATTRIBUTE_DATA = 'used';
   
    const REFURBISHED_CUSTOM_ATTRIBUTE_DATA = 'refurbished';
    






    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Logger Interface
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     * @param StoreManagerInterface $storeManagerInterface
     * @param Product $product
     * @param ProductFactory $productFactory
     * @param Product\Copier $productCopier
     * @param LoggerInterface $logger
     * @param \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
     */
    public function __construct(
        
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        StoreManagerInterface $storeManagerInterface,
        Product $product,
        ProductFactory $productFactory,
        LoggerInterface $logger,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository
    )
    {
        
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->_product = $product;
        $this->_productFactory = $productFactory;
        $this->logger = $logger;
        $this->_stockItemRepository = $stockItemRepository;
        
    }

    /**
     * @param $entityId
     * @return bool
     * @throws \Exception
     */
    public function createProductNew($entityId){

    try{
       $mainProduct = $this->_product->load($entityId);
       $mainProductName = $mainProduct->getName();
       $stockQuantity = $mainProduct->getQuantityAndStockStatus();
    if(strpos($mainProductName, self::NEW_KEY_DATA) !== false)
    {
        $this->addLogger("Support :: Product Transcript SKU of main:".$mainProduct->getSku());
        $mainProductName = $mainProduct->getName();
        $mainProductSku = $mainProduct->getSku();
        $mainProductUrl = $mainProduct->getProductUrl();
        $mainProductUrlKey = $mainProduct->getUrlKey();
        $mainProductMetaTitle = $mainProduct->getMetaTitle();
        $newProductName = str_replace(self::NEW_KEY_DATA, self::USED_KEY_DATA, $mainProductName);
        $newProductSku = str_replace(self::USED_SKU_DATA, self::NEW_SKU_DATA, $mainProductSku);
        $newProductMetaTitle = str_replace(self::NEW_TITLE_DATA, self::USED_TITLE_DATA, $mainProductSku);
        $newProductUrl = str_replace(self::NEW_URL_DATA, self::USED_URL_DATA, $mainProductUrl);
        $newProductUrlKey = str_replace(self::NEW_URL_DATA, self::USED_URL_DATA, $mainProductUrlKey);
        $newProduct = $this->_productFactory->create();
        $newProduct->setSku($newProductSku);
        $newProduct->setName($newProductName);
        $newProduct->setName($newProductName);
        $newProduct->setCustomAttribute('custom_attribute',self::USED_CUSTOM_ATTRIBUTE_DATA);
        
        $newProduct->setUrlKey($newProductUrlKey);
        $newProduct->setAttributeSetId(4);
        $newProduct->setVisibility(4);
        $newProduct->setTaxClassId(0); 
        $newProduct->setTypeId('simple'); 
        $newProduct->setWebsiteIds(array(
            0
           ));
        $newProduct->setTitle($mainProductMetaTitle);
        $newProduct->setMetaTitle($mainProductMetaTitle);
        $newProduct->setPrice($mainProduct->getPrice());
        $newProduct->setStockData(array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => $stockQuantity['qty']
        ));
        $newProduct->save();
        $this->addLogger("Customproduct_module = Product  Saved of New:".$newProduct->getSku());
        return true;
    }else{
        $this->addLogger("Customproduct_module = Product  Not Saved:");
        return false;
    }
    }catch (\Throwable $e) {
       
        $this->addLogger("Customproduct = Product  Task Error :");
        $this->logger->critical("Customproduct :: Product  Task Error :" . $e->getMessage());
        throw new \Exception($e->getMessage());
    }
    return false;
}

    /**
     * @param $entityId
     * @return bool
     * @throws \Exception
     */
    public function createProductRefurbished($entityId){

    try{
       $mainProductRefurbished = $this->_product->load($entityId);
       $mainProductName = $mainProductRefurbished->getName();
       $stockQuantityRefurbished = $mainProductRefurbished->getQuantityAndStockStatus();
    if(strpos($mainProductName, self::NEW_KEY_DATA) !== false)
    {
        $mainProductName = $mainProductRefurbished->getName();
        $mainProductSku = $mainProductRefurbished->getSku();
        $mainProductUrl = $mainProductRefurbished->getProductUrl();
        $mainProductUrlKey = $mainProductRefurbished->getUrlKey();
        $mainProductMetaTitle = $mainProductRefurbished->getMetaTitle();
        $newProductName = str_replace(self::NEW_KEY_DATA, self::REFURBISHED_KEY_DATA, $mainProductName);
        $newProductSku = str_replace(self::USED_SKU_DATA, self::NEW_REFURBISHED_KEY_DATA, $mainProductSku);
        $newProductMetaTitle = str_replace(self::NEW_TITLE_DATA, self::REFURBISHED_KEY_DATA, $mainProductSku);
        $newProductUrl = str_replace(self::NEW_URL_DATA, self::REFURBISHED_KEY_DATA, $mainProductUrl);
        $newProductUrlKey = str_replace(self::NEW_URL_DATA, self::REFURBISHED_KEY_DATA, $mainProductUrlKey);
        $newProductRefurbished = $this->_productFactory->create();
        $newProductRefurbished->setSku($newProductSku);
        $newProductRefurbished->setName($newProductName); 
        $newProductRefurbished->setCustomAttribute('custom_attribute',self::REFURBISHED_CUSTOM_ATTRIBUTE_DATA);
        $newProductRefurbished->setUrlKey($newProductUrlKey);
        $newProductRefurbished->setAttributeSetId(4);
        $newProductRefurbished->setVisibility(4);
        $newProductRefurbished->setTaxClassId(0); 
        $newProductRefurbished->setTypeId('simple'); 
        $newProductRefurbished->setWebsiteIds(array(
            0
           ));
        $newProductRefurbished->setTitle($mainProductMetaTitle);
        $newProductRefurbished->setPrice($mainProductRefurbished->getPrice());
        $newProductRefurbished->setStockData(array(
                'use_config_manage_stock' => 0,
                'manage_stock' => 1,
                'is_in_stock' => 1,
                'qty' => $stockQuantityRefurbished['qty']
        ));
        $newProductRefurbished->save();
        $this->addLogger("Customproduct_module = Product  Saved of New:".$newProductRefurbished->getSku());
        return true;
    }else{
        $this->addLogger("Customproduct_module = Product  Not Saved:");
        return false;
    }
    }catch (\Throwable $e) {
       
        $this->addLogger("Customproduct_module = Product   Error :");
        $this->logger->critical("Customproduct_module = Product   Error :" . $e->getMessage());
        throw new \Exception($e->getMessage());
    }
    return false;
}



   /**
     * @param $log
     * @return mixed
     */
    protected function addLogger($log)
    {
        return $this->logger->addDebug("Customproduct_module = Product" . $log);
    }

}