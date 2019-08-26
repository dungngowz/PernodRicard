<?php
/**
 * installSchema.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Search\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        $setup->startSetup();

        /**
         * Create table 'drinksandco_search_data'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('drinksandco_search_data')
        )->addColumn(
            'entity_id',
            Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Data ID'
        )
        ->addColumn(
            'object_id',
            Table::TYPE_SMALLINT,
            100,
            ['nullable' => false],
            'Object Id'
        )
        ->addColumn(
            'type',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false],
            'Type'
        )
        ->addColumn(
            'title',
            Table::TYPE_TEXT,
            NULL,
            ['nullable' => true],
            'Title'
        )
        ->addColumn(
            'short_content',
            Table::TYPE_TEXT,
            NULL,
            ['nullable' => true],
            'Short Content'
        )
        ->addColumn(
            'content',
            Table::TYPE_TEXT,
            NULL,
            ['nullable' => true],
            'Content'
        )
        ->addColumn(
            'url',
            Table::TYPE_TEXT,
            100,
            ['nullable' => true],
            'url'
        )
        ->addColumn(
            'thumbnail',
            Table::TYPE_TEXT,
            100,
            ['nullable' => true],
            'Thumbnail'
        )
        ->addColumn(
            'keyword',
            Table::TYPE_TEXT,
            100,
            ['nullable' => true],
            'Keyword'
        )
        ->addIndex(
            $setup->getIdxName(
                $setup->getTable('drinksandco_search_data'),
                ['type'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['type'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Data Search Table'
        );

        // Add more columns here

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
