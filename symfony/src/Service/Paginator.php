<?php

namespace App\Service;

class Paginator
{
    private $_paginator;

    private $_itemCount;

    private $_maxItems;

    private $_maxPages;

    private $_currPage;

    public function __construct($itemCount, $currPage = 1, $maxItems = 10, $maxPages = 20)
    {
        $this->_itemCount = $itemCount;
        $this->_currPage = $currPage;
        $this->_maxItems = $maxItems;
        $this->_maxPages = $maxPages;

        $this->paginate();
    }

    private function paginate(): void
    {
        $this->_paginator = array();
        $page_count = ceil($this->_itemCount / $this->_maxItems);
        $lft = ceil((($this->_currPage - (($this->_maxPages - 1) / 2)) > 0)) ?
            ceil(($this->_currPage - (($this->_maxPages - 1) / 2))) : 1;
        $rgt = ceil((($this->_currPage + (($this->_maxPages - 1) / 2))) < $page_count) ?
            ($this->_currPage + ceil((($this->_maxPages - 1) / 2))) : $page_count;
        if ($lft > 1)
            $this->_paginator[1] = 1;
        if ($lft > 2)
            $this->_paginator['leftPoints'] = '<-';
        for ($i = $lft; $i <= $rgt; $i++) {
            $this->_paginator[$i] = $i;
        }
        if ($rgt < $page_count - 1)
            $this->_paginator['rightPoints'] = '->';
        if ($rgt < $page_count)
            $this->_paginator[(int)$page_count] = (int)$page_count;

    }

    public function getPaginator(): array
    {
        return $this->_paginator;
    }

    public function __toString()
    {
        $result = '';
        $result .= "Страницы: ";
        foreach ($this->_paginator as $page => $value) {
            if (is_integer($value) and $value != $this->_currPage) {
                if ($value != 1)
                    $result .= "{$page}";
                else
                    $result .= "1";
            } elseif ($value != $this->_currPage)
                $result .= "{$value}";
            else
                $result .= "{$value}";
        }

        return $result;
    }

}