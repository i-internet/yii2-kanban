<?php

/* @var $this \yii\web\View */
/* @var $model \simialbi\yii2\kanban\models\Board */
/* @var $tasksByUser array */
/* @var $statuses array */



foreach ($tasksByUser as $userId => $tasks) {
    /* @var $user \simialbi\yii2\kanban\models\UserInterface */
    $user = call_user_func([Yii::$app->user->identityClass, 'findIdentity'], $userId);

    echo $this->render('/bucket/_item', [
        'statuses' => $statuses,
        'id' => $userId,
        'boardId' => $model->id,
        'title' => empty($userId)
            ? '<span class="kanban-user">' . Yii::t('simialbi/kanban', 'Not assigned') . '</span>'
            : $this->render('/task/_user', [
                'assigned' => false,
                'user' => $user
            ]),
        'tasks' => $tasks,
        'keyName' => 'userId',
        'action' => 'change-assignee',
        'sort' => false
    ]);
}
