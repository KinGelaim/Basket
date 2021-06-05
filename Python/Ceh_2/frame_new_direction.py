# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
from tkinter import scrolledtext
from tkinter import messagebox
import os
from datetime import date
from datetime import datetime
from direction import *

class NewDirection(tk.Toplevel):
    parent_data = None
    document = None
    def __init__(self, root, data, update_document_window, document):
        super().__init__(root)
        self.parent_data = data
        self.update_document_window = update_document_window
        self.document = document
        self.init_new_direction()

    def init_new_direction(self):
        self.title("Новое направление")
        #self.geometry("350x350")
        #self.resizable(False, False)

        # Левый столбец
        label_date_shipment = tk.Label(self, text='Дата внесения')
        #label_date_shipment.place(x=10, y=10)
        label_date_shipment.grid(row=0, column=0, padx=5)

        self.entry_date_shipment = tk.Entry(self)
        #self.entry_date_shipment.place(x=10, y=30)
        self.entry_date_shipment.grid(row=1, column=0, padx=5)

        label_executor = tk.Label(self, text='Исполнитель')
        #label_executor.place(x=10,y=50)
        label_executor.grid(row=2, column=0, padx=5)

        self.combo = ttk.Combobox(self)
        pr_executors = [i.surname for i in self.parent_data.executors]
        self.combo['values'] = pr_executors
        #self.combo.place(x=10, y=80)
        self.combo.grid(row=3, column=0, padx=10)
        self.combo.get()

        label_date_execute = tk.Label(self, text='Дата исполнения')
        #label_date_execute.place(x=10, y=100)
        label_date_execute.grid(row=4, column=0)

        self.entry_date_execute = tk.Entry(self)
        #self.entry_date_execute.place(x=10, y=120)
        self.entry_date_execute.grid(row=5, column=0)

        label_instruction = tk.Label(self, text='Поручение')
        #label_instruction.place(x=10, y=150)
        label_instruction.grid(row=6, column=0)

        self.txt_instruction = scrolledtext.ScrolledText(self, width=15, height=5)
        #self.txt_instruction.place(x=10, y=180)
        self.txt_instruction.grid(row=7, column=0)

        # Правый столбец
        label_executor_return = tk.Label(self, text='Кто вернул')
        #label_executor_return.place(x=170,y=50)
        label_executor_return.grid(row=2, column=1)

        self.combo_return = ttk.Combobox(self)
        self.combo_return['values'] = pr_executors
        #self.combo_return.place(x=170, y=80)
        self.combo_return.grid(row=3, column=1)
        self.combo_return.get()

        label_date_return = tk.Label(self, text='Дата возврата')
        #label_date_return.place(x=170, y=100)
        label_date_return.grid(row=4, column=1)

        self.entry_date_return = tk.Entry(self)
        #self.entry_date_return.place(x=170, y=120)
        self.entry_date_return.grid(row=5, column=1)

        label_instruction_result = tk.Label(self, text='Выполнение')
        #label_instruction_result.place(x=170, y=150)
        label_instruction_result.grid(row=6, column=1)

        self.txt_instruction_result = scrolledtext.ScrolledText(self, width=15, height=5)
        #self.txt_instruction_result.place(x=170, y=180)
        self.txt_instruction_result.grid(row=7, column=1)

        # Кнопки
        btn_cancel = tk.Button(self, text='Отмена', command=self.destroy)
        #btn_cancel.place(x=250, y=300)
        btn_cancel.grid(row=8, column=1, pady=10)

        self.btn_ok = tk.Button(self, text='Добавить')
        self.btn_ok.bind('<Button-1>', lambda event: self.create_new_direction())
        #self.btn_ok.bind('<Button-1>', lambda event: self.update_document_window.refresh_document_info(), add='+')
        #self.btn_ok.bind('<Button-1>', lambda event: self.destroy(), add='+')
        #self.btn_ok.place(x=170, y=300)
        self.btn_ok.grid(row=8, column=0, pady=10)

        self.parent_data.change_icon(self)
        self.parent_data.resize_window(self, 20, 20)
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

    def create_new_direction(self):
        if(self.entry_date_shipment.get() != None and self.entry_date_shipment.get() != '' and self.combo.get() != None and self.combo.get() != '' and self.entry_date_execute.get() != None and self.entry_date_execute.get() != ''):
            check_date = True
            try:
                datetime.strptime(self.entry_date_execute.get(),'%d.%m.%Y')
                split_date = self.entry_date_execute.get().split('.')
                k = date(year=int(split_date[2]), month=int(split_date[1]), day=int(split_date[0]))
            except:
                check_date = False
            if(check_date == True):
                direction = Direction()
                direction.id_document = self.document.id
                direction.date_shipment = self.entry_date_shipment.get()
                for executor in self.parent_data.executors:
                    if(executor.surname == self.combo.get()):
                        direction.executor_shipment = executor.id
                        break
                direction.name_executor_shipment = self.combo.get()
                direction.date_execute = self.entry_date_execute.get()
                direction.instruction = self.txt_instruction.get('1.0', tk.END)
                direction.date_return = self.entry_date_return.get()
                for executor in self.parent_data.executors:
                    if(executor.surname == self.combo_return.get()):
                        direction.executor_return = executor.id
                        break
                direction.name_executor_return = self.combo_return.get()
                direction.result_instruction = self.txt_instruction_result.get('1.0', tk.END)
                self.parent_data.db.create_direction(direction)
                
                self.update_document_window.refresh_document_info()
                self.destroy()
            else:
                messagebox.showinfo('Внимание!', 'Дата исполнения должна являться датой формата: день.месяц.год!')
        else:
            messagebox.showinfo('Внимание!', 'Заполните поле даты внесения, исполнителя и дата исполнения!')
