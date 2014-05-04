<?php  
//Header('Content-type: image/png;Charset:utf-8'); //声明图片 

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
$pi = 3.141592653589793238462643383279502884197169399375105820974944592307816;
function project($center, $point){
    global $config, $pi;
    $dLong = $point["longitude"] - $config["center"]["longitude"];

    $dX = $dLong / 180.0 * $pi * $config["r"];
    $dY = $config["r"]
        * (
            tan($point["latitude"] / 180 * $pi) 
            - tan($center["latitude"] / 180 * $pi)
        )
    ;
    return array("dX"=>$dX, "dY"=>$dY);
};

function toGraphCoordinates($point){
    global $config;
    return array(
        "X"=>$point["X"] + $config["size-width"] / 2,
        "Y"=>$config["size-height"] / 2 - $point["Y"],
    );
};

//////////////////////////////////////////////////////////////////////////////

function drawCross($im, $center, $size, $width, $color){
    $size = $size / 2;
    $width = $width / 2;
    $cx = $center['X'];
    $cy = $center['Y'];

    $point1 = array('X'=>$cx-$size,'Y'=>$cy-$width);
    $point2 = array('X'=>$cx+$size,'Y'=>$cy+$width);
    imagefilledrectangle($im, $point1['X'], $point1['Y'], $point2['X'], $point2['Y'], $color);
    $point1 = array('X'=>$cx-1,'Y'=>$cy-$size);
    $point2 = array('X'=>$cx+1,'Y'=>$cy+$size);
    imagefilledrectangle($im, $point1['X'], $point1['Y'], $point2['X'], $point2['Y'], $color);
};

//////////////////////////////////////////////////////////////////////////////

$im = imagecreate($config["size-width"],$config["size-height"]); 

//get color.
$bg = imagecolorallocate($im,0,0,0); 
$colors = array(
    "red"=>imagecolorallocate($im,255,0,255), 
    "white"=>imagecolorallocate($im,255,255,255),
    "blue"=>imagecolorallocate($im, 0, 0, 255),
);

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

$center = $config['center'];
foreach($config["elements"] as $element){
    $diff = project($center, array('latitude'=>$element['latitude'], 'longitude'=>$element['longitude']));
    $point = toGraphCoordinates(array(
        "X"=>0 + $diff['dX'],
        "Y"=>0 + $diff['dY'],
    ));

    if($element['type'] == 'label'){
        imagestring($im, $element['size'], $point['X'], $point['Y'], $element['text'], $colors["white"]);
    };

    if($element['type'] == 'cross'){
        drawCross($im, $point, $element['size'], $element['width'], $colors["white"]);
    };
};




/****************************** Center Cross ********************************/
drawCross($im, toGraphCoordinates(array('X'=>0, 'Y'=>0)), 16, 2, $colors['blue']);
//////////////////////////////////////////////////////////////////////////////
imagepng($im); 
imagedestroy($im); 
