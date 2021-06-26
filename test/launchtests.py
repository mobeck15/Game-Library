import sys, unittest, os
#sys.path.append(r'D:\games\shortcuts\code\modules')
#sys.path.append(r'D:\games\shortcuts\code')

current_dir=os.path.dirname(__file__)
parent_dir=os.path.abspath(os.path.join(current_dir, os.pardir))
sys.path.append(parent_dir)


#from launchparts import get_game_data,write_log_data,print_game_data,compose_log_record,write_log_data

#Launch Command: python test\launchtests.py -v   

class TestDataFile(unittest.TestCase):
    def test_valid_json(self):
        """
        Test that the data file is valid json
        """
        import json

        with open(parent_dir+r"\data.json") as file:
            line = file.read().replace("\n", " ")
        
        result= True
        try:
            json_object = json.loads(line)
        except ValueError as e:
            result= False

        self.assertEqual(result, True)

class TestGetAppDataMethods(unittest.TestCase):
    def setUp(self):
        """
        Set up get_app_data tests
        """
        from modules.launchparts import get_app_data
        import os, json
        self.testfilename= os.path.dirname(__file__) + r'\testdata.csv'

        data = {}
        data['game'] = []
        data['game'].append({
            'name': 'Name of the App',
            'id': '123',
            'platform': 'TestPlatform',
            'system': 'TestSystem',
            'path':{
                'exe': r'C:\windows\system32\Notepad.exe',
                'Epic': ''
            }
        })

        with open(self.testfilename, 'w') as outfile:
            json.dump(data, outfile)

        self.game=get_app_data(123,filepath=self.testfilename)

    def test_app_name(self):
        """
        Test that get_app_data returns name
        """
        self.assertEqual(self.game['name'], 'Name of the App')

    def test_app_id(self):
        """
        Test that get_app_data returns id
        """
        self.assertEqual(self.game['id'], '123')        

    def test_app_platform(self):
        """
        Test that get_app_data returns platform
        """
        self.assertEqual(self.game['platform'], 'TestPlatform') 

    def test_app_system(self):
        """
        Test that get_app_data returns system
        """
        self.assertEqual(self.game['system'], 'TestSystem') 

    def test_app_notfound(self):
        """
        Test that get_app_data fails gracefully when app not found
        """
        from modules.launchparts import get_app_data
        self.game=get_app_data(120,filepath=self.testfilename)

class TestPrintMethods(unittest.TestCase):
    def test_print_app(self):
        """
        Test that print_app_data outputs text as expected
        """
        from modules.launchparts import print_app_data
        game = {
            'name': 'Name of the App',
            'id': '123',
            'platform': 'TestPlatform',
            'system': 'TestSystem',
            'path':{
                'exe': r'C:\windows\system32\Notepad.exe',
                'Epic': ''
            }
        }
 
        output=print_app_data(game)
        match = 'Name: ' + game['name'] +"\n"
        match = match + 'ID: ' + game['id'] +"\n"
        match = match + 'Platform: ' + game['platform'] +"\n"
        match = match + 'System: ' + game['system'] +"\n"
        match = match + 'Launch: ' + game['path']['exe'] +"\n"
        match = match + ''
        #print(match)
        self.assertEqual(match, output) 

"""
Test that write_log_data saves log data to the specified new log file when the log file does not exist
"""
"""
Test that write_log_data saves log data to the specidied existing log file and appedns without altering existing data
"""

class TestLogFile(unittest.TestCase):
    def test_compose_startrecord(self):
        """
        Test that compose_log_record creates a valid record for -start-
        """
        from modules.launchparts import compose_log_record
        record=compose_log_record("Start",1620537964.210104,"game Name",123,"testPlatform")
        match=("Start,2021-05-08,22:26:04,game Name,123,testPlatform,,,\n")
        self.assertEqual(match, record) 

    def test_compose_fullrecord(self):
        """
        Test that compose_log_record creates a valid full record for -end-
        """
        from modules.launchparts import compose_log_record
        #import time
        #print(time.time())
        #print(time.strftime("%Y-%m-%d,%H:%M:%S", time.localtime(1620537964.210104)))

        record=compose_log_record("Stop ",1620537964.210104,"game Name",123,"testPlatform","some test notes?",2,"Active")
        match=("Stop ,2021-05-08,22:26:04,game Name,123,testPlatform,some test notes?,2,Active\n")
        self.assertEqual(match, record) 

"""
launchapp
	Test that launchapp launches app using -exe- option
	Test that launchapp launches app using alternate launch option
	Test that launchapp launches app using the default launch option when unspecified
	Test that launchapp launches app using the exe launch option if appdata does not have a default
	Test launchapp for an app that requires admin privelages
	Test launchapp with a bad exe path
	*Use launchapp to start a Steam game
	*Use launchapp to start an Epic game
	*Use launchapp to start an Origin game
	*Use launchapp to start an amazon game
	*Use launchapp to start an exe using startfile
"""

"""
Test printtime returns time in expected format
"""

class TestElapsedTime(unittest.TestCase):
    def setUp(self):
        """
        Set up printelapses tests 
        """
        start=1620537964.210104
        end=1620537964.210104+1*60*60+120+6 #+1 hour, 2 mintues, 6 seconds
        self.elapsed=end-start

    def test_printelapses(self):
        """
        Test that printelapses returns elapsed time for all time formats 
        """
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,seconds=True,minutes=True,elapsed=True)
        match  = "Elapsed Seconds 3,726\n"
        match += "Elapsed Minutes 62.1000\n"
        match += "Elapsed Time 1:02:06\n"
        self.assertEqual(match, output) 

    def test_printelapses_seconds(self):
        """
        Test that printelapses returns elapsed time as Seconds 
        """
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,seconds=True,elapsed=False)
        match  = "Elapsed Seconds 3,726\n"
        self.assertEqual(match, output) 

    def test_printelapses_minutes(self):
        """
        Test that printelapses returns elapsed time as minutes 
        """
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,minutes=True,elapsed=False)
        match  = "Elapsed Minutes 62.1000\n"
        self.assertEqual(match, output) 

    def test_printelapses_elapsed(self):
        """
        Test that printelapses returns elapsed time as duration 
        """
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,elapsed=True)
        match  = "Elapsed Time 1:02:06\n"
        self.assertEqual(match, output) 

class TestRecaptureTime(unittest.TestCase):
    from unittest.mock import patch

    @patch('builtins.input', return_value="n")
    def test_recapture(self,mmock_input):
        """
        Test that captureendtime records an end time that is larger than start time
        """
        #TODO: this may not actually be testing what it says
        from modules.launchparts import captureendtime
        import time
        
        start=time.time() #-30
        time.sleep(0.01)
        end=captureendtime(start,mintime=120,verbose=False)

        self.assertGreater(end,start)
        """
        Test that captureendtime prompts to recapture time when below minimum
        Test that captureendtime prompts to recapture time when below minimum even after prompting once
        Test that captureendtime does not prompt to recapture time when above minimum
        """
    
"""
getrating
	Test that getrating only accepts 1-4
	*Test that getrating accepts blank as previously submitted value (only if one exists)
	*Test that getrating rejects blank if no previous value
	*Test that getrating displays previous value before prompt
"""

"""
getstatus
	Test that getstatus only accepts listed options (Active)
	Test that getstatus only accepts listed options (Done)
	Test that getstatus only accepts listed options (Inative)
	Test that getstatus only accepts listed options (OnHold)
	Test that getstatus accepts -on hold- staus OnHold
	Test that getstatus only accepts listed options (Unplayed)
	Test that getstatus only accepts listed options (Broken)
	Test that getstatus only accepts listed options (Never)
	Test that getstatus accepts input as case insensetive
	Test that getstatus output case is normalized
	*Test that getstatus accepts blank as previously submitted value (only if one exists)
	*Test that getstatus rejects blank if no previous value
	*Test that getstatus displays previous value before prompt
"""

"""
saverecord
	Test that saverecord submits data to onlind database
	Test that saverecord upload prompt accecpts blank as -yes-
	Test that saverecord composes post string as expected
	Test that saverecord returns -Record updated successfully- on success
	Test that saverecord returns -Record not saved- on error
	Test that saverecord returns -Record not saved- decline prompt
"""

if __name__ == '__main__':
    unittest.main()
    input("Press Enter to continue...")