<?php

use yii\db\Migration;

/**
 * Class m240421_172424_add_user_table
 */
class m240421_172424_add_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "user" (
                "id" SERIAL PRIMARY KEY,
                "email" TEXT NOT NULL UNIQUE,
                "password" TEXT NOT NULL,
                "is_activated" BOOLEAN NOT NULL DEFAULT false,
                "activation_link" TEXT NULL
            );
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240421_172424_add_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240421_172424_add_user_table cannot be reverted.\n";

        return false;
    }
    */
}
