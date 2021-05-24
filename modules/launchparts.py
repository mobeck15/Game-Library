def get_app_data(appid,launcher="exe",filepath=r'D:\games\shortcuts\Code\data.txt'):
    import json
    appdata=None
    with open(filepath) as json_file:
        data = json.load(json_file)
        for p in data['game']:
            if str(p['id'])==str(appid):
                appdata=p
    return appdata

def print_app_data(p,launcher='exe'):
    #print(p)
    output='Name: ' + p['name'] + "\n"
    output = output + 'ID: ' + p['id'] + "\n"
    output = output + 'Platform: ' + p['platform'] + "\n"
    output = output + 'System: ' + p['system'] + "\n"
    output = output + 'Launch: ' + p['path'][launcher] + "\n"
    output = output + ''

    return output

def write_log_data(logfilename,logtype,logtime,name,appid,platform,notes="",rating="",status=""):
    file1 = open(logfilename, "a")  # append mode
    startline = compose_log_record(logtype,logtime,name,appid,platform,notes,rating,status)
    file1.write(startline)
    file1.close()

def compose_log_record(logtype,logtime,name,id,platform,notes="",rating="",status=""):
    import time
    startline = logtype
    startline = startline + ","+time.strftime("%Y-%m-%d,%H:%M:%S", time.localtime(logtime))
    startline = startline + ","+ name
    startline = startline + ","+ str(id)
    startline = startline + ","+ platform
    startline = startline + ","+ notes
    startline = startline + ","+ str(rating)
    startline = startline + ","+ status
    startline = startline + "\n"
    return startline

def launchapp(appdata,argv,verbose=True):
    import subprocess, os
    if len(argv) < 3 or argv[2]=='exe':
        os.chdir(os.path.dirname(appdata['path']['exe']))
        if verbose:
            print('Working Directory: ', os.getcwd())
            print('executing ', appdata['path']['exe'])
        subprocess.call(appdata['path']['exe'])
    else:
        if verbose:
            print('executing ', appdata['path'][argv[2]])
        os.startfile(appdata['path'][argv[2]])
        
        #subprocess.call(['C:\\windows\\system32\\Notepad.exe', 'D:\\test.txt'])
        #os.startfile('com.epicgames.launcher://apps/68c214c58f694ae88c2dab6f209b43e4?action=launch&silent=true')

        #steam://rungameid/1102190
        #subprocess.run("cmd /c start steam://run/1102190")

def printtime(usetime,label=''):
    import time
    output = label
    output = output + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(usetime))
    return output

def printelapses(elapsedtime,seconds=False,minutes=False,elapsed=True):
    import datetime
    output=''
    if seconds:
        #print('Elapsed Seconds', elapsed)
        output += 'Elapsed Seconds ' + f"{elapsedtime:,.0f}" + "\n"
    if minutes:
        #print('Elapsed Minutes', round(elapsedmin, 4))
        output += 'Elapsed Minutes ' + f"{round(elapsedtime/60, 4):,.4f}" + "\n"

    if elapsed:
        #print('Elapsed time', datetime.timedelta(seconds=elapsed))
        output += 'Elapsed Time ' + str(datetime.timedelta(seconds=elapsedtime)) + "\n"

    return output

def captureendtime(start,mintime=120,verbose=True):
    import time
    recapture='n'
    while True:
        end = time.time()
        elapsed=end-start

        if verbose:
            print(printtime(end,'Ending at   '))

            print(printelapses(elapsed,seconds=True,minutes=True,elapsed=True))
            print('______________________')

        if elapsed<mintime:
            #recapture = prompt_for_recapture()
            recapture = input("Less than two minutes logged, Y to re-capture end time, any key to keep. ")
            if recapture.lower() not in  ('y', 'ye', 'yes'):
                break
        else: 
            break
    return end

def prompt_for_recapture():
    return input("Less than two minutes logged, Y to re-capture end time, any key to keep. ")

def getrating():
    while True:
        rating=input("Rating (1-4) ")
        if rating not in ('1','2','3','4'):
            print('---Invalid response---')
        else:
            break
    return rating

def getstatus():
    while True:
        status=input("Status (Active Done Inative OnHold Unplayed Broken Never) ")
        if status.lower() in ('active'):
            status = 'Active'
        elif status.lower() in ('done'):
            status = 'Done'
        elif status.lower() in ('inactive'):
            status = 'Inactive'
        elif status.lower() in ('onhold', 'on hold'):
            status = 'OnHold'
        elif status.lower() in ('unplayed'):
            status = 'Unplayd'
        elif status.lower() in ('broken'):
            status = 'Broken'
        elif status.lower() in ('never'):
            status = 'Never'
        if status not in ('Active', 'Done', 'Inactive', 'OnHold', 'Unplayed', 'Broken', 'Never'):
            print('---Invalid response---')
        else:
            break
    return status

def saverecord(appdata,elapsedmin,datatype,notes,status,rating):
    #curl --data "datarow[1][update]=on&datarow[1][ProductID]=%gameid%&datarow[1][Title]=%gamename%&currenttime=on&datarow[1][hours]=%min%.%min2%&datarow[1][System]=%system%&datarow[1][Data]=%datatype%&datarow[1][notes]=%notes%&datarow[1][source]=Game%%20Library%%206%%20cmd&datarow[1][minutes]=on&Submit=Save&datarow[1][achievements]=&datarow[1][status]=%status%&datarow[1][review]=%rating%&timestamp=" Http://isaacguerrero:TCSPws73SUMCcU@games.stuffiknowabout.com/gl6/addhistory.php

    url = 'http://games.stuffiknowabout.com/gl6/addhistory.php'
    PostArgs = {
        'currenttime': 'on',
        'Submit': 'Save',
        'timestamp': '',
        'datarow[1][update]': 'on',
        'datarow[1][source]': 'Game Library 7 cmd',
        'datarow[1][minutes]': 'on',
        'datarow[1][achievements]': '',

        'datarow[1][ProductID]': appdata['id'],
        'datarow[1][Title]': appdata['name'],
        'datarow[1][hours]': elapsedmin,
        'datarow[1][System]': appdata['system'],
        'datarow[1][Data]': datatype,

        'datarow[1][notes]': notes,
        'datarow[1][status]': status,
        'datarow[1][review]': rating
    }

    import requests
    from modules.secrets import secrets
    x = requests.post(url, data = PostArgs, auth = (secrets['username'], secrets['password']))

    search=x.text.find('Record updated successfully')
    if search > 0:
        output= 'Record updated successfully'
    else:
        output= 'Record not saved'
    
    return output