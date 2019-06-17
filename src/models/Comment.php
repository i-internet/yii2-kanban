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
use yii\web\IdentityInterface;

/**
 * Class Comment
 * @package simialbi\yii2\kanban\models
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $text
 * @property integer $created_by
 * @property integer|string $created_at
 *
 * @property-read IdentityInterface $author
 * @property-read Task $task
 * @property-read Bucket $bucket
 * @property-read Board $board
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%kanban_comment}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id'], 'integer'],
            ['text', 'string'],

            [['task_id', 'text'], 'required']
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
                    self::EVENT_BEFORE_INSERT => 'created_by'
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => 'created_at'
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
            'id' => Yii::t('simialbi/kanban/model/comment', 'Id'),
            'task_id' => Yii::t('simialbi/kanban/model/comment', 'Task'),
            'text' => Yii::t('simialbi/kanban/model/comment', 'Text'),
            'created_by' => Yii::t('simialbi/kanban/model/comment', 'Created by'),
            'created_at' => Yii::t('simialbi/kanban/model/comment', 'Created at')
        ];
    }

    /**
     * Get author
     * @return IdentityInterface
     */
    public function getAuthor()
    {
        return call_user_func([Yii::$app->user->identityClass, 'findIdentity'], $this->created_by);
    }

    /**
     * Get associated task
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Get associated bucket
     * @return \yii\db\ActiveQuery
     */
    public function getBucket()
    {
        return $this->hasOne(Bucket::class, ['id' => 'task_id'])->via('task');
    }

    /**
     * Get associated board
     * @return \yii\db\ActiveQuery
     */
    public function getBoard()
    {
        return $this->hasOne(Board::class, ['id' => 'task_id'])->via('bucket');
    }
}
