# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from frame_new_executor import *
from frame_update_executor import *

class ExecutorWindow(tk.Toplevel):
    parent_root = None
    parent_data = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_main_executor()
        self.view_all_executors()

    def init_main_executor(self):
        # Кнопка добавления
        self.btn_main_documents = ttk.Button(self, text='Новый исполнитель', command=self.dialog_new_executor)
        #self.btn_main_documents.pack(side=tk.LEFT)
        self.btn_main_documents.grid(row=0, column=0, sticky=tk.E)
        
        # Таблица
        self.tree = ttk.Treeview(self, columns=('id','surname','name','patronymic', 'telephone'), height=15, show='headings')

        self.tree.column('id', width=10, anchor=tk.CENTER)
        self.tree.column('surname', width=180, anchor=tk.CENTER)
        self.tree.column('name', width=110, anchor=tk.CENTER)
        self.tree.column('patronymic', anchor=tk.CENTER)
        self.tree.column('telephone', width=110, anchor=tk.CENTER)

        self.tree.heading('id', text='id')
        self.tree.heading('surname', text='Фамилия')
        self.tree.heading('name', text='Имя')
        self.tree.heading('patronymic', text='Отчество')
        self.tree.heading('telephone', text='Телефон')

        self.tree.bind('<Double-Button-1>', lambda event: self.open_update_dialog())

        #self.tree.pack(side=tk.TOP, fill=tk.BOTH)
        self.tree.grid(row=1, column=0)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self)

        self.grab_set()
        self.focus_set()

    def view_all_executors(self):
        # Очистка таблицы
        [self.tree.delete(i) for i in self.tree.get_children()]
        # Добавление в таблицу
        [self.tree.insert('', 'end', values=[row.id,row.surname,row.name,row.patronymic,row.telephone]) for row in self.parent_data.executors]

    def dialog_new_executor(self):
        NewExecutor(self.parent_root, self.parent_data, self)

    def open_update_dialog(self):
        id_executor = self.tree.set(self.tree.selection()[0],'id')
        UpdateExecutor(self.parent_root, self.parent_data, self, id_executor)
