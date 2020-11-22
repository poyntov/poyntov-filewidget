<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%meta}}`.
 */
class m201117_140642_create_meta_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%meta}}', [
            'id' => $this->primaryKey(),
            'file_path' => $this->string(255)->null(),
            'file_date_time' => $this->integer()->null(),
            'file_size' => $this->integer()->null(),
            'file_type' => $this->integer()->null(),
            'mime_type' => $this->string(255)->null(),
            'height' => $this->integer()->null(),
            'width' => $this->integer()->null(),
            'is_color' => $this->integer()->null(),
            'byte_order_motorola' => $this->integer()->null(),
            'copyright' => $this->string(255)->null(),
            'image_description' => $this->string(255)->null(),
            'artist' => $this->string(255)->null(),
            'exif_ifd_pointer' => $this->integer()->null(),
            'title' => $this->string(255)->null(),
            'comments' => $this->string(255)->null(),
            'make' => $this->string(255)->null(),
            'model' => $this->string(255)->null(),
            'date_time_original' => $this->dateTime()->null(),
            'date_time_digitized' => $this->dateTime()->null(),
            'sub_sec_time_original' => $this->integer()->null(),
            'sub_sec_time_digitized' => $this->integer()->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%meta}}');
    }
}
