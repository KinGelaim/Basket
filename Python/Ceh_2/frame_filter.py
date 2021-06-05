# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk

class SearchFilter(tk.Toplevel):
    parent_data = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_data = data
        self.init_filter_frame()

    def init_filter_frame(self):
        self.title("Фильтр по документам")
        self.geometry("220x100")
        #self.resizable(False, False)

        # Наименование документа
        label_name_document = tk.Label(self, text='Наименование документа содержит:')
        label_name_document.grid(row=0, column=0, columnspan=2)

        self.entry_name_document = tk.Entry(self)
        self.entry_name_document.grid(row=1, column=0, columnspan=2, padx=10)

        # Кнопки
        btn_cancel = ttk.Button(self, text='Закрыть', command=self.destroy)
        btn_cancel.grid(row=2, column=1, pady=10)

        self.btn_ok = ttk.Button(self, text='Фильтровать', command=self.use_filter)
        self.btn_ok.grid(row=2, column=0)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

    def use_filter(self):
        self.parent_data.all_documents.refresh_document_filter(self.entry_name_document.get())
        self.destroy()
