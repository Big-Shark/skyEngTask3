<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\ResponseFactory;

class DataProvider implements DataProviderInterface
{
    private $host;
    private $user;
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct($host, $user = null, $password = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function get($path)
    {
        $auth = $this->getAuth();
        $header = '';
        if($auth) {
            $header .= "Authorization: Basic ".$auth;
        }
        $opts = [
            'http'=> [
                [
                    'header' => $header,
                ],
                'method' => 'GET',
            ]
        ];
        $context = stream_context_create($opts);

        $content = file_get_contents($this->host.$path, false, $context);
        if($content === false) {
            throw new DataProviderException('Whoops');
        }

        return new TextResponse($content);
    }

    private function getAuth()
    {
         if($this->user === null) {
             return null;
         }
        return  base64_encode($this->user.':'.$this->password);
    }
}