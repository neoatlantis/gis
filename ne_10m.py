# -*- coding: utf-8 -*-

import shapefile

load = ['coastline']

loaded = {}
for each in load:
    loaded[each] = shapefile.Reader('data/ne_10m/ne_10m_%s' % each)

x = loaded['coastline'].shapes()
print len(x)
for i in xrange(0, 10):
    print x[i].points
