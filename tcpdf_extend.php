<?php

class MYPDF extends TCPDF {
    public function addShadow($x,$y,$h,$w,$fill){
        for($i=2;$i>=1;$i-=0.5){
            $this->SetAlpha(0.1-($i*0.02));
            $this->SetFillColor($fill[0], $fill[1], $fill[2]);
            $this->SetDrawColor(84, 84, 84);
            $this->Rect($x+($i/2), $y+($i/2), $h, $w, 'DF');
        }
        $this->SetAlpha(1);
    }
}