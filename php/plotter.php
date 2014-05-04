<?php
$pi = 3.141592653589793238462643383279502884197169399375105820974944592307816;
$toDeg = 180.0 / $pi;
$toRad = $pi / 180;

class geoPoint{
    public $lat;
    public $lng;
    public function __construct($lat, $lng){
        $this->lat = $lat;
        $this->lng = $lng;
    }
    public function project($projector){
        return $projector->project($this);
    }
};

class mapPoint{
    public $x;
    public $y;
    public function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    public function addDelta($mapPoint){
        return new mapPoint(
            $this->x + $mapPoint->x,
            $this->y + $mapPoint->y
        );
    }
};

class projector{
    private $r;
    private $geoCenter;
    public function __construct($geoCenter, $radius){
        $this->geoCenter = $geoCenter;
        $this->r = $radius;
    }
    public function project($geoPoint){
        global $pi, $toRad, $toDeg;
        $dLong = $geoPoint->lng - $this->geoCenter->lng;

        $dX = $dLong * $toRad * $this->r;
        $dY = $this->r
            * (
                tan($geoPoint->lat * $toRad) 
                - tan($this->geoCenter->lat * $toRad)
            )
        ;
        return new mapPoint($dX, $dY);
    }
};

class map{
    private $im;
    private $width;
    private $height;

    public $colors;
    
    private function _coord($point){
        return array(
            "x"=>$point->x + $this->width / 2,
            "y"=>$this->height / 2 - $point->y,
        );
    }

    public function __construct($width, $height){
        $this->im = imagecreate($width,$height);
        $this->width = $width;
        $this->height = $height;
        $bg = imagecolorallocate($this->im,0,0,0); 
        $this->colors = array(
            'bg'=>$bg,
            "red"=>imagecolorallocate($this->im,255,0,255), 
            "white"=>imagecolorallocate($this->im,255,255,255),
            "blue"=>imagecolorallocate($this->im, 0, 0, 255),
        );
    }

    public function output(){
        imagepng($this->im);
        imagedestroy($this->im);
    }

    public function cross($center, $size, $width, $color){

        $size = $size / 2;
        $width = $width / 2;
        $cx = $center->x;
        $cy = $center->y;

        $point1 = $this->_coord(new mapPoint($cx-$size, $cy-$width));
        $point2 = $this->_coord(new mapPoint($cx+$size, $cy+$width));
        imagefilledrectangle($this->im, $point1['x'], $point1['y'], $point2['x'], $point2['y'], $color);
        $point1 = $this->_coord(new mapPoint($cx-$width, $cy-$size));
        $point2 = $this->_coord(new mapPoint($cx+$width, $cy+$size));
        imagefilledrectangle($this->im, $point1['x'], $point1['y'], $point2['x'], $point2['y'], $color);
    }

    public function write($position, $size, $text, $color){
        $position = $this->_coord($position);
        imagestring($this->im, $size, $position['x'], $position['y'], $text, $color);
    }
};
