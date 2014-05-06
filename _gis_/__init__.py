import sys
import subprocess

def parseArgvAsRegion():
    if len(sys.argv) < 5:
        print "Usage: python %s <Longitude> <deltaLongitude> <Latitude> <deltaLatitude>" % sys.argv[0]
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
    return borderN, borderS, borderL

def within(borders, point):
    lng, lat = point
    borderN, borderS, borderL = borders
    if not (borderS <= lat and lat <= borderN):
        return False
    for borderW, borderE in borderL:
        if borderW <= lng and lng <= borderE:
            return True
    return False

def checkPythonOutput(name, center, diff):
    command = ['python', name + '.py', str(center[0]), str(diff), str(center[1]), str(diff)]
    return subprocess.check_output(command)