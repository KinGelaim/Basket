# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk

class MenuFrame(tk.Frame):
    parent_root = None
    parent_data = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_main_menu()

    def init_main_menu(self):
        btn_main_documents = ttk.Button(self, text='Документы', command=self.init_main_documents)
        btn_main_documents.grid(row=0, column=0, pady=30, padx=170)

        btn_main_archive = ttk.Button(self, text='Архив', command=self.init_main_archive)
        btn_main_archive.grid(row=1, column=0, pady=30, padx=170)

        btn_main_exit = ttk.Button(self, text='Выход', command=self.parent_root.destroy)
        btn_main_exit.grid(row=2, column=0, pady=30, padx=170)

        self.parent_data.resize_window(self.parent_root, 400, 200)

    def init_main_documents(self):
        self.parent_data.main_menu.pack_forget()
        self.parent_data.all_documents.pack()
        self.parent_data.resize_window(self.parent_root, 50, 50)

    def init_main_archive(self):
        self.parent_data.main_menu.pack_forget()
        self.parent_data.archive_window.pack()
        self.parent_data.resize_window(self.parent_root, 50, 50)
