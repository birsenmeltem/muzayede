<?php
class Pagination
{
    public $link;
    public $table;
    public $start;
    public $where = NULL;
    public $pg = 1;
    public $count = 0;
    public $select = '*';
    public $search = false;
    public $showType = 1;
    public $before = null;
    public $after = null;

    public function startPage()
    {
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
        $ara = $this->search ? '&' : '?';
        $x = 100;


        if($this->count > $this->perpage) :
            $tmp = '<ul class="paging">';
            $lastP = ceil($this->count/$this->perpage);
            if($this->pg==1) $tmp .= "<li class='active'><a href='{$this->link}{$ara}pg=1'>1</a></li>";
            else $tmp .= "<li><a href='{$this->link}{$ara}pg=1'>1</a></li>";
            if($this->pg-$x > 2) {
                $tmp .= "<li><a>...</a></li>";
                $i = $this->pg-$x;
            } else {
                $i = 2;
            }
            // +/- $x sayfalarý yazdýr
            for($i; $i<=$this->pg+$x; $i++) {
                if($i==$this->pg) $tmp .= "<li class='active'><a href='{$this->link}{$ara}pg=".$i."'>$i</a></li>";
                else $tmp .= "<li><a href='{$this->link}{$ara}pg=".$i."'>$i</a></li>";
                if($i==$lastP) break;
            }
            // "..." veya son sayfa
            if($this->pg+$x < $lastP-1) {
                $tmp .= "<li><a>...</a></li>";
                $tmp .= "<li><a href='{$this->link}{$ara}pg=".$lastP."'>$lastP</a></li>";
            } elseif($this->pg+$x == $lastP-1) {
                $tmp .= "<li><a href='{$this->link}{$ara}pg=".$lastP."'>$lastP</a></li>";
            }
            $tmp .= "</ul>";

            if($this->pg!=1)  $this->before = '<a href="'.rtrim($this->link,'/').$ara.'pg='.(max(1,($this->pg-1))).'"><i class="ficon-left"></i>Önceki</a>';
            if($this->pg!=$lastP) $this->after = '<a href="'.rtrim($this->link,'/').$ara.'pg='.(max(1,($this->pg+1))).'">Sonraki<i class="ficon-right"></i></a>';
        endif;
        return $tmp;
    }
}
?>