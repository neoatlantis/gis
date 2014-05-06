# -*- coding: utf-8 -*-
import _gis_
import shapefile

region = _gis_.parseArgvAsRegion()

load = ['coastline']

loaded = {}
for each in load:
    loaded[each] = shapefile.Reader('data/ne_10m/ne_10m_%s' % each)

x = loaded['coastline'].shapes()
for each in x:
    points = each.points
    use = False
    for lng, lat in points:
        if _gis_.within(region, (lng, lat)):
            use = True
            break
    if use:
        print 'line\t' + '\t'.join(['%f\t%f' % (lng, lat) for lng, lat in points])
