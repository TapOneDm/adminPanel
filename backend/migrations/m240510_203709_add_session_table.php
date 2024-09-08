<?php

use yii\db\Migration;

/**
 * Class m240510_203709_add_session_table
 */
class m240510_203709_add_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('session', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
        ]);
        
        $this->addPrimaryKey('session_pk', 'session', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240510_203709_add_session_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240510_203709_add_session_table cannot be reverted.\n";

        return false;
    }
    */
}
