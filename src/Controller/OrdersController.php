<?php

namespace App\Controller;
use App\Entity\Discount;
use App\Entity\Product;
use App\Utils\Pricer;
use App\Utils\Constants;
use App\Utils\OrderValidator;
use App\Controller\BaseController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class OrdersController extends BaseController
{
    private $products;
    private $discounts;

    private $validator;
    private $pricer;

    private function init(CacheInterface $cache): void
    {
        $this->initProducts($cache);

        $this->initDiscounts($cache);

        $this->initValidator($cache);

        $this->initPricer($cache);
    }

    private function initProducts(CacheInterface $cache): void
    {
        $productsItem = $cache->getItem(Constants::ProductsCacheKey);

        if (!$productsItem->isHit())
        {
            $productRepository = $this->getDoctrine()->getRepository(Product::class);
            $this->products = $productRepository->findAll();
            $cache->save($productsItem->set($this->products));
        }
        else
        {
            $this->products = $productsItem->get();
        }
    }

    private function initDiscounts(CacheInterface $cache): void
    {
        $discountsItem = $cache->getItem(Constants::DiscountsCacheKey);

        if (!$discountsItem->isHit())
        {
            $discountRepository = $this->getDoctrine()->getRepository(Discount::class);
            $this->discounts = $discountRepository->findAll();
            $cache->save($discountsItem->set($this->discounts));
        }
        else
        {
            $this->discounts = $discountsItem->get();
        }
    }

    private function initValidator(CacheInterface $cache): void
    {
        $validatorItem = $cache->getItem(Constants::ValidatorCacheKey);

        if (!$validatorItem->isHit())
        {
            $this->validator = new OrderValidator($this->products);
            $cache->save($validatorItem->set($this->validator));
        }
        else
        {
            $this->validator = $validatorItem->get();
        }
    }

    private function initPricer(CacheInterface $cache): void
    {
        $pricerItem = $cache->getItem(Constants::PricerCacheKey);
        if (!$pricerItem->ishit())
        {
            $this->pricer = new Pricer($this->products, $this->discounts);
            $cache->save($pricerItem->set($this->pricer));
        }
        else
        {
            $this->pricer = $pricerItem->get();
        }
    }

    /**
     * @Route("/orders", name="orders")
     */

    public function index(Request $request, CacheInterface $cache): Response
    {
        $this->init($cache);

        $orderPrice = 0;

        $order = $request->request->get('order');
        $orderErrors = [];

        if ($order)
        {
            $order = strtoupper($order);
            $orderErrors = $this->validator->validateOrder($order);
            $orderPrice = $this->pricer->getPrice($order);
        }

        return $this->render('orders/index.html.twig', [
            'products' => $this->products,
            'discounts' => $this->discounts,
            'order' => $order,
            'orderErrors' => $orderErrors,
            'orderPrice' => $orderPrice
        ]);
    }
}
