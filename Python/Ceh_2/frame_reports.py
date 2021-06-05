# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
import os
import subprocess, sys
import datetime

class HistoryReportsWindow(tk.Toplevel):
    parent_root = None
    parent_data = None
    files = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_main_history_reports()
        self.get_files()
        self.view_files()

    def init_main_history_reports(self):
        self.tree = ttk.Treeview(self, columns=('name','date','path'), height=15, show='headings')
        self.tree.column('name', width=180, anchor=tk.CENTER)
        self.tree.column('date', width=140, anchor=tk.CENTER)
        self.tree.column('path', anchor=tk.CENTER)
        self.tree.heading('name', text='Наименование')
        self.tree.heading('date', text='Дата')
        self.tree.heading('path', text='Путь')
        self.tree.bind('<Double-Button-1>', lambda event: self.open_report())
        #self.tree.grid(row=0, column=0, padx=10, pady=10, sticky=tk.N)
        self.tree.pack(side=tk.TOP, fill=tk.BOTH, expand=tk.YES)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self, 200, 200)

        self.grab_set()
        self.focus_set()

    def get_files(self):
        # Получаем список файлов
        self.files = [os.path.join(self.parent_data.settings.path_reports, fn) for fn in os.listdir(self.parent_data.settings.path_reports)]
        
        #os.path.getctime(path)  # Время создания
        #os.path.getmtime(path)  # Время редактирования
        
        # Сортируем по дате
        if self.files:
            filters = sorted(self.files, key=lambda x: os.path.getctime(x))
            last_ten = filters[-17:]
            self.files = last_ten

    def view_files(self):
        [self.tree.delete(i) for i in self.tree.get_children()]
        [self.tree.insert('', 'end', values=[os.path.basename(row),datetime.datetime.fromtimestamp(os.path.getctime(row)),row]) for row in reversed(self.files)]
    
    def open_report(self):
        path_report = self.tree.set(self.tree.selection()[0],'path')
        if(sys.platform.startswith('win')):
            os.startfile(path_report)
        else:
            opener ="open" if sys.platform == "darwin" else "xdg-open"
            subprocess.call([opener, path_report])
        #subprocess.call(path_report)   # Запуск Win32
