# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from tkinter import scrolledtext
from tkinter import filedialog
from tkinter import messagebox
from resolution import *
import os

class NewDocument(tk.Toplevel):
    parent_root = None
    parent_data = None
    file = None
    id_document = None
    def __init__(self, root, data):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.init_document_frame()

    def init_document_frame(self):
        self.title("Добавить документ")
        #self.geometry("330x230")
        #self.resizable(False, False)

        # Наименование документа
        label_name_document = tk.Label(self, text='Наименование документа:')
        #label_name_document.place(x=10, y=10)
        label_name_document.grid(row=0, column=0, columnspan=3)

        self.txt_name_document = scrolledtext.ScrolledText(self, width=30, height=5)
        #self.txt_name_document.place(x=10, y=30)
        self.txt_name_document.grid(row=1, column=0, columnspan=3)
        
        # Дата
        label_date = tk.Label(self, text='Дата:')
        #label_date.place(x=10, y=120)
        label_date.grid(row=2, column=0)

        self.entry_date = ttk.Entry(self)
        #self.entry_date.place(x=10, y=140)
        self.entry_date.grid(row=3, column=0, padx=10)

        # Скан
        btnDialog = tk.Button(self, text="Скан", command=self.fileDialog)
        #btnDialog.place(x=170,y=140)
        btnDialog.grid(row=3, column=1)

        self.label_resolution = tk.Label(self, text='')
        #self.label_resolution.place(x=210, y=143)
        self.label_resolution.grid(row=3, column=2)

        # Кнопки
        btn_cancel = ttk.Button(self, text='Закрыть', command=self.destroy)
        #btn_cancel.place(x=170, y=170)
        btn_cancel.grid(row=4, column=2, pady=10)

        self.btn_ok = ttk.Button(self, text='Добавить')
        #self.btn_ok.place(x=70, y=170)
        self.btn_ok.grid(row=4, column=1)
        self.btn_ok.bind('<Button-1>', lambda event: self.create_document())

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self, 20, 20)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

    def create_document(self):
        if(self.txt_name_document.get("1.0", tk.END) != None and self.txt_name_document.get("1.0", tk.END) != '' and self.entry_date.get() != None and self.entry_date.get() != ''):
            self.id_document = self.parent_data.db.create_document(self.txt_name_document.get("1.0", tk.END), self.entry_date.get())
            self.parent_data.data_get_all_documents()
            self.parent_data.all_documents.get_all_directions()
            self.parent_data.all_documents.view_all_documents()
            self.create_resolution(self.file)
            self.destroy()
        else:
            messagebox.showinfo('Внимание!', 'Заполните поля!')

    def fileDialog(self):
        self.file = filedialog.askopenfilename(filetypes = (("all files","*.*"),("pdf","*.pdf"),("bmp","*.bmp")))
        self.label_resolution.configure(text=os.path.basename(self.file))

    def create_resolution(self, file):
        if(file != None):
            new_path = Resolution.copy_resolution(self.parent_data.settings, file)
            self.parent_data.db.create_resolution(self.id_document, new_path)
