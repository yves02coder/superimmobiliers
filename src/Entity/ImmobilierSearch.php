<?php


namespace App\Entity;


use Symfony\Component\Form\AbstractType;

class ImmobilierSearch extends AbstractType
{
    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var int|null
     */
    private $minSurface;

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return ImmobilierSearch
     */
    public function setMaxPrice(?int $maxPrice): ImmobilierSearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    /**
     * @param int|null $minSurface
     * @return ImmobilierSearch
     */
    public function setMinSurface(?int $minSurface): ImmobilierSearch
    {
        $this->minSurface = $minSurface;
        return $this;
    }

}