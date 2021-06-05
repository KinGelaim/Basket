# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from executor import *

class NewExecutor(tk.Toplevel):
    parent_data = None
    executor_window = None
    def __init__(self, root, data, executor_window):
        super().__init__(root)
        self.parent_data = data
        self.executor_window = executor_window
        self.init_new_executor()

    def init_new_executor(self):
        self.title("Новый исполнитель")

        label_surname = tk.Label(self, text='Фамилия')
        label_surname.grid(row=0, column=0, padx=5, columnspan=2)
        self.entry_surname = tk.Entry(self)
        self.entry_surname.grid(row=1, column=0, padx=5, columnspan=2)

        label_name = tk.Label(self, text='Имя')
        label_name.grid(row=2, column=0, padx=5, columnspan=2)
        self.entry_name = tk.Entry(self)
        self.entry_name.grid(row=3, column=0, padx=5, columnspan=2)

        label_patronymic = tk.Label(self, text='Отчество')
        label_patronymic.grid(row=4, column=0, padx=5, columnspan=2)
        self.entry_patronymic = tk.Entry(self)
        self.entry_patronymic.grid(row=5, column=0, padx=5, columnspan=2)

        label_telephone = tk.Label(self, text='Телефон')
        label_telephone.grid(row=6, column=0, padx=5, columnspan=2)
        self.entry_telephone = tk.Entry(self)
        self.entry_telephone.grid(row=7, column=0, padx=5, columnspan=2)

        # Кнопки
        btn_cancel = tk.Button(self, text='Отмена', command=self.destroy)
        #btn_cancel.place(x=250, y=300)
        btn_cancel.grid(row=8, column=1, pady=10)

        self.btn_ok = tk.Button(self, text='Добавить')
        self.btn_ok.bind('<Button-1>', lambda event: self.create_new_executor())
        self.btn_ok.bind('<Button-1>', lambda event: self.parent_data.data_get_all_executors(), add='+')
        self.btn_ok.bind('<Button-1>', lambda event: self.executor_window.view_all_executors(), add='+')
        self.btn_ok.bind('<Button-1>', lambda event: self.destroy(), add='+')
        #self.btn_ok.place(x=170, y=300)
        self.btn_ok.grid(row=8, column=0, pady=10)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

    def create_new_executor(self):
        executor = Executor()
        executor.surname = self.entry_surname.get()
        executor.name = self.entry_name.get()
        executor.patronymic = self.entry_patronymic.get()
        executor.telephone = self.entry_telephone.get()
        self.parent_data.db.create_executor(executor)
