<?php

namespace App\ThirdParty;

class MyPaper
{

    private $page;
    private $pagesize;
    private $total;
    private $first;
    private $last;
    private $totalPage;

    public function __construct(int $page = 1, int $pageSize = 15, int $total = 0)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->total = $total;
        $this->totalPage = ceil($this->total / $this->pageSize);

        $this->first = $this->page - 2 > 0 ? $this->page - 2 : 1;
        $this->last = $this->page + 2 <= $this->totalPage ? $this->page + 2 : (int) $this->totalPage;

    }

    public function createLinks($route_name, $sep = '_', $fix = '')
    {

        if ($this->total == 0) {
            return '';
        }

        $totalPage = ceil($this->total / $this->pageSize);

        if ($totalPage == 1) {
            return '';
        }

        $html = '';
        $html .= '<ul class="pagination justify-content-center pt-3">';

        if ($this->first > 1) {
            $url = $this->getLink($route_name, $sep, 1, $fix);
            $html .= '<li class="page-item"><a href="' . $url . '" class="page-link"><span aria-hidden="true"><<</span></a></li>';
            $url = $this->getLink($route_name, $sep, $this->page - 1, $fix);
            $html .= '<li class="page-item"><a href="' . $url . '" class="page-link"><span aria-hidden="true"><</span></a></li>';
        }

        for ($i = $this->first; $i <= $this->last; $i++) {
            $url = $this->getLink($route_name, $sep, $i, $fix);
            if ($this->page == $i) {
                $html .= '<li class="page-item active"><a href="' . $url . '" class="page-link">' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a href="' . $url . '" class="page-link">' . $i . '</a></li>';
            }

        }

        if ($this->page < $this->last) {
            $url = $this->getLink($route_name, $sep, $this->page + 1, $fix);
            $html .= '<li class="page-item"><a href="' . $url . '"  class="page-link"><span aria-hidden="true">></span></a></li>';
            $url = $this->getLink($route_name, $sep, $this->totalPage, $fix);
            $html .= '<li class="page-item"><a href="' . $url . '"  class="page-link"><span aria-hidden="true">>></span></a></li>';
        }
        $html .= '</ul>';

        return $html;

    }

    private function getLink($route_name, $sep, $page, $fix = '')
    {
        if ($page > 1) {
            $url = my_site_url($route_name . $sep . $page) . $fix;
        } else {
            $url = my_site_url($route_name) . $fix;
        }
        return $url;
    }

}
