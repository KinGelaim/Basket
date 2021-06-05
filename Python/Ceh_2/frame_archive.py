# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from frame_new_document import *
from frame_update_document import *
from frame_update_document_archive import *

class ArchiveWindow(tk.Frame):
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
        
        # Таблица
        self.tree = ttk.Treeview(self, columns=('id','name_document','date_document','last_date_direction', 'last_name_direction', 'period', 'last_instruction'), height=15, show='headings')

        self.tree.column('id', width=10, anchor=tk.CENTER)
        self.tree.column('name_document', width=180, anchor=tk.CENTER)
        self.tree.column('date_document', width=110, anchor=tk.CENTER)
        self.tree.column('last_date_direction', width=110, anchor=tk.CENTER)
        self.tree.column('last_name_direction', width=110, anchor=tk.CENTER)
        self.tree.column('period', width=110, anchor=tk.CENTER)
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

        #self.tree.pack(side=tk.TOP, fill=tk.BOTH)
        self.tree.grid(row=1, column=0)

    def btn_back(self):
        self.parent_data.main_menu.pack()
        self.parent_data.archive_window.pack_forget()
        self.parent_data.resize_window(self.parent_root)

    def open_update_dialog(self):
        id_selected_document = self.tree.set(self.tree.selection()[0],'id')
        UpdateDocumentArchive(self.parent_root, self.parent_data, id_selected_document)

    def get_all_directions(self):
        for document in self.parent_data.archive_documents:
            self.parent_data.db.get_directions(document)
            if(document.last_date_return == None or document.last_date_return == ''):
                if(document.last_date_execute != None):
                    document.period = datetime.datetime.strptime(document.last_date_execute,'%d.%m.%Y').toordinal() - datetime.datetime.now().toordinal()
            else:
                document.period = "Выполнен"

    def view_all_documents(self):
        # Очистка таблицы
        [self.tree.delete(i) for i in self.tree.get_children()]
        # Добавление в таблицу
        [self.tree.insert('', 'end', values=[row.id,row.name_document,row.date_document,row.last_date_direction,row.last_name_direction,row.period,row.last_instruction]) for row in self.parent_data.archive_documents]

    def refresh_all_archives(self):
        self.parent_data.data_get_all_archives()
        self.get_all_directions()
        self.view_all_documents()
