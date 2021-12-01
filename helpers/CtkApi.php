<?php


namespace app\helpers;


use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class CtkApi
{
    const SERVER = 'http://127.0.0.1';
    const PORT = 8601;
    const CONFIG = ["nerlimit" => 2, "k" => 2, "npts" => 2, "older" => 1];
    private $_client;

    public function __construct()
    {
        $this->_client = new Client();
    }

    public function getDictionary($ctk_id, $options = [])
    {
        $params = ArrayHelper::merge(self::CONFIG, $options);
        return $this->_client->createRequest()
            ->setMethod('GET')
            ->setUrl(ArrayHelper::merge([self::SERVER.":".self::PORT."/dictionary/$ctk_id"], $params))
            ->send()
            ->getData();
    }

    public function getSample()
    {
        return $this->_client->createRequest()
            ->setMethod('GET')
            ->setUrl(self::SERVER.":".self::PORT."/sample")
            ->send()
            ->getData();
    }

    public function getParagraph($ctk_id)
    {
        return $this->_client->createRequest()
            ->setMethod('GET')
            ->setUrl(self::SERVER.":8602/fetch/$ctk_id")
            ->send()
            ->getData();
    }
}