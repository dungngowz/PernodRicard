<?php
/**
 * installSchema.php
 *
 * @copyright Copyright © 1989 Staempfli. All rights reserved.
 * @author    dung.ngowz@gmail.com
 */
namespace DrinksAndCo\Invite\Setup;

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
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
        * Drop tables if exists
        */
        $installer->getConnection()->dropTable($installer->getTable('drinksandco_invite_friend'));

        if (!$installer->tableExists('drinksandco_invite_friend')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('drinksandco_invite_friend'))
                ->addColumn('invite_friend_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Invite Friend ID')
                ->addColumn('customer_id_staff', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false], 'Customer ID Staff')
                ->addColumn('customer_id_relative', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false], 'Customer ID relative')
                ->setComment('InviteFriend Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
