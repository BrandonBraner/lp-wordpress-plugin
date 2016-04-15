<?php


namespace Leadpages\Admin\Providers;

use Leadpages\Helpers\LPToken;
use TheLoop\Contracts\HttpClient;

class LeadpagesLoginApi
{
    use LPToken;

    private $token = '';
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function getUserToken($username, $password){
        global $config;

        //set url for http client
        $this->client->setUrl($config['api']['sessions']['new']);

        $args['headers'] = array(
          'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password)
        );

        //set args for http client
        $this->client->setArgs($args);
        //make get request for httpclient
        $response = $this->client->post();

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            return $error_message;
        }elseif($response['response']['code'] > 299){
            $error_message = $response['response']['message'];
            return $error_message;
        } else {
            $token = $this->parseTokenResponse($response);
            $this->setToken($token);
            $this->setAccessToken();
            return $response['response']['message'];
        }
    }

    public function checkUserToken($token){
        global $config;

        $this->client->setUrl($config['api']['sessions']['current']);

        $args = array();

        $args['headers'] = array(
          'LP-Security-Token' => $token
        );

        $this->client->setArgs($args);

        $response = $this->client->get();
        //echo '<pre>';print_r($response);die('12312');

        if ( is_wp_error( $response ) ) {
            return false;
        }elseif($response['response']['code'] != '200'){
            $this->deleteAccessToken();
            return false;
        } else {
            $currentToken = $this->parseTokenResponse($response);
            if($token != $currentToken ){
                $this->deleteAccessToken();
                return false;
            }else{
                return true;
            }
        }

    }

}