# -*- coding: utf-8 -*-

import sqlite3
from document import *
from resolution import *
from executor import *
from direction import *
import os

class DB:
    def __init__(self, settings):
        self.connect = sqlite3.connect(settings.path_db)
        self.cursor = self.connect.cursor()
        self.cursor.execute('''CREATE TABLE IF NOT EXISTS `directions` (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,id_document INTEGER NOT NULL,date_shipment VARCHAR(50) NOT NULL,date_execute VARCHAR(50) NOT NULL,executor_shipment INTEGER NOT NULL,instruction TEXT,date_return VARCHAR(50),executor_return INTEGER,result_instruction TEXT)''')
        self.cursor.execute('''CREATE TABLE IF NOT EXISTS `documents` (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,name_document TEXT NOT NULL,date_document VARCHAR(50) NOT NULL,archive INTEGER)''')
        self.cursor.execute('''CREATE TABLE IF NOT EXISTS `executors` (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,surname VARCHAR(50) NOT NULL,name VARCHAR(50) NOT NULL,patronymic VARCHAR(50) NOT NULL,telephone VARCHAR(50))''')
        self.cursor.execute('''CREATE TABLE IF NOT EXISTS `resolutions` (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,id_document INTEGER NOT NULL,name_resolution VARCHAR(50),path_resolution TEXT NOT NULL)''')
        self.connect.commit()

    def create_document(self, txt_name_document, entry_date):
        self.cursor.execute('INSERT INTO documents (name_document,date_document,archive) VALUES (?,?,?)', (txt_name_document,entry_date,0),)
        self.connect.commit()
        return self.cursor.lastrowid

    def get_all_documents(self, archive=0):
        self.cursor.execute('SELECT * FROM documents WHERE archive=? ORDER BY id DESC', (archive,),)
        pr_documents = []
        for row in self.cursor.fetchall():
            new_document = Document()
            new_document.id = row[0]
            new_document.name_document = row[1]
            new_document.date_document = row[2]
            new_document.archive = row[3]
            pr_documents.append(new_document);
        return pr_documents

    def get_all_documents_with_filter(self, name_document_filter):
        name_document_filter = ('%' + name_document_filter + '%',)
        self.cursor.execute('SELECT * FROM documents WHERE archive=0 AND name_document LIKE ? ORDER BY id DESC', name_document_filter,)
        pr_documents = []
        for row in self.cursor.fetchall():
            new_document = Document()
            new_document.id = row[0]
            new_document.name_document = row[1]
            new_document.date_document = row[2]
            new_document.archive = row[3]
            pr_documents.append(new_document);
        return pr_documents

    def update_document(self, id_document, txt_name_document, entry_date, archive):
        self.cursor.execute('UPDATE documents SET name_document=?,date_document=?,archive=? WHERE id=?', (txt_name_document,entry_date,archive,id_document),)
        self.connect.commit()

    def delete_document(self, id_document):
        self.cursor.execute('DELETE FROM resolutions WHERE id_document=?', (id_document,),)
        self.connect.commit()
        self.cursor.execute('DELETE FROM directions WHERE id_document=?', (id_document,),)
        self.connect.commit()
        self.cursor.execute('DELETE FROM documents WHERE id=?', (id_document,),)
        self.connect.commit()

    def create_resolution(self, id_document, new_path):
        self.cursor.execute('INSERT INTO resolutions (id_document,path_resolution,name_resolution) VALUES (?,?,?)', (id_document,new_path,os.path.basename(new_path)),)
        self.connect.commit()

    def get_resolutions(self, document):
        self.cursor.execute('SELECT * FROM resolutions WHERE id_document=?', (document.id,),)
        pr_resolutions = []
        for row in self.cursor.fetchall():
            new_resolution = Resolution()
            new_resolution.id = row[0]
            new_resolution.id_document = row[1]
            new_resolution.name_resolution = row[2]
            new_resolution.path_resolution = row[3]
            pr_resolutions.append(new_resolution)
        document.resolutions = pr_resolutions

    def delete_resolution(self, id_resolution):
        self.cursor.execute('DELETE FROM resolutions WHERE id=?', (id_resolution,),)
        self.connect.commit()

    def create_executor(self, executor):
        self.cursor.execute('INSERT INTO executors (surname,name,patronymic,telephone) VALUES (?,?,?,?)', (executor.surname,executor.name,executor.patronymic,executor.telephone),)
        self.connect.commit()

    def get_all_executors(self):
        self.cursor.execute('SELECT * FROM executors ORDER BY surname ASC')
        pr_executors = []
        for row in self.cursor.fetchall():
            new_executor = Executor()
            new_executor.id = row[0]
            new_executor.surname = row[1]
            new_executor.name = row[2]
            new_executor.patronymic = row[3]
            new_executor.telephone = row[4]
            pr_executors.append(new_executor);
        return pr_executors

    def update_executor(self, executor):
        self.cursor.execute('UPDATE executors SET surname=?,name=?,patronymic=?,telephone=? WHERE id=?', (executor.surname,executor.name,executor.patronymic,executor.telephone,executor.id),)
        self.connect.commit()

    def create_direction(self, direction):
        self.cursor.execute('INSERT INTO directions (id_document,date_shipment,executor_shipment,date_execute,instruction,date_return,executor_return,result_instruction) VALUES (?,?,?,?,?,?,?,?)', (direction.id_document,direction.date_shipment,direction.executor_shipment,direction.date_execute,direction.instruction,direction.date_return,direction.executor_return,direction.result_instruction),)
        self.connect.commit()

    def get_directions(self, document):
        self.cursor.execute('SELECT * FROM directions WHERE id_document=?', (document.id,),)
        pr_directions = []
        for row in self.cursor.fetchall():
            new_direction = Direction()
            new_direction.id = row[0]
            new_direction.id_document = row[1]
            new_direction.date_shipment = row[2]
            new_direction.date_execute = row[3]
            new_direction.executor_shipment = row[4]
            new_direction.instruction = row[5]
            new_direction.date_return = row[6]
            new_direction.executor_return = row[7]
            new_direction.result_instruction = row[8]
            pr_directions.append(new_direction)

            document.last_date_direction = row[2]
            document.last_date_execute = row[3]
            document.last_id_direction = row[4]
            document.last_instruction = row[5]
            document.last_date_return = row[6]
        document.directions = pr_directions

    def update_direction(self, direction):
        self.cursor.execute('UPDATE directions SET date_shipment=?,executor_shipment=?,date_execute=?,instruction=?,date_return=?,executor_return=?,result_instruction=? WHERE id=?', (direction.date_shipment,direction.executor_shipment,direction.date_execute,direction.instruction,direction.date_return,direction.executor_return,direction.result_instruction,direction.id),)
        self.connect.commit()

    def delete_direction(self, id_direction):
        self.cursor.execute('DELETE FROM directions WHERE id=?', (id_direction,),)
        self.connect.commit()
