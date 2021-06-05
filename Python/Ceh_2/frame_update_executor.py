# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from executor import *
from frame_new_executor import *

class UpdateExecutor(NewExecutor):
    executor = None
    def __init__(self, root, data, executor_window, id_executor):
        super().__init__(root, data, executor_window)
        self.get_info_executor(id_executor)
        self.init_update_executor()
        self.load_info_executor()

    def get_info_executor(self, id_executor):
        for executor in self.parent_data.executors:
            if(executor.id == int(id_executor)):
                self.executor = executor
                break

    def load_info_executor(self):
        if(self.executor.surname != None):
            self.entry_surname.insert(0, self.executor.surname)
        if(self.executor.name != None):
            self.entry_name.insert(0, self.executor.name)
        if(self.executor.patronymic != None):
            self.entry_patronymic.insert(0, self.executor.patronymic)
        if(self.executor.telephone != None):
            self.entry_telephone.insert(0, self.executor.telephone)
            
    def init_update_executor(self):
        self.title("Изменить исполнителя")

        self.btn_ok.destroy()

        self.btn_update = tk.Button(self, text='Изменить')
        self.btn_update.bind('<Button-1>', lambda event: self.update_executor())
        self.btn_update.bind('<Button-1>', lambda event: self.parent_data.data_get_all_executors(), add='+')
        self.btn_update.bind('<Button-1>', lambda event: self.executor_window.view_all_executors(), add='+')
        self.btn_update.bind('<Button-1>', lambda event: self.destroy(), add='+')
        self.btn_update.grid(row=8, column=0, pady=10)

    def update_executor(self):
        self.executor.surname = self.entry_surname.get()
        self.executor.name = self.entry_name.get()
        self.executor.patronymic = self.entry_patronymic.get()
        self.executor.telephone = self.entry_telephone.get()
        self.parent_data.db.update_executor(self.executor)
