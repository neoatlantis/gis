#!/usr/bin/python
# -*- coding: utf-8

import sys
import os

import _gis_

borderN, borderS, borderL = _gis_.parseArgvAsRegion()

##############################################################################

filename = "data/cities1000.txt"

fobj = open(filename, 'r')

while True:
    line = fobj.readline()
    if not line: break
    lineSplit = line.split('\t')

    if len(lineSplit) != 19: continue
    
    latitude, longitude = float(lineSplit[4]), float(lineSplit[5])

    # compare latitude
    if not (borderS < latitude and latitude < borderN): continue
    # compare longitude
    inner = False
    for borderW, borderE in borderL:
        if borderW <= longitude and longitude <= borderE:
            inner = True
            break
    if not inner: continue

    print '\t'.join(lineSplit)
