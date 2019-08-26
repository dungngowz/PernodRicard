<?php
namespace DrinksAndCo\DrinksAndCo_Customer\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            'company_name',
            [
                'label' => 'Company Name',
                'type' => 'varchar',
                'input' => 'text',
                'visible' => true,
                'required' => 0,
                'system' => false,
                'position' => 90
            ]
        );
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'company_name')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_edit', 'customer_account_create'],
            ]);
        $attribute->save();

        //Tax Code
        // $customerSetup->addAttribute(
        //     Customer::ENTITY,
        //     'tax_code',
        //     [
        //         'label' => 'Tax Code',
        //         'type' => 'varchar',
        //         'input' => 'text',
        //         'visible' => true,
        //         'required' => 0,
        //         'system' => false,
        //         'position' => 90
        //     ]
        // );
        // $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'tax_code')
        //     ->addData([
        //         'attribute_set_id' => $attributeSetId,
        //         'attribute_group_id' => $attributeGroupId,
        //         'used_in_forms' => ['adminhtml_customer', 'customer_account_edit', 'customer_account_create'],
        //     ]);
        // $attribute->save();
    }
}