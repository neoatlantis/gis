<?php
$mapCenter = $mapR = $mapWidth = $mapHeight = null;

$content = explode("\n", $content);
$headRead = True;
$contentMax = count($content);
for($i=0; $i<$contentMax;$i++){
    if(!$content[$i] = trim($content[$i])) break;
    $split = explode("\t", $content[$i]);
    
    $cmd = $split[0];
    if($headRead){
        if('center' == $cmd)
            $mapCenter = new geoPoint($split[1], $split[2]);
        else if('r' == $cmd)
            $mapR = $split[1];
        else if('width' == $cmd)
            $mapWidth = $split[1];
        else if('height' == $cmd)
            $mapHeight = $split[1];
    };
};

$projector = new projector($mapCenter, $mapR);
$map = new map($mapWidth, $mapHeight);

for($i;$i<$contentMax;$i++){
    if(!($content[$i] = trim($content[$i]))) continue;
    $split = explode("\t", $content[$i]);
    $cmd = $split[0];

    $geoPoint = new geoPoint($split[1], $split[2]);
    $pos = $geoPoint->project($projector);

    if('label' == $cmd){
        // label Longitude Latitude Size Text
//        var_dump($pos->x);
        $map->dot($pos, $split[3], $map->colors['red']);
        $map->write($pos, 4, $split[4], $map->colors['white']);
    } else if('cross' == $cmd){
        // cross Longitude Latitude Size Width
        $map->cross($pos, $split[3], $split[4], $map->colors["white"]);
    } else if('line' == $cmd){
        $lastPointProjected = $pos;
        for($j=3; $j<count($split); $j+=2){
            $newPoint = new geoPoint($split[$j], $split[$j+1]);
            $newPointProjected = $newPoint->project($projector);
            $map->line($lastPointProjected, $newPointProjected, $map->colors["red"]);
            $lastPointProjected = $newPointProjected;
        };
    }
    /*else if('cross-net' == $cmd){
        $xstep = $element['x-step'];
        $ystep = $element['y-step'];
        $num = abs($element['n']);
        for($i=-$num;$i<=$num;$i++){
            for($j=-$num;$j<=$num;$j++){
                $geopos = new geoPoint($element['latitude'] + $i * $ystep, $element['longitude'] + $j * $xstep);
                $pos = $geopos->project($projector);
                $map->cross($pos, $element['size'], $element['width'], $map->colors['white']);
            };
        };
    };*/

};




/****************************** Center Cross ********************************/
$map->cross(new mapPoint(0,0), 16, 2, $map->colors['blue']);
$map->output();
