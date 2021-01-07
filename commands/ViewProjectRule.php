<?php
namespace app\commands;

use yii\rbac\Rule;
use app\models\Project;

/**
 * Checks if authorID matches user passed via params
 */
class ViewProjectRule extends Rule
{
    public $name= 'isViewProject';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    { 
        return isset($params['idUser']) ? in_array($user,$params['idUser']) : false;
    }
}