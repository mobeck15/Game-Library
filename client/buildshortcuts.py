from modules.launchparts import get_app_data, write_log_data, print_app_data, launchapp, saverecord
from modules.launchparts import printtime, printelapses, captureendtime, getrating, getstatus

import os
datafile=os.path.dirname(__file__) + r"\data\data.json"

#source tutorial
#https://pbpython.com/windows-shortcut.html

#works but does not create shortcuts with 'run as administrator' selected This link may help
#https://stackoverflow.com/questions/37049108/create-windows-explorer-shortcut-with-run-as-administrator

def clean_filename(name,folder=""):
    #https://www.mtu.edu/umc/services/websites/writing/characters-avoid/
    name=name.replace("@","")
    name=name.replace("#","")
    name=name.replace(":","")
    name=name.replace("'","")
    name=name.replace("\"","")
    filepath = str(folder + "\\" + name + ".lnk")
    return filepath

import winshell, json
with open(datafile) as json_file:
    data = json.load(json_file)
    datacount=0
    linkcount=0
    for p in data['game']:
        shorcutfolder=os.path.dirname(__file__) + r"\shortcuts"
        datacount+=1
        if(os.path.isfile(str(p['path']['exe']))):
            #print(str(p['name']),end='')
            #print(" -Installed-")
            linkcount+=1
            link_filepath = clean_filename(str(p['name']),shorcutfolder)
            link_args=str(p['id'])
            link_desc=str(p['name'])
            if 'launch' in p:
                link_args = str(p['id']) + " " + p['launch']
                shorcutfolder = os.path.join(shorcutfolder,p['launch'])
                link_filepath = clean_filename(str(p['name']) +" (" + p['launch'] + ")",shorcutfolder)
                link_desc = link_desc +" (" + p['launch'] + ")"
                folderexists = os.path.isdir(shorcutfolder)
                if folderexists == False:
                    os.makedirs(shorcutfolder)
            #print(link_args)
            with winshell.shortcut(link_filepath) as link:
                link.path = r"C:\Users\mobec\python\virtualenv\Scripts\python.exe"
                link.description = link_desc
                link.arguments = r"d:\Python\game-library\client\launchapp.py " + link_args
                #link.icon_location = (r"C:\Users\mobec\python\virtualenv\Scripts\python.exe", 0)
                link.icon_location = (str(p['path']['exe']), 0)
                link.working_directory = r"C:\Users\mobec\python\virtualenv\Scripts"

        #else:
            #print(str(p['name']),end='')
            #print(" -NOT FOUND-")
    print(str(datacount) + " Data records processed")
    print(str(linkcount) + " Shortcuts created")
    #input("Press Enter to exit...")

#dump shortcut data
#lnk = str(shorcutfolder + r"\Dragonshard (original1).lnk")
#shortcut = winshell.shortcut(str(lnk))
#shortcut.dump()
 

