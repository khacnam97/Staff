<?php

use yii\db\Migration;

/**
 * Class m210106_074736_addChildView
 */
class m210106_074736_addChildView extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $staff = $auth->getRole('staff');
        $viewProject = $auth->getPermission('viewProject');
        $auth->addChild($staff, $viewProject);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210106_074736_addChildView cannot be reverted.\n";

        return false;
    }
    */
}
