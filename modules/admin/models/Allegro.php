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
 * @property int $price
 * @property string $waretype
 */
class Allegro extends Product
{

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
            [['sku', 'quantity', 'price'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }


    public function scenarios()
    {
        return Model::scenarios();
    }

    public function array_json($resp)
    {
        $js = substr($resp, strpos($resp, '{'));

        return json_decode($js, true);
    }

    public function prod_json()
    {

        $json_prod = "{\"name\":\"ware1\",
                        \"stock\":{\"available\":2, 
                                    \"unit\":\"UNIT\"
                                    }, 
                        \"category\":{\"id\":\"14737\"}, 
                        \"payments\":{\"invoice\":\"NO_INVOICE\"},
                        \"location\":{\"city\":\"NN\",
                                        \"countryCode\":\"RU\",
                                        \"postCode\":\"603000\",
                                        \"province\":\"NN\"},
                        \"description\":{\"sections\":[
                                            {\"items\":[
                                                        {\"type\":\"TEXT\",
                                                          \"content\":\"<p>Very good product!</p>\" 
                                                        }]}]
                                        },
                        \"sellingMode\":{\"format\":\"BUY_NOW\",
                                        \"price\":{\"amount\":1000,\"currency\":\"PLN\"},
                                        \"startingPrice\":null,
                                        \"minimalPrice\":null},
                        \"delivery\": {\"additionalInfo\": \"Delivery\",
                                        \"handlingTime\": \"P3D\",                                       
                                        \"shipmentDate\": null
                                        },
                        \"parameters\":[{\"id\": \"11323\", 
                                        \"valuesIds\":[\"11323_1\"]
                                        },
                                        {\"id\": \"224017\",
                                        \"values\":[\"1237\"]
                                        },
                                        {\"id\": \"130411\",
                                        \"valuesIds\":[\"130411_2\"]
                                        },
                                        {\"id\": \"25929\",
                                        \"valuesIds\":[\"25929_1\"]
                                        },
                                        {\"id\": \"54\",
                                        \"valuesIds\":[\"54_4\"]
                                        },
                                        {\"id\": \"127522\",
                                        \"valuesIds\":[\"127522_14\"]
                                        },
                                        {\"id\": \"215750\",
                                        \"valuesIds\":[\"215750_271110\"]
                                        },
                                        {\"id\": \"1294\",
                                        \"valuesIds\":[\"1294_1\"]
                                        }]
                                                                        
                    }";//state:new,manuf:1237,gender:man,brand:al,size:M,color:white,type:training,kind:open
        
        return $json_prod;
    }

    public function output_prod($prod, $token)
    {

        $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/offers");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $prod);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
                                                        "Accept: application/vnd.allegro.public.v1+json",
                                                        "Content-Type: application/vnd.allegro.public.v1+json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            curl_close($ch);

        return $response;
    }

    public function order_list($token)
    {

        $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/shipping-rates");
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
                                                        "Accept: application/vnd.allegro.public.v1+json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            curl_close($ch);

        return $response;
    }

    public function get_cat($cat_id, $token)
    {

        $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/categories/?parent.id=".$cat_id);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
                                                        "Accept: application/vnd.allegro.public.v1+json",
                                                        'Accept-Language: en-US'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            curl_close($ch);

        return $response;
    }

    public function get_params($cat_id, $token)
    {

        $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/categories/".$cat_id."/parameters");
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
                                                        "Accept: application/vnd.allegro.public.v1+json",
                                                        'Accept-Language: en-US'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            curl_close($ch);

        return $response;
    }

    public function get_offers($token)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/offers");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
            "Accept: application/vnd.allegro.public.v1+json",
            'Accept-Language: en-US'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function del_offer($offer_id, $token)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/offers/".$offer_id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
            "Accept: application/vnd.allegro.public.v1+json",
            'Accept-Language: en-US'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
    public function active_offer($id, $offer, $token)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.allegro.pl.allegrosandbox.pl/sale/product-offers/".$id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $offer);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$token,
                                                            "Accept: application/vnd.allegro.beta.v2+json",
                                                            "Content-Type: application/vnd.allegro.beta.v2+json"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }



}