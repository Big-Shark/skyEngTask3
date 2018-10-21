<?php

namespace App;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response\TextResponse;

class CacheDataProvider implements DataProviderInterface
{
    private $cache;
    private $logger;
    private $dataProvider;

    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->logger = $logger;
        $this->cache = $cache;

    }

    /**
     * {@inheritdoc}
     */
    public function get($path)
    {
        $cacheKey = $this->getCacheKey($path);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        try {
            $result = $this->dataProvider->get($path);
        } catch (DataProviderException $e) {
            $this->logger->critical('Error');
            throw $e;
        }

        $cacheItem
            ->set($result)
            ->expiresAt(new \DateTimeImmutable('+1 day'));

        $this->cache->save($cacheItem);
        return $result;
    }

    public function getCacheKey($path)
    {
        return md5($path);
    }
}