<?php


namespace App\Data;


use App\Entity\Category;

class SearchData
{
    /**
     * @var string
     */

    public $q='';

    /**
     * @var Category=[];
     */
    public $category;
/**
 * @var null|integer
 */
public $pricemax;
    /**
     * @var null|integer
     */
public $surfacemin;


}