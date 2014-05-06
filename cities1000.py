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

    """
        'STLMT': 0,
        'PPLW':  0,
        'PPLCH': 0,
        'PPLH':  1, 
        'PPLF':  4, 
        'PPLR':  5, 
        'PPLG':  11,
        'PPLS':  13, 
        'PPLQ':  17, 
        'PPLC':  240, 
        'PPLL':  243,
        'PPLX':  1154, 
        'PPLA':  3477,
        'PPLA2': 12861,
        'PPLA3': 26200,
        'PPLA4': 26467
        'PPL':   71262, 
    """
    if not cityRole in ['PPLC']: continue

    print '\t'.join(['city', cityLng, cityLat, '5', cityName])
