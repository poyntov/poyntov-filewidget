<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m201117_140847_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'id_meta' => $this->integer()->notNull()
        ]);

        $this->createIndex('idx-files-id_meta', 'files', 'id_meta');
        $this->addForeignKey('fk-post-author_id', 'files', 'id_meta', 'meta', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
