<?php

namespace JPF\Paginator;

class Paginator{

    private $page;
    private $perPage;

    private $offset;

    function __construct($page,$perPage)
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->offset();
    }

    public function offset()
    {
        return $this->offset = ($this->page - 1) * $this->perPage;
    }
}