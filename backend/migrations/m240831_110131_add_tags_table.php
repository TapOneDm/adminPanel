<?php

use yii\db\Migration;

/**
 * Class m240831_110131_add_tags_table
 */
class m240831_110131_add_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('
            CREATE TABLE "tag" (
                id bigint NOT NULL,
                "title" TEXT NOT NULL
            );
        ');

        $this->execute('ALTER TABLE "tag" ADD CONSTRAINT "tag_id" PRIMARY KEY ("id");');

        $this->execute('
            CREATE TABLE "product_tag" (
                "product_id" bigint NOT NULL,
                "tag_id" bigint NOT NULL
            );
        ');

        $this->execute('ALTER TABLE "product_tag" ADD CONSTRAINT "product_id_tag_id" PRIMARY KEY ("product_id", "tag_id");');
        $this->execute('CREATE INDEX "product_tag_id" ON "product_tag" ("tag_id");');

        $this->execute('ALTER TABLE "product_tag" ADD FOREIGN KEY ("product_id") REFERENCES "product" ("id")');
        $this->execute('ALTER TABLE "product_tag" ADD FOREIGN KEY ("tag_id") REFERENCES "tag" ("id")');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240831_110131_add_tags_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240831_110131_add_tags_table cannot be reverted.\n";

        return false;
    }
    */
}
