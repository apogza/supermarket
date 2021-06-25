<?php

namespace App\Tests\Utils;

use App\Entity\Product;
use App\Utils\OrderValidator;

use PHPUnit\Framework\TestCase;

class OrderValidatorTest extends TestCase
{
    private $orderValidator;

    protected function setUp(): void
    {
        $productA = new Product();
        $productA->setId(1);
        $productA->setName('A');
        $productA->setPrice(50);

        $productB = new Product();
        $productB->setId(2);
        $productB->setName('B');
        $productB->setPrice(30);

        $productC = new Product();
        $productC->setId(3);
        $productC->setName('C');
        $productC->setPrice(20);

        $productD = new Product();
        $productD->setId(4);
        $productD->setName('D');
        $productD->setPrice(10);

        $products = [$productA, $productB, $productC, $productD];

        $this->orderValidator = new OrderValidator($products);
    }

    public function testNoErrors()
    {
        $order = 'AABBBCCCDDDD';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(0, count($errors));
    }

    public function testOneError()
    {
        $order = 'ABCDE';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(1, count($errors));
    }

    public function testTwoErrors()
    {
        $order = 'ABCDEF';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(2, count($errors));
    }

    public function testTwoErrorsSameProduct()
    {
        $order = 'ABCDEE';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(2, count($errors));
    }

    public function testTwoErrorsShuffle()
    {
        $order = 'ABCFDE';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(2, count($errors));
    }

    public function testTwoErrorsSameProductShuffle()
    {
        $order = 'ABCEDE';

        $errors = $this->orderValidator->validateOrder($order);
        $this->assertSame(2, count($errors));
    }
}
