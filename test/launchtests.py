import sys, unittest, os
#sys.path.append(r'D:\games\shortcuts\code\modules')
#sys.path.append(r'D:\games\shortcuts\code')

current_dir=os.path.dirname(__file__)
parent_dir=os.path.abspath(os.path.join(current_dir, os.pardir))
sys.path.append(parent_dir)


#from launchparts import get_game_data,write_log_data,print_game_data,compose_log_record,write_log_data

#cd \games\shortcuts\code
#Launch Command: python test\launchtests.py -v   

class TestDataFile(unittest.TestCase):
    def test_valid_json(self):
        """
        Test that the data file is valid json
        """
        import json

        file = open(r"D:\games\shortcuts\code\data.json")
        line = file.read().replace("\n", " ")
        file.close()
        
        result= True
        try:
            json_object = json.loads(line)
        except ValueError as e:
            result= False

        self.assertEqual(result, True)

class TestGetGameDataMethods(unittest.TestCase):
    #game = None
    def setUp(self):
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

    def test_game_name(self):
        """
        Test that the game name is returned
        """
        self.assertEqual(self.game['name'], 'Name of the App')

    def test_game_id(self):
        """
        Test that the game id is returned
        """
        self.assertEqual(self.game['id'], '123')        

    def test_game_platform(self):
        """
        Test that the game platform is returned
        """
        self.assertEqual(self.game['platform'], 'TestPlatform') 

    def test_game_system(self):
        """
        Test that the game system is returned
        """
        self.assertEqual(self.game['system'], 'TestSystem') 

    def test_game_notfound(self):
        """
        Test if the game is not found graceful failure
        """
        from modules.launchparts import get_app_data
        self.game=get_app_data(120,filepath=self.testfilename)

class TestPrintMethods(unittest.TestCase):
    def test_print_game(self):
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

class TestLogFile(unittest.TestCase):
    def test_compose_fullrecord(self):
        from modules.launchparts import compose_log_record
        #import time
        #print(time.time())
        #print(time.strftime("%Y-%m-%d,%H:%M:%S", time.localtime(1620537964.210104)))

        record=compose_log_record("Stop ",1620537964.210104,"game Name",123,"testPlatform","some test notes?",2,"Active")
        match=("Stop ,2021-05-08,22:26:04,game Name,123,testPlatform,some test notes?,2,Active\n")
        self.assertEqual(match, record) 

    def test_compose_startrecord(self):
        from modules.launchparts import compose_log_record
        record=compose_log_record("Start",1620537964.210104,"game Name",123,"testPlatform")
        match=("Start,2021-05-08,22:26:04,game Name,123,testPlatform,,,\n")
        self.assertEqual(match, record) 

class TestElapsedTime(unittest.TestCase):
    def setUp(self):
        start=1620537964.210104
        end=1620537964.210104+1*60*60+120+6 #+1 hour, 2 mintues, 6 seconds
        self.elapsed=end-start

    def test_printelapses(self):
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,seconds=True,minutes=True,elapsed=True)
        match  = "Elapsed Seconds 3,726\n"
        match += "Elapsed Minutes 62.1000\n"
        match += "Elapsed Time 1:02:06\n"
        self.assertEqual(match, output) 

    def test_printelapses_seconds(self):
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,seconds=True,elapsed=False)
        match  = "Elapsed Seconds 3,726\n"
        self.assertEqual(match, output) 

    def test_printelapses_minutes(self):
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,minutes=True,elapsed=False)
        match  = "Elapsed Minutes 62.1000\n"
        self.assertEqual(match, output) 

    def test_printelapses_elapsed(self):
        from modules.launchparts import printelapses

        output=printelapses(self.elapsed,elapsed=True)
        match  = "Elapsed Time 1:02:06\n"
        self.assertEqual(match, output) 

class TestRecaptureTime(unittest.TestCase):
    from unittest.mock import patch

    @patch('builtins.input', return_value="n")
    def test_recapture(self,mmock_input):
        from modules.launchparts import captureendtime
        import time
        
        start=time.time()-30
        end=captureendtime(start,mintime=120,verbose=False)

        self.assertGreater(end,start)
    

if __name__ == '__main__':
    unittest.main()
    input("Press Enter to continue...")