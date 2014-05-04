<?php  
//Header('Content-type: image/png;Charset:utf-8'); //声明图片 
require('php/plotter.php');

$config = array(
    "size-width"=>800,
    "size-height"=>600,

    "r"=>100,
    "center"=>array("latitude"=>0.0, "longitude"=>0.0),

    "elements"=>array(
        array("type"=>"label", "size"=>4, "text"=>"Beijing", "latitude"=>39.0, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>10, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>20, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>30, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>40, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>50, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>60, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>70, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>80, "longitude"=>115.0),
    ),
);

//////////////////////////////////////////////////////////////////////////////

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
$projector = new projector(new geoPoint($config['center']['latitude'], $config['center']['longitude']), $config['r']);
$map = new map($config['size-width'], $config['size-height']);

foreach($config["elements"] as $element){
    $geoPoint = new geoPoint($element['latitude'], $element['longitude']);
    $mapPoint = $geoPoint->project($projector);

    if($element['type'] == 'label'){
        $map->write($mapPoint, $element['size'], $element['text'], $map->colors['white']);
    };

    if($element['type'] == 'cross'){
        $map->cross($mapPoint, $element['size'], $element['width'], $map->colors["white"]);
    };
};




/****************************** Center Cross ********************************/
$map->cross(new mapPoint(0,0), 16, 2, $map->colors['blue']);
$map->output();
