<?php
/**
 * custom product Module
 *
 * @category  
 * @package   
 */
namespace Customproduct\Script\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as ProductAttributeCollectionFactory;


/**
 * Class InstallData
 * @package Setup
 */
class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    /**
     * @var ProductAttributeCollectionFactory
     */
    protected $productAttributeCollectionFactory;


    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ProductAttributeCollectionFactory $productAttributeCollectionFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ProductAttributeCollectionFactory $productAttributeCollectionFactory

    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeCollectionFactory = $productAttributeCollectionFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->setup = $setup;
        $this->setup->startSetup();
        $this->eavSetup = $this->eavSetupFactory->create([
            'setup' => $this->setup,
        ]);
        $this->createProductCustomAttributes();
        $this->setup->endSetup();

    }

    protected function createProductCustomAttributes(){
        $this->eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_attribute',
            [
                'type' => 'varchar',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => 'Custom Attribute',
                'input' => 'select',
                'class' => '',
                'source' => 'Customproduct\Script\Model\Object\Source\ProductConfig',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'is_used_in_grid' => false,
                'is_filterable_in_grid' => false,
                'unique' => false,
                'apply_to' => 'simple',
                'note' => 'define the custom product',
                'group' => 'General'
            ]
        );

    }
}