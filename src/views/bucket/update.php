<?php

use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $model \simialbi\yii2\kanban\models\Bucket */
/* @var $users \simialbi\yii2\models\UserInterface[] */
/* @var $statuses array */

?>

<?php Pjax::begin([
    'id' => 'updateBucketPjax' . $model->id,
    'formSelector' => '#updateBucketForm' . $model->id,
    'enablePushState' => false,
    'clientOptions' => ['skipOuterContainers' => true]
]); ?>
<div class="kanban-bucket-header">
    <?php $form = ActiveForm::begin([
        'action' => ['bucket/update', 'id' => $model->id],
        'id' => 'updateBucketForm' . $model->id,
        'fieldConfig' => function ($model, $attribute) {
            /* @var $model \yii\base\Model */
            return [
                'labelOptions' => ['class' => 'sr-only'],
                'inputOptions' => [
                    'class' => ['form-control', 'form-control-sm'],
                    'placeholder' => $model->getAttributeLabel($attribute)
                ]
            ];
        }
    ]); ?>
    <?= $form->field($model, 'name')->textInput(['autofocus' => true]); ?>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end(); ?>
