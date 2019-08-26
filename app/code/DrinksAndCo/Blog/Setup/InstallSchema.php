<?php

namespace DrinksAndCo\Blog\Setup;

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

        //Blog Posts
        $table = $installer->getConnection()
            ->newTable($installer->getTable('drinksandco_blog_post'))
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Post Id'
            )
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Category Id'
            )
            /*->addColumn(
                'content_type',
                Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Content Type'
            )*/
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
                'thumbnail',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true, 'default' => null],
                'Thumbnail'
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
				'author_post',
				Table::TYPE_SMALLINT,
				null,
				['nullable' => true, 'default' => '0'],
				'Author Post'
            )
            ->addIndex(
                $installer->getIdxName('drinksandco_blog_post', ['url_key']),
                ['url_key']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('drinksandco_blog_post'),
                    ['title', 'url_key', 'content', 'meta_keywords', 'meta_description'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'url_key', 'content', 'meta_keywords', 'meta_description'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->addForeignKey(
                $installer->getFkName('drinksandco_blog_category_post', 'category_id', 'drinksandco_blog_category', 'category_id'),
                'category_id',
                $installer->getTable('drinksandco_blog_category'),
                'category_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Blog Posts');
        $installer->getConnection()->createTable($table);


        //Blog Categories
        $table = $installer->getConnection()
            ->newTable($installer->getTable('drinksandco_blog_category'))
            ->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Category Id'
            )
            /*->addColumn(
                'content_type',
                Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Content Type'
            )*/
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
                $installer->getIdxName('drinksandco_blog_category', ['url_key']),
                ['url_key']
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable('drinksandco_blog_category'),
                    ['title', 'url_key', 'meta_keywords', 'meta_description'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'url_key', 'meta_keywords', 'meta_description'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setComment('Blog Categories');
        $installer->getConnection()->createTable($table);

        //Blog Post Store
        $table = $installer->getConnection()
            ->newTable($installer->getTable('drinksandco_blog_post_store'))
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
                $installer->getIdxName('drinksandco_blog_post_store', ['post_id']),
                ['post_id']
            )
            ->addIndex(
                $installer->getIdxName('drinksandco_blog_post_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('drinksandco_blog_post_store', 'post_id', 'drinksandco_blog_post', 'post_id'),
                'post_id',
                $installer->getTable('drinksandco_blog_post'),
                'post_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('drinksandco_blog_post_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Blog Post Store');
        $installer->getConnection()->createTable($table);

        //Blog Category Store
        $table = $installer->getConnection()
            ->newTable($installer->getTable('drinksandco_blog_category_store'))
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
                $installer->getIdxName('drinksandco_blog_category_store', ['category_id']),
                ['category_id']
            )
            ->addIndex(
                $installer->getIdxName('drinksandco_blog_category_store', ['store_id']),
                ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('drinksandco_blog_category_store', 'category_id', 'drinksandco_blog_category', 'category_id'),
                'category_id',
                $installer->getTable('drinksandco_blog_category'),
                'category_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('drinksandco_blog_category_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Blog Category Store');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
