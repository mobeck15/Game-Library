import time, subprocess, os, datetime, sys

#cd \games\shortcuts\code
#Launch Command: python launchapp.dev.py 4011 Epic

from modules.launchparts import get_game_data, write_log_data, print_game_data, launchgame, saverecord
from modules.launchparts import printtime, printelapses, captureendtime, getrating, getstatus

datafile=os.path.dirname(__file__) + r"\data.json"

game=get_game_data(sys.argv[1],filepath=datafile)

print(print_game_data(game))

datatype='Add Time'

start = time.time()
print(printtime(start,'Starting at '))

logfilename= os.path.dirname(__file__) + r'\log' + time.strftime("%Y", time.localtime(start)) + '-' + time.strftime("%m", time.localtime(start)) + '.csv'
print(logfilename)

write_log_data(logfilename,"Start",start,game['name'],game['id'],game['platform'])

print("Running " + game['name'])

launchgame(game,sys.argv)

end=captureendtime(start,mintime=120,verbose=True)
elapsed=end-start
elapsedmin=elapsed/60

notes=input("Any Notes, (include Keywords - Idle Beat Cheat) ")

rating=getrating()
status=getstatus()

write_log_data(logfilename,"Stop ",end,game['name'],game['id'],game['platform'],notes,rating,status)

print(saverecord(game,elapsedmin,datatype,notes,status,rating))

input("Press Enter to continue...")