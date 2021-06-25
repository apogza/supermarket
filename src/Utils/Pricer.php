<?php

namespace App\Utils;

use App\Entity\Product;
use App\Entity\Discount;

class Pricer
{
    private $productsArray;
    private $productNameToIdArray;
    private $productDiscountArray;

    public function __construct($products, $discounts)
    {
        $this->productsArray = $this->getProductsArray($products);
        $this->productNameToIdArray = $this->getProductNameToIdArray($products);
        $this->productDiscountArray = $this->getProductDiscountArray($discounts);
    }

    public function getPrice($productList)
    {
        $totalPrice = 0;
        $productCount = $this->getProductCount($productList);

        foreach ($productCount as $prodId => $count)
        {
            $product = $this->productsArray[$prodId];
            $productTotalPrice = $this->getTotalProductPrice($prodId, $count, $product->getPrice());

            $totalPrice +=  $productTotalPrice;
        }

        return $totalPrice;
    }

    public function getTotalProductPrice($prodId, $count, $price)
    {
        $totalProductPrice = $count * $price;
        if (array_key_exists($prodId, $this->productDiscountArray))
        {
            $discount = $this->productDiscountArray[$prodId];

            $totalProductPrice = intdiv($count, $discount->getunits()) * $discount->getPrice();
            $totalProductPrice += ($count % $discount->getUnits()) * $price;
        }

        return $totalProductPrice;
    }

    private function getProductCount($productList)
    {
        $productCountArray = [];

        for ($i = 0; $i < strlen($productList); $i++)
        {
            $currentChar = $productList[$i];

            if (array_key_exists($currentChar, $this->productNameToIdArray))
            {
                $currentProductId = $this->productNameToIdArray[$currentChar];
                if (array_key_exists($currentProductId, $productCountArray))
                {
                    $productCountArray[$currentProductId] += 1;
                }
                else
                {
                    $productCountArray[$currentProductId] = 1;
                }
            }
        }

        return $productCountArray;
    }

    private function getProductsArray($products)
    {
        $productsArray = [];

        foreach ($products as $product)
        {
            $productsArray[$product->getId()] = $product;
        }

        return $productsArray;
    }

    private function getProductNameToIdArray($products)
    {
        $productNameToIdArray = [];

        foreach ($products as $product)
        {
            $productNameToIdArray[$product->getName()] = $product->getId();
        }

        return $productNameToIdArray;
    }

    private function getProductDiscountArray($productDiscounts)
    {
        $productDiscountArray = [];
        foreach ($productDiscounts as $discount)
        {
            $currentProduct = $discount->getProdId();
            $productDiscountArray[$currentProduct->getId()] = $discount;
        }

        return $productDiscountArray;
    }
}
