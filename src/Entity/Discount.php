<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Product::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $prod_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $units;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdId(): ?Product
    {
        return $this->prod_id;
    }

    public function setProdId(Product $prod_id): self
    {
        $this->prod_id = $prod_id;

        return $this;
    }

    public function getUnits(): ?int
    {
        return $this->units;
    }

    public function setUnits(int $units): self
    {
        $this->units = $units;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
