# -*- coding: utf-8 -*-

import os

class Setting:
    path_to_settings_file = 'settings.txt'
    all_settings = ""
    path_db = 'db/ceh2.db'
    path_resource = 'resources';
    path_resolution = 'resolutions'
    path_reports = 'reports'
    
    def __init__(self):
        self.load_settings_from_file()
        self.load_settings_from_str()

    def load_settings_from_file(self):
        if os.access(self.path_to_settings_file, os.R_OK):
            f = open(self.path_to_settings_file, 'r')
            self.all_settings = f.read()
            f.close()
        else:
            self.create_settings_file()

    def load_settings_from_str(self):
        for row in self.all_settings.replace('\n','').split(';'):
            part_setting = row.split('=')
            if(part_setting[0] == 'path_db'):
                self.path_db = part_setting[1]
            elif(part_setting[0] == 'path_resource'):
                self.path_resource = part_setting[1]
            elif(part_setting[0] == 'path_resolution'):
                self.path_resolution = part_setting[1]
            elif(part_setting[0] == 'path_reports'):
                self.path_reports = part_setting[1]

    def create_settings_file(self):
        self.all_settings = 'path_db=' + self.path_db + ';\npath_resource=' + self.path_resource + ';\npath_resolution=' + self.path_resolution + ';\npath_reports=' + self.path_reports + ';'
        f = open(self.path_to_settings_file, 'w')
        f.write(self.all_settings)
        f.close()
