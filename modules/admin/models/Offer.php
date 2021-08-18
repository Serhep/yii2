<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property string $offer_id
 * @property string $name
 * @property string $category_id
 * @property string $category
 * @property int $available
 * @property string $price
 * @property string $format
 * @property string $status
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offer_id', 'name', 'category_id', 'category', 'available', 'price', 'format', 'status', 'structure'], 'safe'],
            [['offer_id', 'name', 'category_id', 'category', 'price', 'format', 'status'], 'string', 'max' => 64],
            [['price', 'format', 'status'], 'string', 'max' => 64],
            [['offer_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'offer_id' => 'Offer ID',
            'name' => 'Name',
            'category_id' => 'Category ID',
            'category' => 'Category',
            'available' => 'Available',
            'price' => 'Price (PLN)',
            'format' => 'Selling format',
            'status' => 'Status'
        ];
    }
}
