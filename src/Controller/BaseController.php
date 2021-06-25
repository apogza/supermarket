<?php

namespace App\Controller;
use App\Utils\Constants;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected function clearCache(CacheInterface $cache)
    {
        $cache->delete(Constants::ProductsCacheKey);
        $cache->delete(Constants::DiscountsCacheKey);
        $cache->delete(Constants::ValidatorCacheKey);
        $cache->delete(Constants::PricerCacheKey);
    }
}
