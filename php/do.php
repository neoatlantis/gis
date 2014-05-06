<?php
$projector = new projector(new geoPoint($config['center']['latitude'], $config['center']['longitude']), $config['r']);
$map = new map($config['size-width'], $config['size-height']);

foreach($config["elements"] as $element){
    $geoPoint = new geoPoint($element['latitude'], $element['longitude']);
    $pos = $geoPoint->project($projector);

    if($element['type'] == 'label'){
        $map->dot($pos, $element['size'], $map->colors['red']);
        $map->write($pos, 4, $element['text'], $map->colors['white']);
    };

    if($element['type'] == 'cross'){
        $map->cross($pos, $element['size'], $element['width'], $map->colors["white"]);
    };

    if($element['type'] == 'cross-net'){
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
    };
};




/****************************** Center Cross ********************************/
$map->cross(new mapPoint(0,0), 16, 2, $map->colors['blue']);
$map->output();
