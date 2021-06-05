# -*- coding: utf-8 -*-

import os
import datetime

class Document:
    id = None
    name_document = None
    date_document = None
    last_date_direction = None
    last_id_direction = None
    last_name_direction = None
    last_date_execute = None
    period = None
    last_instruction = None
    last_date_return = None
    archive = None
    directions = []
    resolutions = []

    @staticmethod
    def create_html_document(data):
        html = "<!DOCTYPE html><html lang='ru'>"
        html += "<head><meta charset='utf-8'><title>Документы</title><style>table{border-spacing:0px;}table th, table td{border:black 1px solid;padding:7px;}</style></head><body>"
        html += "<table><thead><tr><th>Наименование документа</th><th>Дата документа</th><th>Дата передачи</th><th>Исполнитель</th><th>Поручение</th><th>Дата исполнения</th><th>Дата возврата</th><th>Результат исполнения</th></tr></thead><tbody>"
        for document in data.documents:
            html += '<tr><td>'
            html += document.name_document
            html += '</td><td>'
            html += document.date_document
            html += '</td><td>'
            date_shipment = ''
            executor_shipment = ''
            instruction = ''
            date_execute = ''
            date_return = ''
            result_instruction = ''
            for direction in document.directions:
                if(direction.date_shipment != None):
                    date_shipment += direction.date_shipment + '<br/>'
                if(direction.name_executor_shipment != None):
                    executor_shipment += direction.name_executor_shipment + '<br/>'
                if(direction.instruction != None):
                    instruction += direction.instruction + '<br/>'
                if(direction.date_execute != None):
                    date_execute += direction.date_execute + '<br/>'
                if(direction.date_return != None):
                    date_return += direction.date_return + '<br/>'
                if(direction.result_instruction != None):
                    result_instruction += direction.result_instruction + '<br/>'
            html += date_shipment
            html += '</td><td>'
            html += executor_shipment
            html += '</td><td>'
            html += instruction
            html += '</td><td>'
            html += date_execute
            html += '</td><td>'
            html += date_return
            html += '</td><td>'
            html += result_instruction
            html += '</td></tr>'
        html += '</tbody></table>'
        html += '</body></html>'
        return html

    @staticmethod
    def create_html(data, settings):
        html = Document.create_html_document(data)
        datetime_now = datetime.datetime.now()
        datetime_now = str(datetime_now).replace(':', '-').replace(' ', '_')
        save_path = settings.path_reports + '/Documents_' + datetime_now + '.html'
        f = open(save_path, 'a', encoding='utf-8')
        f.write(html)
        f.close()

    @staticmethod
    def create_word(data, settings):
        html = Document.create_html_document(data)
        datetime_now = datetime.datetime.now()
        datetime_now = str(datetime_now).replace(':', '-').replace(' ', '_')
        save_path = settings.path_reports + '/Documents_' + datetime_now + '.doc'
        f = open(save_path, 'a', encoding='utf-8')
        f.write(html)
        f.close()

    @staticmethod
    def create_excel(data, settings):
        html = Document.create_html_document(data)
        datetime_now = datetime.datetime.now()
        datetime_now = str(datetime_now).replace(':', '-').replace(' ', '_')
        save_path = settings.path_reports + '/Documents_' + datetime_now + '.xls'
        f = open(save_path, 'a', encoding='utf-8')
        f.write(html)
        f.close()
