# -*- coding: utf-8 -*-
import _gis_
import shapefile

region = _gis_.parseArgvAsRegion()

load = ['coastline', 'bathymetry_K_200', 'lakes']

loaded = {}
for each in load:
    loaded[each] = shapefile.Reader('data/ne_10m/ne_10m_%s' % each)

def showLines(shapes, name):
    global region
    for each in shapes:
        points = each.points
        use = False
        for lng, lat in points:
            if _gis_.within(region, (lng, lat)):
                use = True
                break
        if use:
            print name + '\t' + '\t'.join(['%f\t%f' % (lng, lat) for lng, lat in points])

showLines(loaded['coastline'].shapes(), 'coastline')
showLines(loaded['bathymetry_K_200'].shapes(), 'bathymetry200')
showLines(loaded['lakes'].shapes(), 'lake')
