<?php

namespace App\Tests\Utils;

use App\Entity\Product;
use App\Entity\Discount;
use App\Utils\Pricer;
use PHPUnit\Framework\TestCase;


class PricerTest extends TestCase
{
    private $pricer;

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

        $productArray = [$productA, $productB, $productC, $productD];

        $discountProductA = new Discount();
        $discountProductA->setProdId($productA);
        $discountProductA->setUnits(3);
        $discountProductA->setPrice(130);

        $discountProductB = new Discount();
        $discountProductB->setProdId($productB);
        $discountProductB->setUnits(2);
        $discountProductB->setPrice(45);

        $discountsArray = [$discountProductA, $discountProductB];
        $this->pricer = new Pricer($productArray, $discountsArray);
    }

    public function testSingleA()
    {
        $price = $this->pricer->getPrice('A');
        $this->assertSame(50.0, $price);
    }

    public function testSingleAAndB()
    {
        $price = $this->pricer->getPrice('AB');
        $this->assertSame(80.0, $price);
    }

    public function testSingleAll()
    {
        $price = $this->pricer->getPrice('CDBA');
        $this->assertSame(110.0, $price);
    }

    public function testDoubleA()
    {
        $price = $this->pricer->getPrice('AA');
        $this->assertSame(100.0, $price);
    }

    public function testTripleA()
    {
        $price = $this->pricer->getPrice('AAA');
        $this->assertSame(130.0, $price);
    }

    public function testQuadrupleA()
    {
        $price = $this->pricer->getPrice('AAAA');
        $this->assertSame(180.0, $price);
    }

    public function testFiveA()
    {
        $price = $this->pricer->getPrice('AAAAA');
        $this->assertSame(230.0, $price);
    }

    public function testSixA()
    {
        $price = $this->pricer->getPrice('AAAAAA');
        $this->assertSame(260.0, $price);
    }

    public function testTripleAAndB()
    {
        $price = $this->pricer->getPrice('AAAB');
        $this->assertSame(160.0, $price);
    }

    public function testTripleAAndDoubleB()
    {
        $price = $this->pricer->getPrice('AAABB');
        $this->assertSame(175.0, $price);
    }

    public function testTripleAAndDoubleBAndSingleD()
    {
        $price = $this->pricer->getPrice('AAABBD');
        $this->assertSame(185.0, $price);
    }

    public function testTripleAAndDoubleBAndSingleDShuffle()
    {
        $price = $this->pricer->getPrice('DABABA');
        $this->assertSame(185.0, $price);
    }
}
