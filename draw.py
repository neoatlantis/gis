#!/usr/bin/python
# -*- coding: utf-8 -*-
import os
import subprocess
import _gis_

center = (126.39, 39.9075 - 10)
diff = 0.5 / 60 * 1000
netdiff = 0.5 / 60 * 10

width = 800
height = 600
r = 3000

#{'type':"cross-net", "size":14, "width":1, "latitude":center[0], "longitude":center[1], 'x-step':netdiff, 'y-step':netdiff, 'n':10}

content = '\n'.join(['width\t%d' % width, 'height\t%d' % height, 'r\t%d' % r, 'center\t%f\t%f' % (center[0], center[1]), '\n'])

content += _gis_.checkPythonOutput('cities1000', center, diff) + '\n'
content += _gis_.checkPythonOutput('ne_10m', center, diff) + '\n'

##############################################################################
output = """<?php
require(dirname(__FILE__) . '/php/plotter.php');

$content = <<<DATA
%s
DATA;
require(dirname(__FILE__) . '/php/do.php');""" % (content)

open('____temp____.php', 'w+').write(output)

os.system('php ____temp____.php > some.png')
os.system('ristretto some.png')
#os.remove('____temp____.php')
#os.remove('some.png')
