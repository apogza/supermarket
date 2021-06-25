<?php

namespace App\Utils;

use App\Entity\Product;

class OrderValidator
{
    private $productsArray;
    public function __construct($products)
    {
        $this->productsArray = $this->createProductsArray($products);
    }

    public function validateOrder($order)
    {
        $errors = [];
        for ($i = 0; $i < strlen($order); $i++)
        {
            if (!array_key_exists($order[$i], $this->productsArray))
            {
                array_push($errors, 'Unknown product ' . $order[$i] . '. Go shop somewhere else!');
            }
        }

        return $errors;
    }

    private function createProductsArray($products)
    {
        $productsArray = [];
        foreach ($products as $prod)
        {
            $productsArray[$prod->getName()] = 1;
        }

        return $productsArray;
    }
}
