<?php

use yii\db\Migration;

/**
 * Class m210107_012014_ruledeleteproject
 */
class m210107_012014_ruledeleteproject extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add the rule
        $rule = new \app\commands\DeleteProjectRule;
        $auth->add($rule);

        // add the "deleteOwnPost" permission and associate the rule with it.
        $deleteOwnProject = $auth->createPermission('deleteOwnProject');
        $deleteOwnProject->description = 'delete own project';
        $deleteOwnProject->ruleName = $rule->name;
        $auth->add($deleteOwnProject);

        $manager = $auth->getRole('manager');
        $deleteProject = $auth->getPermission('deleteProject');
        // "updateOwnPost" will be used from "delete"
        $auth->addChild($deleteOwnProject, $deleteProject);

        // allow "author" to update their own posts
        $auth->addChild($manager, $deleteOwnProject);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210107_012014_ruledeleteproject cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210107_012014_ruledeleteproject cannot be reverted.\n";

        return false;
    }
    */
}
