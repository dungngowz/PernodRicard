<?php

namespace DrinksAndCo\History\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_post'))
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Post Id'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'label',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Label'
            )
            ->addColumn(
                'color',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Color'
            )
            ->addColumn(
                'url_key',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Url Key'
            )
            ->addColumn(
                'thumbnail',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Thumbnail'
            )
            ->addColumn(
                'short_content',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Short Content'
            )
            ->addColumn(
                'content',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Content'
            )
            ->addColumn(
                'meta_keywords',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Meta Keywords'
            )
            ->addColumn(
                'meta_description',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Meta Description'
            )
            ->addColumn(
                'tags',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Tags'
            )
            ->addColumn(
                'user',
                Table::TYPE_TEXT,
                250,
                ['nullable' => true, 'default' => null],
                'User'
            )
            ->addColumn(
                'updated_by_user',
                Table::TYPE_TEXT,
                250,
                ['nullable' => true, 'default' => null],
                'Updated By User'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )
			->addColumn(
				'image',
				Table::TYPE_TEXT,
				150,
				['nullable' => true, 'default' => ''],
				'Image'
            )
			->addColumn(
				'author_post',
				Table::TYPE_SMALLINT,
				null,
				['nullable' => true, 'default' => '0'],
				'Author Post'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_post', ['url_key']),
                ['url_key']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('DrinksAndCo_History_post'),
                    ['title', 'url_key', 'short_content', 'content', 'meta_keywords', 'meta_description', 'tags'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'url_key', 'short_content', 'content', 'meta_keywords', 'meta_description', 'tags'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setComment('History Posts');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_category'))
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Category Id'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'url_key',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Url Key'
            )
            ->addColumn(
                'meta_keywords',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Meta Keywords'
            )
            ->addColumn(
                'meta_description',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Meta Description'
            )
            ->addColumn(
                'sort_order',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Sort Order'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_category', ['url_key']),
                ['url_key']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('DrinksAndCo_History_category'),
                    ['title', 'url_key', 'meta_keywords', 'meta_description'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'url_key', 'meta_keywords', 'meta_description'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setComment('History Categories');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_comment'))
            ->addColumn(
                'comment_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Comment Id'
            )
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Post Id'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Name'
            )
            ->addColumn(
                'email',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Email'
            )
            ->addColumn(
                'content',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Content'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '2'],
                'Status'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_comment', ['email']),
                ['email']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('DrinksAndCo_History_comment'),
                    ['name', 'email', 'content'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name', 'email', 'content'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setComment('History Comments');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_tag'))
            ->addColumn(
                'tag_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Tag Id'
            )
            ->addColumn(
                'tag',
                Table::TYPE_TEXT,
                250,
                ['nullable' => false],
                'Tag'
            )
            ->addColumn(
                'tag_count',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Tag Count'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_tag', ['tag']),
                ['tag']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('DrinksAndCo_History_tag'),
                    ['tag'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['tag'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setComment('History Tags');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_post_store'))
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Post Id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store Id'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_post_store', ['post_id']),
                ['post_id']
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_post_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_post_store', 'post_id', 'DrinksAndCo_History_post', 'post_id'),
                'post_id',
                $installer->getTable('DrinksAndCo_History_post'),
                'post_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_post_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('History Post Store');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_category_store'))
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Category Id'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Store Id'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_category_store', ['category_id']),
                ['category_id']
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_category_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_category_store', 'category_id', 'DrinksAndCo_History_category', 'category_id'),
                'category_id',
                $installer->getTable('DrinksAndCo_History_category'),
                'category_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_category_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('History Category Store');
        $installer->getConnection()->createTable($table);
        $table = $installer->getConnection()
            ->newTable($installer->getTable('DrinksAndCo_History_category_post'))
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Category Id'
            )
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true],
                'Post Id'
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_category_post', ['category_id']),
                ['category_id']
            )
            ->addIndex(
                $installer->getIdxName('DrinksAndCo_History_category_post', ['post_id']),
                ['post_id']
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_category_post', 'category_id', 'DrinksAndCo_History_category', 'category_id'),
                'category_id',
                $installer->getTable('DrinksAndCo_History_category'),
                'category_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('DrinksAndCo_History_category_post', 'post_id', 'DrinksAndCo_History_post', 'post_id'),
                'post_id',
                $installer->getTable('DrinksAndCo_History_post'),
                'post_id',
                Table::ACTION_CASCADE
            )
            ->setComment('History Category Post');
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
