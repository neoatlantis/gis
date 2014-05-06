#!/usr/bin/python
# -*- coding: utf-8

import sys
import os

import _gis_

region = _gis_.parseArgvAsRegion()

##############################################################################

filename = "data/cities1000.txt"

fobj = open(filename, 'r')

while True:
    line = fobj.readline()
    if not line: break
    lineSplit = line.split('\t')

    if len(lineSplit) != 19: continue
    
    latitude, longitude = float(lineSplit[4]), float(lineSplit[5])

    if not _gis_.within(region, (longitude, latitude)):
        continue

    cityName = lineSplit[2]
    cityLat = lineSplit[4]
    cityLng = lineSplit[5]
    cityRole = lineSplit[7]

    print '\t'.join(['city', cityLng, cityLat, '5', cityName])
