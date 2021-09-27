<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agent_data}}`.
 */
class m210927_094105_create_agent_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agent_data}}', [
            'id' => $this->primaryKey(),
            'data' => $this->text(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agent_data}}');
    }
}
