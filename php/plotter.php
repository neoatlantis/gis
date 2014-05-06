<?php
$pi = 3.141592653589793238462643383279502884197169399375105820974944592307816;
$toDeg = 180.0 / $pi;
$toRad = $pi / 180;
/*

$arrowX = array(394,97,399,100,394,103); 
$arrowY = array(197,5,200,0,203,5);                

//画曲线 
for($i=0;$i<380;$i+=0.1){ 
    $x = $i/20; 
    $y =sin($x); 
    $y = 100 + 40*$y; 
    imagesetpixel($im,$i+10,$y,$red); 
} 

//画X轴和Y轴 
imageline($im,0,100,394,100,$white); 
imageline($im,200,5,200,200,$white); 

//画坐标title 
imagestring($im,4,350,110,'XShaft',$white); 

//画箭头 
imagefilledpolygon($im,$arrowX,3,$white); 
imagefilledpolygon($im,$arrowY,3,$white); 
*/

class geoPoint{
    public $lat;
    public $lng;
    public function __construct($lng, $lat){
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
    private $fontfile;

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
            "red"=>imagecolorallocate($this->im,255,0,0), 
            "white"=>imagecolorallocate($this->im,255,255,255),
            "blue"=>imagecolorallocate($this->im, 0, 0, 255),
            "yellow"=>imagecolorallocate($this->im, 255, 255, 0),
        );
        $this->fontfile = dirname(__FILE__) . '/font.ttc';
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
//        imagestring($this->im, $size, $position['x'], $position['y'], $text, $color);
        imagettftext($this->im, $size + 6, 0, $position['x'], $position['y'], $color, $this->fontfile ,$text);
    }

    public function dot($position, $size, $color){
        $position = $this->_coord($position);
        imagefilledarc($this->im, $position['x'], $position['y'], $size, $size, 0, 360, $color, IMG_ARC_PIE);
    }

    public function line($pos1, $pos2, $color){
        $point1 = $this->_coord($pos1);
        $point2 = $this->_coord($pos2);
        imageline($this->im, $point1['x'], $point1['y'], $point2['x'], $point2['y'], $color);
    }

    public function polygon($posAry, $color){
        $count = count($posAry);
        $inputAry = Array();
        for($i=0; $i<$count; $i++){
            $pos = $this->_coord($posAry[$i]);
            $inputAry[] = $pos['x'];
            $inputAry[] = $pos['y'];
        };
        imagefilledpolygon($this->im, $inputAry, $count, $color);
    }
};


/*
 * Takes a map and commands, to plot on a map like a man.
 * Decides colors and other options.
 */
class marker{
    private $map;
    public function __construct($map){
        $this->map = $map;
    }

    private function _line($posAry, $color){
        $lastPos = $posAry[0];
        for($i=1; $i<count($posAry); $i++){
            $newPos = $posAry[$i];
            $this->map->line($lastPos, $newPos, $color);
            $lastPos = $newPos;
        };
    }

    public function city($pos, $size, $text){
        $this->map->dot($pos, $size, $this->map->colors['red']);
        $this->map->write($pos, $size + 2, $text, $this->map->colors['white']);
    }

    public function coastline($posAry){
        $this->_line($posAry, $this->map->colors["yellow"]);
    }

    public function bathymetry($posAry, $deepth){
#        $this->_line($posAry, $this->map->colors["blue"]);
        $this->map->polygon($posAry, $this->map->colors['blue']);
    }

    public function lake($posAry){
#        $this->_line($posAry, $this->map->colors["blue"]);
        $this->map->polygon($posAry, $this->map->colors['blue']);
    }

    public function finish(){
        $this->map->cross(new mapPoint(0,0), 16, 2, $this->map->colors['blue']);
        $this->map->output();
    }
};
