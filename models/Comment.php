<?php

namespace alexander777hub\crop\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id
 * @property int $userform_id
 * @property int $user_id
 * @property string $text
 * @property string|null $created_at
 *
 * @property Userform $userform
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userform_id', 'user_id', 'text'], 'required'],
            [['userform_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['created_at'], 'safe'],
            [['userform_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userform::className(), 'targetAttribute' => ['userform_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userform_id' => Yii::t('app', 'Userform ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'text' => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Userform]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserform()
    {
        return $this->hasOne(Userform::className(), ['id' => 'userform_id']);
    }
}
