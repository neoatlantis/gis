<?php  
//Header('Content-type: image/png;Charset:utf-8'); //声明图片 
require(dirname(__FILE__) . '/php/plotter.php');

$config = array(
    "size-width"=>800,
    "size-height"=>600,

    "r"=>100,
    "center"=>array("latitude"=>0.0, "longitude"=>0.0),

    "elements"=>array(
        array("type"=>"label", "size"=>4, "text"=>"Beijing", "latitude"=>39.0, "longitude"=>115.0),/*
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>10, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>20, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>30, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>40, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>50, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>60, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>70, "longitude"=>115.0),
        array("type"=>"cross", "size"=>12, "width"=>1, "latitude"=>80, "longitude"=>115.0),*/
    ),
);

//////////////////////////////////////////////////////////////////////////////
require(dirname(__FILE__) . '/php/do.php');
