<?php


namespace Magestore\POS\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if(!$installer->tableExists('POS_staff')){
            $table = $installer->getConnection()->newTable(
              $installer->getTable('POS_staff')
            )->addColumn(
                'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'staff ID'
            )->addColumn(
                'name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                ['nullable' => false, 'default' => ''],
                'Name')
            ->addColumn(
                'email', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                ['nullable' => false],
                'username')
            ->addColumn(
                'password', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                ['nullable' => false],
                'password'
            )->addColumn(
                'created_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'Created At'
            )->addColumn(
                'updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'updated_at'
            )->addColumn(
                'status', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )->setComment('staff table');
            $installer->getConnection()->createTable($table);;
        }

        if(!$installer->tableExists('POS_session')){
            $table = $installer->getConnection()->newTable(
                $installer->getTable('POS_session')
            )->addColumn(
                'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'session ID'
            )->addColumn(
                'token', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                ['nullable' => false, 'default' => ''],
                'access token')
            ->addColumn(
                'created_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'Created At'
            )->addColumn(
                'updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'updated_at'
            )->addColumn(
                'expired', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                ['nullable' => false, 'default' => '1'],
                '1: session is expired '
            )->addColumn(
                'staff_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Staff ID'
            )->addForeignKey(
            $installer->getFkName(
                'POS_session',
                'id',
                'POS_staff',
                'id'
            ),
                'staff_id',
                $installer->getTable('POS_staff'),
                'id',
                Table::ACTION_CASCADE
            )->setComment('session table');
            $installer->getConnection()->createTable($table);;
        }


        if(!$installer->tableExists('POS_pos')){
            $table = $installer->getConnection()->newTable(
                $installer->getTable('POS_pos')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'pos id'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'pos name'
            )->addColumn(
                'description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                ['nullable' => true],
                'Description'
            )->addColumn(
                'created_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'Created At'
            )->addColumn(
                'updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME, null,
                ['nullable' => true],
                'updated_at'
            )->addColumn(
                'status', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )->addColumn(
                'location', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null,
                ['nullable' => false, 'default' => '1'],
                'location'
            )->setComment('POS table');

            $installer->getConnection()->createTable($table);
        }

        if(!$installer->tableExists('POS_staff_pos')){
            $table = $installer->getConnection()->newTable(
                $installer->getTable('POS_staff_pos')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Staff ID'
            )->addColumn(
                'staff_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Staff ID'
            )->addColumn(
                'pos_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'POS ID'
            )->addForeignKey(
                $installer->getFkName(
                    'POS_staff_pos',
                    'id',
                    'POS_staff',
                    'id'
                ),
                'staff_id',
                $installer->getTable('POS_staff'),
                'id',
                Table::ACTION_CASCADE
            )
                ->addForeignKey(
                    $installer->getFkName(
                        'POS_staff_pos',
                        'pos_id',
                        'POS_pos',
                        'id'
                    ),
                    'pos_id',
                    $installer->getTable('POS_pos'),
                    'id',
                    Table::ACTION_CASCADE
                );

            $installer->getConnection()->createTable($table);

        }

        $installer->endSetup();
    }
}
