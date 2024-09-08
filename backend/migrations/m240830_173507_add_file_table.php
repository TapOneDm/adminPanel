<?php

use yii\db\Migration;

/**
 * Class m240830_173507_add_file_table
 */
class m240830_173507_add_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "file" (
                "id" SERIAL PRIMARY KEY,
                "name" TEXT NOT NULL,
                "ext" TEXT NOT NULL,
                "path" TEXT NOT NULL,
                "open_link" TEXT NOT NULL
            );
        ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240830_173507_add_file_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240830_173507_add_file_table cannot be reverted.\n";

        return false;
    }
    */
}
