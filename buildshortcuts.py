from modules.launchparts import get_app_data, write_log_data, print_app_data, launchapp, saverecord
from modules.launchparts import printtime, printelapses, captureendtime, getrating, getstatus

import os
datafile=os.path.dirname(__file__) + r"\data\data.json"
shorcutfolder=os.path.dirname(__file__) + r"\shortcuts"

#source tutorial
#https://pbpython.com/windows-shortcut.html

#works but does not create shortcuts with 'run as administrator' selected This link may help
#https://stackoverflow.com/questions/37049108/create-windows-explorer-shortcut-with-run-as-administrator

def clean_filename(name,folder=""):
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
    for p in data['game']:
        if(os.path.isfile(str(p['path']['exe']))):
            print(str(p['name']),end='')
            print(" -Installed-")
            #link_filepath = str(shorcutfolder + "\\" + str(p['name']) + ".lnk")
            link_filepath = clean_filename(str(p['name']),shorcutfolder)
            with winshell.shortcut(link_filepath) as link:
                link.path = r"C:\Users\mobec\python\virtualenv\Scripts\python.exe"
                link.description = str(p['name'])
                link.arguments = r"d:\Python\game-library\launchapp.py " + str(p['id'])
                link.icon_location = (r"C:\Users\mobec\python\virtualenv\Scripts\python.exe", 0)
                link.working_directory = r"C:\Users\mobec\python\virtualenv\Scripts"

        #else:
            #print(str(p['name']),end='')
            #print(" -NOT FOUND-")


link_filepath = str(shorcutfolder + r"\Dragonshard.lnk")
# Create the shortcut
with winshell.shortcut(link_filepath) as link:
    link.path = r"C:\Users\mobec\python\virtualenv\Scripts\python.exe"
    link.description = "Dragonshard"
    link.arguments = r"d:\Python\game-library\launchapp.py 221"
    link.icon_location = (r"C:\Users\mobec\python\virtualenv\Scripts\python.exe", 0)
    link.working_directory = r"C:\Users\mobec\python\virtualenv\Scripts"

link_filepath = str(shorcutfolder + r"\Kingdom Hearts Birth By Sleep.lnk")
# Create the shortcut
with winshell.shortcut(link_filepath) as link:
    link.path = r"C:\Users\mobec\python\virtualenv\Scripts\python.exe"
    link.description = "Kingdom Hearts Birth By Sleep"
    link.arguments = r"d:\Python\game-library\launchapp.py 4012 Epic"
    link.icon_location = (r"C:\Users\mobec\python\virtualenv\Scripts\python.exe", 0)
    link.working_directory = r"C:\Users\mobec\python\virtualenv\Scripts"

#dump shortcut data
#lnk = str(shorcutfolder + r"\Dragonshard (original1).lnk")
#shortcut = winshell.shortcut(str(lnk))
#shortcut.dump()


