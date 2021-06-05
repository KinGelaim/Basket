# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from tkinter import scrolledtext
from tkinter import filedialog
from resolution import *
from frame_new_direction import *
from frame_update_direction import *
import os
import datetime
import subprocess, sys

class UpdateDocument(tk.Toplevel):
    parent_root = None
    parent_data = None
    document = None
    is_change_archive = False
    def __init__(self, root, data, id_document):
        super().__init__(root)
        self.parent_root = root
        self.parent_data = data
        self.get_document_info(id_document)
        self.init_update_document_frame()

    def init_update_document_frame(self):
        self.title("Редактировать документ")
        #self.geometry("900x450")
        #self.resizable(False, False)

        # Наименование документа
        label_name_document = tk.Label(self, text='Наименование документа:')
        #label_name_document.place(x=10, y=5)
        label_name_document.grid(row=0, column=0)

        self.txt_name_document = scrolledtext.ScrolledText(self, width=30, height=5)
        #self.txt_name_document.place(x=10, y=30)
        self.txt_name_document.grid(row=1, column=0, columnspan=2, padx=10)
        self.txt_name_document.insert(tk.INSERT, self.document.name_document)

        # Архив
        self.chk_state = tk.IntVar()
        if(self.document.archive == 1):
            self.chk_state.set(1)
        self.archive = tk.Checkbutton(self, text='Архив', var=self.chk_state, onvalue=1, offvalue=0, command=self.change_archive)  
        #self.archive.place(x=200, y=5)
        self.archive.grid(row=0, column=1)
        
        # Дата
        label_date = tk.Label(self, text='Дата:')
        #label_date.place(x=10, y=120)
        label_date.grid(row=2, column=0)

        self.entry_date = ttk.Entry(self)
        self.entry_date.insert(0, self.document.date_document)
        #self.entry_date.place(x=10, y=140)
        self.entry_date.grid(row=3, column=0)

        # Скан
        btnDialog = tk.Button(self, text="Новый скан", command=self.new_resolution_dialog)
        #btnDialog.place(x=170,y=140)
        btnDialog.grid(row=3, column=1, pady=10)

        self.tree_resolutions = ttk.Treeview(self, columns=('id','name_resolution'), height=15, show='headings')
        self.tree_resolutions.column('id', width=20, anchor=tk.CENTER)
        self.tree_resolutions.column('name_resolution', width=170, anchor=tk.CENTER)
        self.tree_resolutions.heading('id', text='id')
        self.tree_resolutions.heading('name_resolution', text='Наименование скана')
        self.tree_resolutions.bind('<Double-Button-1>', lambda event: self.open_resolution())
        self.tree_resolutions.bind('<Key-Delete>', lambda event: self.delete_resolution())
        #self.tree_resolutions.place(x=10, y=180)
        self.tree_resolutions.grid(row=4, column=0, columnspan=2)

        self.set_tree_resolutions()

        #Направления
        label_direction = tk.Label(self, text='Направления')
        #label_direction.place(x=280, y=5)
        label_direction.grid(row=0, column=3)

        btn_new_direction = tk.Button(self, text='Направить документ', command=self.new_direction)
        #btn_new_direction.place(x=650, y=5)
        btn_new_direction.grid(row=0, column=4, sticky=tk.E, padx=5, pady=5)

        self.tree_directions = ttk.Treeview(self, columns=('id','date_shipment','name_executor_shipment','date_execute','instruction','period'), height=15, show='headings')
        self.tree_directions.column('id', minwidth=10, width=50, anchor=tk.CENTER)
        self.tree_directions.column('date_shipment', minwidth=100, width=100, anchor=tk.CENTER)
        self.tree_directions.column('name_executor_shipment', minwidth=100, width=150, anchor=tk.CENTER)
        self.tree_directions.column('date_execute', minwidth=100, width=100, anchor=tk.CENTER)
        self.tree_directions.column('instruction', minwidth=150, width=200, stretch=True, anchor=tk.CENTER)
        self.tree_directions.column('period', minwidth=100, width=100, anchor=tk.CENTER)
        self.tree_directions.heading('id', text='id')
        self.tree_directions.heading('date_shipment', text='Дата внесения')
        self.tree_directions.heading('name_executor_shipment', text='Кому направлено')
        self.tree_directions.heading('date_execute', text='Дата исполнения')
        self.tree_directions.heading('instruction', text='Поручение')
        self.tree_directions.heading('period', text='Срок')
        #self.tree_directions.place(x=280, y=30)
        self.tree_directions.bind('<Double-Button-1>', lambda event: self.update_direction())
        self.tree_directions.bind('<Key-Delete>', lambda event: self.delete_direction())
        self.tree_directions.grid(row=1, column=3, columnspan=2, rowspan=4)
        self.set_tree_direcions()

        # Сохранить изменения в документе
        self.btn_ok = tk.Button(self, text='Сохранить', command=self.update_document)
        #self.btn_ok.place(x=750, y=380)
        self.btn_ok.grid(row=5, column=4, sticky=tk.E, padx=5, pady=5)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self, 20, 20)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

    def get_document_info(self, id_document):
        for document in self.parent_data.documents:
            if(document.id == int(id_document)):
                self.document = document
                break
        self.parent_data.db.get_resolutions(self.document)
        self.parent_data.db.get_directions(self.document)

    def refresh_document_info(self):
        self.parent_data.db.get_resolutions(self.document)
        self.parent_data.db.get_directions(self.document)
        self.set_tree_resolutions()
        self.set_tree_direcions()

    def change_archive(self):
        self.is_change_archive = True

    def update_document(self):
        self.parent_data.db.update_document(self.document.id, self.txt_name_document.get("1.0", tk.END), self.entry_date.get(), self.chk_state.get())
        self.parent_data.all_documents.refresh_all_documents()
        if(self.is_change_archive):
            self.parent_data.archive_window.refresh_all_archives()
        self.destroy()

    def set_tree_resolutions(self):
        [self.tree_resolutions.delete(i) for i in self.tree_resolutions.get_children()]
        [self.tree_resolutions.insert('', 'end', values=[row.id,row.name_resolution]) for row in self.document.resolutions]

    def open_resolution(self):
        id_resolution_selected = self.tree_resolutions.set(self.tree_resolutions.selection()[0],'id')
        for resolution in self.document.resolutions:
            if(resolution.id == int(id_resolution_selected)):
                if(sys.platform.startswith('win')):
                    os.startfile(resolution.path_resolution)
                else:
                    opener ="open" if sys.platform == "darwin" else "xdg-open"
                    subprocess.call([opener, resolution.path_resolution])
                break

    def new_resolution_dialog(self):
        file = filedialog.askopenfilename(filetypes = (("all files","*.*"),("pdf","*.pdf"),("bmp","*.bmp")))
        if(file != None):
            new_path = Resolution.copy_resolution(self.parent_data.settings, file)
            self.parent_data.db.create_resolution(self.document.id, new_path)
        self.get_document_info(self.document.id)
        self.set_tree_resolutions()

    def delete_resolution(self):
        res = tk.messagebox.askquestion('Удаление', 'Вы уверены, что желаете удалить запись?')
        if(res == 'yes'):
            id_resolution_selected = self.tree_resolutions.set(self.tree_resolutions.selection()[0],'id')
            for resolution in self.document.resolutions:
                if(resolution.id == int(id_resolution_selected)):
                    Resolution.delete_resolution(resolution.path_resolution)
                    break
            self.parent_data.db.delete_resolution(id_resolution_selected)
            self.refresh_document_info()
            
    def set_tree_direcions(self):
        [self.tree_directions.delete(i) for i in self.tree_directions.get_children()]
        for row in self.document.directions:
            for executer in self.parent_data.executors:
                if(executer.id == row.executor_shipment):
                    row.name_executor_shipment = executer.surname
                if(executer.id == row.executor_return):
                    row.name_executor_return = executer.surname
            if(row.date_return == None or row.date_return == ''):
                row.period = datetime.datetime.strptime(row.date_execute,'%d.%m.%Y').toordinal() - datetime.datetime.now().toordinal()
            else:
                row.period = "Выполнен"
            self.tree_directions.insert('', 'end', values=[row.id,row.date_shipment,row.name_executor_shipment,row.date_execute,row.instruction,row.period])

    def new_direction(self):
        NewDirection(self.parent_root, self.parent_data, self, self.document)

    def update_direction(self):
        id_selected_direction = self.tree_directions.set(self.tree_directions.selection()[0],'id')
        UpdateDirection(self.parent_root, self.parent_data, self, self.document, id_selected_direction)

    def delete_direction(self):
        res = tk.messagebox.askquestion('Удаление', 'Вы уверены, что желаете удалить запись?')
        if(res == 'yes'):
            id_selected_direction = self.tree_directions.set(self.tree_directions.selection()[0],'id')
            self.parent_data.db.delete_direction(id_selected_direction)
            self.refresh_document_info()
