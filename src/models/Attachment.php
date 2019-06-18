<?php
/**
 * @package yii2-kanban
 * @author Simon Karlen <simi.albi@outlook.com>
 * @copyright Copyright © 2019 Simon Karlen
 */

namespace simialbi\yii2\kanban\models;


use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Attachment
 * @package simialbi\yii2\kanban\models
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $name
 * @property string $path
 * @property string $mime_type
 * @property integer $size
 * @property boolean $card_show
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer|string $created_at
 * @property integer|string $updated_at
 *
 * @property-read UserInterface $author
 * @property-read UserInterface $updater
 * @property-read Task $task
 */
class Attachment extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%kanban_attachment}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'size'], 'integer'],
            [['name', 'mime_type'], 'string', 'max' => 255],
            ['path', 'file'],
            ['card_show', 'boolean'],

            ['card_show', 'default', 'value' => false],

            [['task_id', 'size', 'name', 'mime_type', 'path', 'card_show'], 'required']
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    self::EVENT_BEFORE_UPDATE => 'updated_by'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => 'updated_at'
                ]
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('simialbi/kanban/model/attachment', 'Id'),
            'task_id' => Yii::t('simialbi/kanban/model/attachment', 'Task'),
            'name' => Yii::t('simialbi/kanban/model/attachment', 'Name'),
            'path' => Yii::t('simialbi/kanban/model/attachment', 'Path'),
            'mime_type' => Yii::t('simialbi/kanban/model/attachment', 'Mime type'),
            'size' => Yii::t('simialbi/kanban/model/attachment', 'Size'),
            'card_show' => Yii::t('simialbi/kanban/model/attachment', 'Show on card'),
            'created_by' => Yii::t('simialbi/kanban/model/attachment', 'Created by'),
            'updated_by' => Yii::t('simialbi/kanban/model/attachment', 'Updated by'),
            'created_at' => Yii::t('simialbi/kanban/model/attachment', 'Created at'),
            'updated_at' => Yii::t('simialbi/kanban/model/attachment', 'Updated at'),
        ];
    }

    /**
     * Get author
     * @return UserInterface
     */
    public function getAuthor()
    {
        return call_user_func([Yii::$app->user->identityClass, 'findIdentity'], $this->created_by);
    }

    /**
     * Get user last updated
     * @return mixed
     */
    public function getUpdater()
    {
        return call_user_func([Yii::$app->user->identityClass, 'findIdentity'], $this->updated_by);
    }

    /**
     * Get associated task
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
