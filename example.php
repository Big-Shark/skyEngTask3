<?php

include 'vendor/autoload.php';

$dataProvider = new \App\DataProvider('https://aws.random.cat/');
var_dump((string)$dataProvider->get('meow')->getBody());
var_dump((string)$dataProvider->get('meow')->getBody());

$cacaheDataProvider = new \App\CacheDataProvider($dataProvider, new \Cache\Adapter\PHPArray\ArrayCachePool(), new \Monolog\Logger('cache'));
var_dump((string)$cacaheDataProvider->get('meow')->getBody());
var_dump((string)$cacaheDataProvider->get('meow')->getBody());
var_dump((string)$cacaheDataProvider->get('meow')->getBody());
var_dump((string)$cacaheDataProvider->get('meow')->getBody());
var_dump((string)$cacaheDataProvider->get('meow')->getBody());
