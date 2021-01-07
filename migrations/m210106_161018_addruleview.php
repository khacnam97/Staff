<?php

use yii\db\Migration;

/**
 * Class m210106_161018_addruleview
 */
class m210106_161018_addruleview extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add the rule
        $rule = new \app\commands\ViewProjectRule;
        $auth->add($rule);

        // add the "updateOwnPost" permission and associate the rule with it.
        $viewOwnProject = $auth->createPermission('viewOwnProject');
        $viewOwnProject->description = 'view own project';
        $viewOwnProject->ruleName = $rule->name;
        $auth->add($viewOwnProject);

        $manager = $auth->getRole('manager');
        $viewProject = $auth->getPermission('viewProject');
        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($viewOwnProject, $viewProject);

        // allow "author" to update their own posts
        $auth->addChild($manager, $viewOwnProject);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210106_161018_addruleview cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210106_161018_addruleview cannot be reverted.\n";

        return false;
    }
    */
}
