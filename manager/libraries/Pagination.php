<?php
    class Pagination
    {
        public $link;
        public $start;
        public $perpage = SHOW_LIST_PAGE;
        public $pg = 1;
        public $count = 0;
        public $filter = FALSE;
        public $nextPage;
        public $prevPage;
        public $activePage;

        public function startPage()
        {
            $this->link = rtrim($this->link,'/').'/';
            $this->filter = _GETFilter();
            if(!$this->pg || $this->pg == 1)
            {
                $this->pg = 1;
                $this->start = 0;
            }
            else
            {
                $this->start = $this->perpage*($this->pg-1);
            }

        }

        public function paginations()
        {
            $tmp = '<hr><div class="kt-pagination  kt-pagination--brand"><div class="kt-pagination__toolbar"></div><ul class="kt-pagination__links">
            ';
            if($this->count > $this->perpage) :
                $x = 10;
                $lastP = ceil($this->count/$this->perpage);
                if($this->pg!=1)$tmp .= "<li class='kt-pagination__link--first'><a href='{$this->link}1/?{$this->filter}'><i class='fa fa-angle-double-left kt-font-brand'></i></a></li>";
                if($this->pg!=1):
                    $tmp .= "<li class='kt-pagination__link--next'><a href='{$this->link}".(max(1,($this->pg-1)))."/?{$this->filter}'><i class='fa fa-angle-left kt-font-brand'></i></a></li>";
                    $this->prevPage = "{$this->link}".(max(1,($this->pg-1)))."/?{$this->filter}";
                    endif;
                if($this->pg==1) $tmp .= "<li class='kt-pagination__link--active'><a>1</a></li>";
                else $tmp .= "<li><a href='{$this->link}1/?{$this->filter}'>1</a></li>";
                if($this->pg-$x > 2) {
                    $tmp .= "<li><a>...</a></li>";
                    $i = $this->pg-$x;
                } else {
                    $i = 2;
                }
                // +/- $x sayfalarý yazdýr
                for($i; $i<=$this->pg+$x; $i++) {
                    if($i==$this->pg) :
                    $tmp .= "<li class='kt-pagination__link--active'><a>$i</a></li>";
                    $this->activePage = "{$this->link}".$i."/?{$this->filter}";
                     else : $tmp .= "<li><a href='{$this->link}".$i."/?{$this->filter}'>$i</a></li>"; endif;
                    if($i==$lastP) break;
                }
                // "..." veya son sayfa
                if($this->pg+$x < $lastP-1) {
                    $tmp .= "<li><a>...</a></li>";
                    $tmp .= "<li><a href='{$this->link}".$lastP."/?{$this->filter}'>$lastP</a></li>";
                } elseif($this->pg+$x == $lastP-1) {
                    $tmp .= "<li><a href='{$this->link}".$lastP."/?{$this->filter}'>$lastP</a></li>";
                }
                if($this->pg!=$lastP):
                    $tmp .= "<li class='kt-pagination__link--prev'><a href='{$this->link}".(max(1,($this->pg+1)))."/?{$this->filter}'><i class='fa fa-angle-right kt-font-brand'></i></a></li>";
                    $this->nextPage = "{$this->link}".(max(1,($this->pg+1)))."/?{$this->filter}";
                    endif;
                if($this->pg!=$lastP)$tmp .= "<li class='kt-pagination__link--last'><a href='{$this->link}".($lastP)."/?{$this->filter}'><i class='fa fa-angle-double-right kt-font-brand'></i></a></li>";
                endif;
            $tmp .= "</ul></div>";

            return $tmp;
        }
    }
?>