<?php

use yii\db\Migration;

/**
 * Class m210106_072939_addPermission
 */
class m210106_072939_addPermission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // add "createPost" permission
        $createProject = $auth->createPermission('createProject');
        $createProject->description = 'Create a project';
        $auth->add($createProject);

        // add "updatePost" permission
        $updateProject = $auth->createPermission('updateProject');
        $updateProject->description = 'Update project';
        $auth->add($updateProject);

        $deleteProject = $auth->createPermission('deleteProject');
        $deleteProject->description = 'Delete project';
        $auth->add($deleteProject);

        $viewProject = $auth->createPermission('viewProject');
        $viewProject->description = 'View project';
        $auth->add($viewProject);
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
        echo "m210106_072939_addPermission cannot be reverted.\n";

        return false;
    }
    */
}
