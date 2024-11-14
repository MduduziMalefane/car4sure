<?php

class JRecordObject extends JObject
{

    public $count;
    // public $offset;
    public $page;
    public $pages;
    // public $limit;
    public $items;
    public $data;

    public function __construct()
    {
        $this->count = 0;
        //$this->offset = 0;
        $this->page = 0;
        $this->pages = 0;
        //$this->limit = 0;
        $this->data = [];
        $this->items = 0;
    }

}
