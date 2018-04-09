<?php

use yii\db\Migration;

/**
 * Class m180408_103241_alter_table_post_add_collumn_complaints
 */
class m180408_103241_alter_table_post_add_collumn_complaints extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }
}
