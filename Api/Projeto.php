<?php
/**
 * Created by PhpStorm.
 * User: moyses-oliveira
 * Date: 02/05/19
 * Time: 16:40
 */
namespace BecauseRevista\Api;

class Projeto
{

    public function __construct()
    {
        add_action('init', array($this,'getCollection'));
        add_action('the_content', array($this,'viewCollection')) ;
    }

    public static function getInfo($id) {
        $response = static::getData('project-info/' . $id, []);
        if(is_array($response))
            return $response;

        echo '<div style="color: red;">ERRO</div>';
        echo $response;
        die;
    }

    public static function getCollection($_params) {
        if($_params instanceof \WP_REST_Request)
            $_params = [];

        $params = array_merge($_GET, $_params);
        if(!isset($params['page']))
            $params['page'] = 1;

        $response = static::getData('project/collection', $params);
        if(count($_params))
            return $response;

        if(is_array($response))
            return (new \WP_REST_Response($response));

        return $response;
    }

    private static function getData($endpoint, $params = []) {
        $host = REST_REVISTA_TRANSFORMANDO;
        $url = "$host/api/$endpoint";
        $http_params = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url?$http_params");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result, true);
        return !$json ? $result : $json;
    }
}