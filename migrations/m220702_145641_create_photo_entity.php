<?php

use yii\db\Migration;

/**
 * Class m220702_145641_create_photo_entity
 */
class m220702_145641_create_photo_entity extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%photo_entity}}', [
            'id' => $this->primaryKey(),
            'bind_obj_id'      => $this->integer(10)->notNull(),
            'created_at'  => $this->dateTime() . ' DEFAULT NOW()',
            'updated_at'  => $this->dateTime()->null(),
            'description' => $this->text()->null(),
            'title' => $this->string(20)->null(),
            'url' => $this->string(255)->null(),
            'type'        => $this->integer(1)->unsigned()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('photo_idx', 'photo_entity', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%photo_entity}}');
    }
}
