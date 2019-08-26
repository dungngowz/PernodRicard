<?php
    namespace DrinksAndCo\News\Setup;
    
    use Magento\Framework\Setup\UpgradeSchemaInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\DB\Ddl\Table;
    
    class UpgradeSchema implements UpgradeSchemaInterface
    {
        public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
                $installer = $setup;
            
                $installer->startSetup();

                if (version_compare($context->getVersion(), '2.0.9', '<')) {
                    $installer->getConnection()
                    ->addColumn(
                        $installer->getTable('DrinksAndCo_News_post'),
                        'related_image',
                        [
                            'type' => Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment' => 'Relate Image'
                        ]
                    ) ;
                
            }
    
            $installer->endSetup();
        }
    }
?>