<?php

use yii\db\Migration;

/**
 * Class m240421_192812_add_token_table
 */
class m240421_192812_add_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "token" (
                "user_id" bigint NOT NULL,
                "refresh_token" TEXT NOT NULL,
                PRIMARY KEY ("user_id")
            );
        ');

        $this->execute('ALTER TABLE "token" ADD FOREIGN KEY ("user_id") REFERENCES "user" ("id");');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240421_192812_add_token_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240421_192812_add_token_table cannot be reverted.\n";

        return false;
    }
    */
}
