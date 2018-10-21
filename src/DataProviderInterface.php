<?php

namespace App;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface DataProviderInterface
{
    /**
     * @param $path
     *
     * @return ResponseInterface
     */
    public function get($path);
}