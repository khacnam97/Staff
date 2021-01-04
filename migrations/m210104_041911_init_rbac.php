<?php

use yii\db\Migration;

/**
 * Class m210104_041911_init_rbac
 */
class m210104_041911_init_rbac extends Migration
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

        $staff = $auth->createRole('staff');
        $auth->add($staff);
        $auth->addChild($staff, $viewProject);
        // add "author" role and give this role the "createPost" permission
        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $createProject);
        $auth->addChild($manager, $updateProject);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
//        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $manager);
        $auth->addChild($manager, $staff);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($staff, 3);
        $auth->assign($manager, 2);
        $auth->assign($admin, 1);

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
        echo "m210104_041911_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
