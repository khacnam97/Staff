<?php

use yii\db\Migration;

/**
 * Class m210106_150717_addrule
 */
class m210106_150717_addrule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add the rule
        $rule = new \app\commands\AuthorRule;
        $auth->add($rule);

        // add the "updateOwnPost" permission and associate the rule with it.
        $updateOwnProject = $auth->createPermission('updateOwnProject');
        $updateOwnProject->description = 'Update own project';
        $updateOwnProject->ruleName = $rule->name;
        $auth->add($updateOwnProject);

        $manager = $auth->getRole('manager');
        $updateProject = $auth->getPermission('updateProject');
        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($updateOwnProject, $updateProject);

        // allow "author" to update their own posts
        $auth->addChild($manager, $updateOwnProject);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210106_150717_addrule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210106_150717_addrule cannot be reverted.\n";

        return false;
    }
    */
}
