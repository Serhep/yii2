<?php

namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * This is the model class for table "product".
 *
 * @property int $sku
 * @property string $image
 * @property string $name
 * @property int $quantity
 * @property string $waretype
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
        /**
     * Вспомогательный атрибут для загрузки изображения товара
     */
    public $upload;

    /**
     * Вспомогательный атрибут для удаления изображения товара
     */
    public $remove;

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
            [['sku', 'name', 'quantity', 'waretype'], 'required'],
            [['sku', 'quantity'], 'integer'],
            [['name', 'waretype'], 'string', 'max' => 50],
            ['image', 'image', 'extensions' => 'png, jpg, gif'],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sku' => 'SKU',
            'image' => 'Изображение',
            'name' => 'Название',
            'quantity' => 'Кол-во на складе',
            'waretype' => 'Тип товара',
        ];
    }

    /**
     * Загружает файл изображения товара
     */
    public function uploadImage() {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить еще три размера
                $large = Yii::getAlias('@webroot/images/products/large/' . $name);
                Image::thumbnail($source, 800, null)->save($large, ['quality' => 100]);
                $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
                Image::thumbnail($source, 500, null)->save($medium, ['quality' => 95]);
                $small = Yii::getAlias('@webroot/images/products/small/' . $name);
                Image::thumbnail($source, 100, null)->save($small, ['quality' => 90]);
                return $name;
            }
        }
        return false;
    }

        /**
     * Удаляет старое изображение при загрузке нового
     */
    public static function removeImage($name) {
        if (!empty($name)) {
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $large = Yii::getAlias('@webroot/images/products/large/' . $name);
            if (is_file($large)) {
                unlink($large);
            }
            $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
            if (is_file($medium)) {
                unlink($medium);
            }
            $small = Yii::getAlias('@webroot/images/products/small/' . $name);
            if (is_file($small)) {
                unlink($small);
            }
        }
    }

    /**
     * Удаляет изображение при удалении товара
     */
    public function afterDelete() {
        parent::afterDelete();
        self::removeImage($this->image);
    }
}
