<?php

namespace AddUserDinoBundle\Pagination;

class PaginatedCollection
{
    private $items;

    private $total;

    //liczba obiektów na stronie
    private $count;

    private $_links = array();

    /**
     * PaginatedCollection constructor.
     * @param $items
     * @param $total
     */
    public function __construct($items, $total)
    {
        $this->items = $items;
        $this->total = $total;
        $this->count = count($items);
    }

    //rel to np. first, last, prev itp.
    public function addLink($rel, $url)
    {
        $this->_links[$rel] = $url;
    }


}