<?php
    namespace DrinksAndCo\Search\Setup;
    
    use Magento\Framework\Setup\UpgradeSchemaInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\DB\Ddl\Table;
    
    class UpgradeSchema implements UpgradeSchemaInterface
    {
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;
    
        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $installer->getConnection()
            ->addColumn(
                $installer->getTable('drinksandco_search_data'),
                'short_content',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Short Content'
                ]
            ) ;
            $installer->getConnection()->addColumn(
                $installer->getTable('drinksandco_search_data'),
                'content',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Content'
                ]
            ) ;
        }
    
        $installer->endSetup();
        }
    }
?>