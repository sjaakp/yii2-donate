<?php

namespace sjaakp\donate\migrations;

use yii\db\Migration;

/**
 * Class m000000_000000_init
 * @package sjaakp\donate
 */
class m000000_000000_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%donation}}', [
            'id' => $this->primaryKey()->unsigned(),
            'email' => $this->string(80)->null(),
            'amount' => $this->float()->notNull(),
            'message' => $this->text()->null(),
            'page' => $this->string(40)->null(),
            'mollie' => $this->string(30)->null(),
            'status' => $this->string(12)->null(),
            'donated_at' => $this->dateTime()->null(),
        ], $tableOptions);

        $this->createIndex('email', '{{%donation}}', 'email');
        $this->createIndex('donated_at', '{{%donation}}', 'donated_at');
    }

    public function down()
    {
        $this->dropTable('{{%donation}}');
    }
}
