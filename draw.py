#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import subprocess

center = (39.9075 + 0.5/60*20, 116.39)
diff = 0.5 / 60 * 100
netdiff = 0.5 / 60 * 10

config = {
    'size-width': 800,
    'size-height': 600,
    'r': 50000,
    'center': {'latitude':center[0], 'longitude':center[1]},
    'elements': [
        {'type':"cross-net", "size":14, "width":1, "latitude":center[0], "longitude":center[1], 'x-step':netdiff, 'y-step':netdiff, 'n':10}
    ]
}

command = ['python', 'cities1000.py', str(center[1]), str(diff), str(center[0]), str(diff)]
found = subprocess.check_output(command)

found = found.split('\n')

elements = []
for each in found:
    split = each.split('\t')
    if len(split) < 10: continue

    city_names = split[3].split(',')
    city_name = split[2] 
#    city_name = city_names[-1]

    city_size = split[7]
#    if not city_size in ['PPLA3','PPLA2']: continue

    size = 4
    if city_size == 'PPLC': 
        size = 12

    elements.append({'type':'label', 'size':size, 'text':city_name, 'latitude':float(split[4]), 'longitude':float(split[5])})

config['elements'] += elements


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
os.remove('____temp____.php')
os.remove('some.png')
