import time, subprocess, os, datetime, sys

#Launch Command: python launchapp.dev.py 4011 Epic

from modules.launchparts import get_app_data, write_log_data, print_app_data, launchapp, saverecord
from modules.launchparts import printtime, printelapses, captureendtime, getrating, getstatus

datafile=os.path.dirname(__file__) + r"\data\data.json"

appdata=get_app_data(sys.argv[1],filepath=datafile)

print(print_app_data(appdata))

datatype='Add Time'

start = time.time()
print(printtime(start,'Starting at '))

logfilename= os.path.dirname(__file__) + r'\logs\log' + time.strftime("%Y", time.localtime(start)) + '-' + time.strftime("%m", time.localtime(start)) + '.csv'
print(logfilename)

write_log_data(logfilename,"Start",start,appdata['name'],appdata['id'],appdata['platform'])

print("Running " + appdata['name'])

launchapp(appdata,sys.argv)

end=captureendtime(start,mintime=120,verbose=True)
elapsed=end-start
elapsedmin=elapsed/60

notes=input("Any Notes, (include Keywords - Idle Beat Cheat) ")

rating=getrating()
status=getstatus()

write_log_data(logfilename,"Stop ",end,appdata['name'],appdata['id'],appdata['platform'],notes,rating,status)

print(saverecord(appdata,elapsedmin,datatype,notes,status,rating))

input("Press Enter to continue...")