<?php

use yii\db\Migration;

/**
 * Class m240831_101213_add_product_tables
 */
class m240831_101213_add_product_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE TYPE record_status AS ENUM(\'ACTIVE\', \'IN_EDITING\', \'DELETED\', \'UNLINKED\');');

        $this->execute('
           CREATE TABLE "product" (
            id bigint NOT NULL,
            "record_status" record_status NOT NULL,
            "show_on_website" boolean NOT NULL,
            "image_file_id" bigint NULL,
            "title" text,
            "text" text NULL,
            "price" bigint,
            "sort_order" bigint NOT NULL
           ); 
        ');

        $this->execute('ALTER TABLE "product" ADD CONSTRAINT "product_id" PRIMARY KEY ("id");');
        $this->execute('CREATE INDEX "product_image_file_id" ON "product" ("image_file_id");');
        $this->execute('ALTER TABLE "product" ADD FOREIGN KEY ("image_file_id") REFERENCES "file" ("id")');

        $this->execute('
            CREATE TABLE "product_file" (
                "product_id" bigint NOT NULL,
                "file_id" bigint NOT NULL
            );
        ');

        $this->execute('ALTER TABLE "product_file" ADD CONSTRAINT "product_id_file_id" PRIMARY KEY ("product_id", "file_id");');
        $this->execute('CREATE INDEX "product_file_id" ON "product_file" ("file_id");');

        $this->execute('ALTER TABLE "product_file" ADD FOREIGN KEY ("product_id") REFERENCES "product" ("id")');
        $this->execute('ALTER TABLE "product_file" ADD FOREIGN KEY ("file_id") REFERENCES "file" ("id")');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240831_101213_add_product_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240831_101213_add_product_tables cannot be reverted.\n";

        return false;
    }
    */
}
