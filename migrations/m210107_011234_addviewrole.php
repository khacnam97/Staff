<?php

use yii\db\Migration;

/**
 * Class m210107_011234_addviewrole
 */
class m210107_011234_addviewrole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $updateProject = $auth->getPermission('updateProject');
        $auth->addChild($admin, $updateProject);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210107_011234_addviewrole cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210107_011234_addviewrole cannot be reverted.\n";

        return false;
    }
    */
}
