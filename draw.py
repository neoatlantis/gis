#!/usr/bin/python
# -*- coding: utf-8 -*-

def convert(d):
    ret = ''
    if type(d) == dict:
        ret += 'array('
        for key in d:
            ret += '"' + key + '"=>' + convert(d[key]) + ','
        ret += ')'
    elif type(d) == str:
        ret = '"' + d + '"'
    elif type(d) == list:
        ret += 'array('
        for item in d:
            ret += convert(item) + ','
        ret += ')'
    else:
        ret = str(d)
    return ret

config = convert({
    'size-width': 800,
    'size-height': 600,
    'r': 1000,
    'center': {'latitude':40.0, 'longitude':110.0},
    'elements': [
        {'type':'label', 'size':4, 'text':'Beijing', 'latitude':40.0, 'longitude': 119.0},
        {'type':'label', 'size':4, 'text':'Tianjin', 'latitude':39.0, 'longitude': 120.0},
    ]
})

output = """<?php
require(dirname(__FILE__) . '/php/plotter.php');

$config = %s;
require(dirname(__FILE__) . '/php/do.php');""" % (config)

open('____temp____.php', 'w+').write(output)

import os
os.system('php ____temp____.php > some.png')
os.system('ristretto some.png')
