# -*- coding: utf-8 -*-

from setting import *
from DB import *
from frame_menu import *
from frame_document import *
from frame_archive import *
from frame_about_the_programm import *
from document import Document
from frame_reports import *
from frame_settings import *
import tkinter as tk
import sys
from tkinter import messagebox

class Data:
    main_menu = None
    all_documents = None
    archive_window = None
    settings = None
    db = None
    documents = []
    executors = []
    archive_documents = []
    parent_root = None
    def __init__(self):
        # Настройки
        self.settings = Setting()

        # База данных
        self.db = DB(self.settings)

        # Получение данных
        self.data_get_all_executors()
        self.data_get_all_documents()
        self.data_get_all_archives()
        
        # Главное окно
        root = tk.Tk()
        root.title('Оборот документов Цех №2')
        #root.geometry('950x700')
        #root.resizable(False, False)
        self.change_icon(root)
    
        # UpMenu
        menu = tk.Menu(root)
        new_item1 = tk.Menu(menu, tearoff=0)  
        #new_item1.add_command(label='Печать')
        item_print = tk.Menu(new_item1, tearoff=0)
        item_print.add_command(label='WORD', command=self.create_word)
        item_print.add_command(label='EXCEL', command=self.create_excel)
        item_print.add_command(label='LibreOffice', state='disabled')
        item_print.add_command(label='HTML', command=self.create_html)
        new_item1.add_cascade(label='Печать', menu=item_print)
        new_item1.add_separator
        new_item1.add_command(label='Последние отчёты', command=self.open_dialog_reports)
        new_item1.add_separator
        new_item1.add_command(label='Выход', command=root.destroy)
        new_item2 = tk.Menu(menu, tearoff=0)
        new_item2.add_command(label='Настройки', command=self.open_settings)
        new_item2.add_separator()
        new_item2.add_command(label='О программе', command=self.open_about_the_programm)
        menu.add_cascade(label='Файл', menu=new_item1)
        menu.add_cascade(label='Сервис', menu=new_item2)
        root.config(menu=menu)
        self.parent_root = root

        # Главное меню
        self.main_menu = MenuFrame(root, self)
        self.main_menu.pack()

        # Страница всех документов
        self.all_documents = DocumentWindow(root, self)

        # Страница архива
        self.archive_window = ArchiveWindow(root, self)
        
        root.mainloop()

    def open_about_the_programm(self):
        AboutTheProgramm(self.parent_root, self)

    def open_settings(self):
        SettingsWindow(self.parent_root, self)

    def data_get_all_documents(self):
        self.documents = self.db.get_all_documents()

    def data_get_all_executors(self):
        self.executors = self.db.get_all_executors()

    def data_get_all_archives(self):
        self.archive_documents = self.db.get_all_documents(1)

    def data_get_all_documents_with_filter(self, name_document_filter):
        self.documents = self.db.get_all_documents_with_filter(name_document_filter)

    def create_html(self):
        Document.create_html(self, self.settings)
        messagebox.showinfo('Информация', 'Создание завершено')

    def create_word(self):
        Document.create_word(self, self.settings)
        messagebox.showinfo('Информация', 'Создание завершено')

    def create_excel(self):
        Document.create_excel(self, self.settings)
        messagebox.showinfo('Информация', 'Создание завершено')

    def open_dialog_reports(self):
        HistoryReportsWindow(self.parent_root, self)

    # Размеры экрана и положение в центре
    def resize_window(self, rootik, add_width=0, add_height=0):
        rootik.state('normal')
        rootik.geometry("")
        rootik.update_idletasks()
        s = rootik.geometry()
        s = s.split('+')
        s = s[0].split('x')
        width_root = int(s[0]) + add_width
        height_root = int(s[1]) + add_height
        w = self.parent_root.winfo_screenwidth()
        h = self.parent_root.winfo_screenheight()
        w = w // 2
        h = h // 2
        w = w - width_root // 2
        h = h - height_root // 2
        rootik.geometry('{}x{}+{}+{}'.format(width_root,height_root,w,h))
        #rootik.geometry('+{}+{}'.format(w,h))

    def change_icon(self, root):
        if(sys.platform.startswith('win')):
            #root.iconbitmap(root, default=self.settings.path_resource+'/sns.ico')
            img = tk.PhotoImage(file=self.settings.path_resource+'/study.gif')
            root.tk.call('wm', 'iconphoto', root._w, img)
        else:
            img = tk.PhotoImage(file=self.settings.path_resource+'/study.gif')
            root.tk.call('wm', 'iconphoto', root._w, img)
