# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from tkinter import scrolledtext

class AboutTheProgramm(tk.Toplevel):
    parent_data = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_data = data
        self.init_about_the_programm()

    def init_about_the_programm(self):
        self.title("Описание программы")

        label_name = tk.Label(self, text='Оборот документов цеха № 2')
        label_name.grid(row=0, column=0, padx=5)

        label_version = tk.Label(self, text='Версия 1.0.4.7')
        label_version.grid(row=1, column=0, padx=5)

        label_copyright = tk.Label(self, text='Copyright(C)2020')
        label_copyright.grid(row=2, column=0, padx=5)

        label_org = tk.Label(self, text='ФКП НТИИМ')
        label_org.grid(row=3, column=0, padx=5)

        label_desc = tk.Label(self, text='Программа для отслеживания документооборота в цехе № 2')
        label_desc.grid(row=4, column=0, padx=5)

        # Кнопки
        btn_cancel = ttk.Button(self, text='Закрыть', command=self.destroy)
        #btn_cancel.place(x=250, y=300)
        btn_cancel.grid(row=5, column=0, padx=10, pady=10, sticky=tk.E)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне
        
