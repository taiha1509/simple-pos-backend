<?php
namespace Magestore\POS\Setup;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Setup\Exception;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    private $salesSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory, SalesSetupFactory $salesSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        try {
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'isVisibleOnPOS');
        }catch (Exception $e){

        } finally {

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'isVisibleOnPOS',
                [
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Visible on POS',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '0',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
                ]
            );
        }

        /**
         * Remove previous attributes (pos_id)
         */
        $attributes = ['pos_id'];
        foreach ($attributes as $attr_to_remove){
            $salesSetup->removeAttribute(\Magento\Sales\Model\Order::ENTITY,$attr_to_remove);

        }



        /**
         * Add 'pos_id' attributes for order
         */
        $options = ['type' => 'int', 'visible' => false, 'required' => false];
        $salesSetup->addAttribute('order', 'pos_id', $options);

    }
}
