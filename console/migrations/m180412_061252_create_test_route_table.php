<?php

use yii\db\Migration;

/**
 * Handles the creation of table `test_route`.
 */
class m180412_061252_create_test_route_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('test_route', [
            'id' => $this->primaryKey(),
            'from' => $this->string(),
            'to' => $this->string(),
        ]);

        $this->insert('test_route', [
            'id' => 1,
            'from' => 'A',
            'to' => 'C',
        ]);

        $this->insert('test_route', [
            'id' => 2,
            'from' => 'A',
            'to' => 'E',
        ]);

        $this->insert('test_route', [
            'id' => 3,
            'from' => 'A',
            'to' => 'F',
        ]);

        $this->insert('test_route', [
            'id' => 4,
            'from' => 'B',
            'to' => 'C',
        ]);

        $this->insert('test_route', [
            'id' => 5,
            'from' => 'B',
            'to' => 'D',
        ]);

        $this->insert('test_route', [
            'id' => 6,
            'from' => 'C',
            'to' => 'D',
        ]);

        $this->insert('test_route', [
            'id' => 7,
            'from' => 'C',
            'to' => 'E',
        ]);

        $this->insert('test_route', [
            'id' => 8,
            'from' => 'D',
            'to' => 'E',
        ]);

        $this->insert('test_route', [
            'id' => 9,
            'from' => 'D',
            'to' => 'A',
        ]);

        $this->insert('test_route', [
            'id' => 10,
            'from' => 'E',
            'to' => 'B',
        ]);

        $this->insert('test_route', [
            'id' => 11,
            'from' => 'F',
            'to' => 'D',
        ]);

        $this->insert('test_route', [
            'id' => 12,
            'from' => 'C',
            'to' => 'G',
        ]);

        $this->insert('test_route', [
            'id' => 13,
            'from' => 'G',
            'to' => 'H',
        ]);

        $this->insert('test_route', [
            'id' => 14,
            'from' => 'G',
            'to' => 'D',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('test_route');
    }
}
