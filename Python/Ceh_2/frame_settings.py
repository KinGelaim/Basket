# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from tkinter import filedialog
from tkinter import messagebox
import os

class SettingsWindow(tk.Toplevel):
    parent_root = None
    parent_data = None
    pr_path_db = None
    pr_path_resource = None
    pr_path_resolution = None
    pr_path_reports = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_main_settings()
        self.init_data_settings()

    def init_main_settings(self):
        label_path_db = tk.Label(self, text='Путь к БД:')
        label_path_db.grid(row=0, column=0)
        btn_path_db = ttk.Button(self, text='Выбрать путь к БД', command=self.update_path_db)
        btn_path_db.grid(row=1, column=0)
        label_path_resource = tk.Label(self, text='Путь к ресурсам:')
        label_path_resource.grid(row=2, column=0)
        btn_path_resource = ttk.Button(self, text='Выбрать путь к ресурсам', command=self.update_path_resource)
        btn_path_resource.grid(row=3, column=0)
        label_path_resolution = tk.Label(self, text='Путь к сканам:')
        label_path_resolution.grid(row=4, column=0)
        btn_path_resolution = ttk.Button(self, text='Выбрать путь к сканам', command=self.update_path_resolution)
        btn_path_resolution.grid(row=5, column=0)
        label_path_reports = tk.Label(self, text='Путь к отчётам:')
        label_path_reports.grid(row=6, column=0)
        btn_path_reports = ttk.Button(self, text='Выбрать путь к отчётам', command=self.update_path_reports)
        btn_path_reports.grid(row=7, column=0)

        self.label_setting_path_db = tk.Label(self)
        self.label_setting_path_db.grid(row=0, column=1)
        self.label_setting_path_resource = tk.Label(self)
        self.label_setting_path_resource.grid(row=2, column=1)
        self.label_setting_path_resolution = tk.Label(self)
        self.label_setting_path_resolution.grid(row=4, column=1)
        self.label_setting_path_reports = tk.Label(self)
        self.label_setting_path_reports.grid(row=6, column=1)

        btn_ok = ttk.Button(self, text='Сохранить', command=self.save_settings)
        btn_ok.grid(row=8, column=1)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self, 170, 10)

        self.grab_set()
        self.focus_set()

    def init_data_settings(self):
        self.label_setting_path_db.configure(text=self.parent_data.settings.path_db)
        self.label_setting_path_resource.configure(text=self.parent_data.settings.path_resource)
        self.label_setting_path_resolution.configure(text=self.parent_data.settings.path_resolution)
        self.label_setting_path_reports.configure(text=self.parent_data.settings.path_reports)

    def update_path_db(self):
        file = filedialog.askopenfilename(filetypes = (("all files","*.*"),))
        self.pr_path_db = file

    def update_path_resource(self):
        file = directory = filedialog.askdirectory()
        self.pr_path_resource = file

    def update_path_resolution(self):
        file = directory = filedialog.askdirectory()
        self.pr_path_resolution = file

    def update_path_reports(self):
        file = directory = filedialog.askdirectory()
        self.pr_path_reports = file

    def save_settings(self):
        if(self.pr_path_db != None and self.pr_path_db != ''):
            self.parent_data.settings.path_db = self.pr_path_db
        if(self.pr_path_resource != None and self.pr_path_resource != ''):
            self.parent_data.settings.path_resource = self.pr_path_resource
        if(self.pr_path_resolution != None and self.pr_path_resolution != ''):
            self.parent_data.settings.path_resolution = self.pr_path_resolution
        if(self.pr_path_reports != None and self.pr_path_reports != ''):
            self.parent_data.settings.path_reports = self.pr_path_reports
        self.parent_data.settings.create_settings_file()
        messagebox.showinfo('Информация', 'Настройки обновлены')
        self.init_data_settings()

