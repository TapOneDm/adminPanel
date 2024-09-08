<?php

use yii\db\Migration;

/**
 * Class m240818_171311_add_project_table
 */
class m240818_171311_add_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "project" (
                "id" SERIAL PRIMARY KEY,
                "title" TEXT NOT NULL,
                "text" TEXT NOT NULL,
                "place" TEXT NOT NULL,
                "image" TEXT NULL
            );
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240818_171311_add_project_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240818_171311_add_project_table cannot be reverted.\n";

        return false;
    }
    */
}
