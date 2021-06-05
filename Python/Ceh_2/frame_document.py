# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from frame_new_document import *
from frame_update_document import *
from frame_filter import *
from frame_executor import *

class DocumentWindow(tk.Frame):
    parent_root = None
    parent_data = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_main_document()
        self.get_all_directions()
        self.view_all_documents()

    def init_main_document(self):
        # Кнопка возврата
        self.btn_main_documents = ttk.Button(self, text='Назад', command=self.btn_back)
        #self.btn_main_documents.pack(side=tk.LEFT)
        self.btn_main_documents.grid(row=0, column=0, sticky=tk.W)

        # Кнопка фильтр
        self.btn_main_filter= ttk.Button(self, text='Фильтр', command=self.search_filter)
        #self.btn_main_filter.pack(side=tk.LEFT)
        self.btn_main_filter.grid(row=1, column=0, sticky=tk.W)
        
        # Кнопка сбросить фильтр
        self.btn_main_reset_filter = ttk.Button(self, text='Сбросить фильтр', command=self.refresh_all_documents)
        #self.btn_main_reset_filter.pack(side=tk.LEFT)
        self.btn_main_reset_filter.grid(row=1, column=1, sticky=tk.W)

        # Кнопка добавить документ
        self.btn_main_add_document = ttk.Button(self, text='Добавить документ', command=self.btn_new_document)
        #self.btn_main_add_document.pack(side=tk.RIGHT)
        self.btn_main_add_document.grid(row=1, column=2, sticky=tk.E)

        # Вкладки
        tab_control = ttk.Notebook(self)
        tab1 = tk.Frame(tab_control)
        tab2 = tk.Frame(tab_control)
        tab_control.add(tab1, text='Документы')
        tab_control.add(tab2, text='Справочники')
        
        # Таблица
        self.tree = ttk.Treeview(tab1, columns=('id','name_document','date_document','last_date_direction', 'last_name_direction', 'period', 'last_instruction'), height=15, show='headings')

        self.tree.tag_configure('frg_red', foreground='red')

        self.tree.column('id', width=30, anchor=tk.CENTER)
        self.tree.column('name_document', minwidth=180, anchor=tk.CENTER)
        self.tree.column('date_document', minwidth=110, anchor=tk.CENTER)
        self.tree.column('last_date_direction', minwidth=110, anchor=tk.CENTER)
        self.tree.column('last_name_direction', minwidth=110, anchor=tk.CENTER)
        self.tree.column('period', minwidth=110, anchor=tk.CENTER)
        self.tree.column('last_instruction', anchor=tk.CENTER)

        self.tree.heading('id', text='id')
        self.tree.heading('name_document', text='Наименование документа')
        self.tree.heading('date_document', text='Дата документа')
        self.tree.heading('last_date_direction', text='Когда направлено')
        self.tree.heading('last_name_direction', text='Кому направлено')
        self.tree.heading('period', text='Срок')
        self.tree.heading('last_instruction', text='Поручение')

        self.tree.bind('<Double-Button-1>', lambda event: self.open_update_dialog())
        self.tree.bind('<Key-Delete>', lambda event: self.delete_record())

        self.tree.pack()

        # Кнопка исполнители
        self.btn_main_executors = ttk.Button(tab2, text='Исполнители', command=self.open_dialog_executor)
        self.btn_main_executors.pack()

        # Упаковка вкладок
        #tab_control.pack(side=tk.BOTTOM, fill=tk.BOTH)
        tab_control.grid(row=2, column=0, columnspan=3)

    def btn_back(self):
        self.parent_data.main_menu.pack()
        self.parent_data.all_documents.pack_forget()
        self.parent_data.resize_window(self.parent_root)

    def btn_new_document(self):
        NewDocument(self.parent_root, self.parent_data)

    def open_update_dialog(self):
        id_selected_document = self.tree.set(self.tree.selection()[0],'id')
        UpdateDocument(self.parent_root, self.parent_data, id_selected_document)
        #print(self.tree.item(self.tree.selection()[0]))

    def delete_record(self):
        res = tk.messagebox.askquestion('Удаление', 'Вы уверены, что желаете удалить запись?')
        if(res == 'yes'):
            id_selected_document = self.tree.set(self.tree.selection()[0],'id')
            # TODO: Физическое удаление сканов

            self.parent_data.db.delete_document(id_selected_document)
            self.refresh_all_documents()

    def get_all_directions(self):
        for document in self.parent_data.documents:
            self.parent_data.db.get_directions(document)
            if(document.last_date_return == None or document.last_date_return == ''):
                if(document.last_date_execute != None):
                    document.period = datetime.datetime.strptime(document.last_date_execute,'%d.%m.%Y').toordinal() - datetime.datetime.now().toordinal()
            else:
                document.period = "Выполнен"

            for executor in self.parent_data.executors:
                if(executor.id==document.last_id_direction):
                    document.last_name_direction=executor.surname

    def view_all_documents(self):
        # Очистка таблицы
        [self.tree.delete(i) for i in self.tree.get_children()]
        # Добавление в таблицу
        [self.tree.insert('', 'end', tag='frg_red' if row.period != 'Выполнен' and row.period != None and int(row.period) < 0 else '', values=[row.id,row.name_document,row.date_document,row.last_date_direction if row.last_date_direction!=None else '',row.last_name_direction if row.last_name_direction!=None else '',row.period if row.period!=None else '',row.last_instruction if row.last_instruction!=None else '']) for row in self.parent_data.documents]

    def refresh_all_documents(self):
        self.parent_data.data_get_all_documents()
        self.get_all_directions()
        self.view_all_documents()

    def search_filter(self):
        SearchFilter(self.parent_root, self.parent_data)

    def refresh_document_filter(self, name_document_filter):
        self.parent_data.data_get_all_documents_with_filter(name_document_filter)
        self.get_all_directions()
        self.view_all_documents()

    def open_dialog_executor(self):
        ExecutorWindow(self.parent_root, self.parent_data)
