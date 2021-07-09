<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "product".
 *
 * @property int $sku
 * @property string $image
 * @property string $name
 * @property int $quantity
 * @property string $waretype
 */
class PostSearch extends Product
{

    public $searchstring;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['searchstring'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sku' => 'Sku',
            'image' => 'Image',
            'name' => 'Name',
            'quantity' => 'Quantity',
            'waretype' => 'Waretype',
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //$query->andFilterWhere(['id' => $this->id]);
        //$query->andFilterWhere(['like', 'name', $this->name])
               //->andFilterWhere(['like', 'sku', $this->sku]);

        $query->orFilterWhere(['like', 'sku', $this->searchstring])
               ->orFilterWhere(['like', 'name', $this->searchstring]);

        return $dataProvider;
    }
}
