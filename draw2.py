#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import subprocess

import shapefile

centerLat = 0
centerLng = 0

load = ['coastline']

loaded = {}
for each in load:
    loaded[each] = shapefile.Reader('data/ne_10m/ne_10m_%s' % each)

points = []
x = loaded['coastline'].shapes()
for i in xrange(0, 40):
    points += x[i].points

elements = []
for x,y in points:
    elements.append({'type':'cross', 'size':1, 'width':0.5, 'latitude':y, 'longitude':x})
    centerLat += y
    centerLng += x

center = (centerLat / len(elements), centerLng / len(elements))
config = {
    'size-width': 800,
    'size-height': 600,
    'r': 200,
    'center': {'latitude':center[0], 'longitude':center[1]},
    'elements': elements
}

##############################################################################
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
config = convert(config)
output = """<?php
require(dirname(__FILE__) . '/php/plotter.php');

$config = %s;
require(dirname(__FILE__) . '/php/do.php');""" % (config)

open('____temp____.php', 'w+').write(output)

os.system('php ____temp____.php > some.png')
os.system('ristretto some.png')
