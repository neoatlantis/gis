#!/usr/bin/python
# -*- coding: utf-8

import sys
import os

if len(sys.argv) < 5:
    print "Usage: python cities1000.py <Longitude> <deltaLongitude> <Latitude> <deltaLatitude>"
    sys.exit(1)

lg, dlg, la, dla = sys.argv[1:5]
lg = float(lg)
dlg = abs(float(dlg))
la = float(la)
dla = abs(float(dla))


borderS = max(la - dla, -90)
borderN = min(la + dla, 90)

borderL = []
borderW, borderE = lg - dlg, lg + dlg
if borderW >= -180 and borderE <= 180:
    borderL.append((borderW, borderE))
elif borderE > 180:
    borderL.append((borderW, 180.0))
    borderL.append((-180.0, borderE - 360.0))
elif borderW < -180:
    borderL.append((-180.0, borderE))
    borderL.append((borderW + 360, 180.0))

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
