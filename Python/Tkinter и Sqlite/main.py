# -*- coding: utf-8 -*-

import tkinter as tk
from tkinter import ttk
import sqlite3

class Main(tk.Frame):
    def __init__(self, root, db):
        super().__init__(root)
        self.init_main()
        self.db = db
        self.view_records()
        
    def init_main(self):
        # Верхнее меню
        toolbar = tk.Frame(bg='#d7d8e0', bd=2)
        toolbar.pack(side=tk.TOP, fill=tk.X)

        self.add_img = tk.PhotoImage(file='resources/a5abe1129b49c2c3f084a50daf69ae26.gif')
        btn_open_dialog = tk.Button(toolbar, text='Добавить финансы', command=self.open_dialog, bg='#d7d8e0', bd=0, compound=tk.TOP, image=self.add_img)
        btn_open_dialog.pack(side=tk.LEFT)

        self.search_img = tk.PhotoImage(file='resources/orig.gif', width=100)
        btn_open_seacrh_dialog = tk.Button(toolbar, text='Поиск', command=self.open_search_dialog, bg='#d7d8e0', bd=0, compound=tk.TOP, image=self.search_img)
        btn_open_seacrh_dialog.pack(side=tk.LEFT)

        # Таблица
        self.tree = ttk.Treeview(self, columns=('id','description','cost', 'total'), height=15, show='headings')

        self.tree.column('id', width=30, anchor=tk.CENTER)
        self.tree.column('description', width=365, anchor=tk.CENTER)
        self.tree.column('cost', width=150, anchor=tk.CENTER)
        self.tree.column('total', width=150, anchor=tk.CENTER)

        self.tree.heading('id', text='ID')
        self.tree.heading('description', text='Наименование')
        self.tree.heading('cost', text='Статья доходов/расходов')
        self.tree.heading('total', text='Сумма')

        self.tree.bind('<Double-Button-1>', lambda event: self.open_update_dialog())
        #self.tree.bind('<KeyPress>', lambda event: print('asd'))    # Нажатие клавиши
        #self.tree.bind('<KeyRelease>', lambda event: self.on_key_release(event))  # Отпуск клавиши
        self.tree.bind('<Key-Delete>', lambda event: self.delete_record())

        self.tree.pack()
        
    def open_dialog(self):
        Child()

    def open_update_dialog(self):
        ChildUpdate()

    #def on_key_release(self, event):
    #    print(event.keycode)

    def open_search_dialog(self):
        Search()
        
    def new_record(self, description, cost, total):
        self.db.insert_data(description, cost, total)
        self.view_records()

    def update_record(self, description, cost, total):
        self.db.cursor.execute('UPDATE finance SET description=?, cost=?, total=? WHERE id=?', (description, cost, total, self.tree.set(self.tree.selection()[0], '#1')))
        self.db.connect.commit()
        self.view_records()

    def delete_record(self):
        res = tk.messagebox.askquestion('Удаление', 'Вы уверены, что желаете удалить запись?')
        if(res == 'yes'):
            self.db.cursor.execute('DELETE FROM finance WHERE id=?', (self.tree.set(self.tree.selection()[0], '#1')))
            self.db.connect.commit()
            self.view_records()

    def search_record(self, description):
        description = ('%' + description + '%',)
        self.db.cursor.execute('''SELECT * FROM finance WHERE description LIKE ?''', description)
        self.db.connect.commit()
        # Очистка таблицы
        [self.tree.delete(i) for i in self.tree.get_children()]
        # Добавление в таблицу
        [self.tree.insert('', 'end', values=row) for row in self.db.cursor.fetchall()]

    def view_records(self):
        self.db.cursor.execute('SELECT * FROM finance')
        # Очистка таблицы
        [self.tree.delete(i) for i in self.tree.get_children()]
        # Добавление в таблицу
        [self.tree.insert('', 'end', values=row) for row in self.db.cursor.fetchall()]

class Child(tk.Toplevel):
    def __init__(self):
        super().__init__(root)
        self.init_child()
        self.parent_view = app  #Вытащилось из начала программы (чёт не нрава это)

    def init_child(self):
        self.title("Добавить финансы")
        self.geometry("400x220+400+300")
        self.resizable(False, False)

        # Подписи полей
        label_cost = tk.Label(self, text='Статья:')
        label_cost.place(x=50, y=50)
        label_description = tk.Label(self, text='Наименование:')
        label_description.place(x=50, y=80)
        label_total = tk.Label(self, text='Сумма:')
        label_total.place(x=50, y=110)
        
        # Поле выбора затрата или прибыль
        self.combobox = ttk.Combobox(self, values=['Доход','Расход'])
        self.combobox.current(0)
        self.combobox.place(x=200, y=50)
        
        # Поля ввода
        self.entry_description = ttk.Entry(self)
        self.entry_description.place(x=200, y=80)
        
        self.entry_total = ttk.Entry(self)
        self.entry_total.place(x=200, y=110)

        # Кнопки
        btn_cancel = ttk.Button(self, text='Закрыть', command=self.destroy)
        btn_cancel.place(x=300, y=170)

        self.btn_ok = ttk.Button(self, text='Добавить')
        self.btn_ok.place(x=220, y=170)
        self.btn_ok.bind('<Button-1>', lambda event: self.parent_view.new_record(self.entry_description.get(), self.combobox.get(), self.entry_total.get()))
        self.btn_ok.bind('<Button-1>', lambda event: self.destroy(), add='+')
        
        self.grab_set()     # Захват всех действий
        self.focus_set()    # Фокус на окне

class ChildUpdate(Child):
    def __init__(self):
        super().__init__()
        self.init_edit()
        self.default_data()

    def init_edit(self):
        self.title('Редактировать позицию')
        btn_edit = ttk.Button(self, text='Редактировать')
        btn_edit.place(x=205, y=170)
        btn_edit.bind('<Button-1>', lambda event: self.parent_view.update_record(self.entry_description.get(), self.combobox.get(), self.entry_total.get()))
        btn_edit.bind('<Button-1>', lambda event: self.destroy(), add='+')

        # Удалить кнопку подтвердить из формы добавления
        self.btn_ok.destroy()

    def default_data(self):
        update_data = self.parent_view.tree.set(self.parent_view.tree.selection()[0])
        if(update_data['cost'] == 'Расход'):
            self.combobox.current(1)
        self.entry_description.insert(0, update_data['description'])
        self.entry_total.insert(0, update_data['total'])

class Search(tk.Toplevel):
    def __init__(self):
        super().__init__()
        self.init_search()
        self.parent_view = app

    def init_search(self):
        self.title('Поиск')
        self.geometry('300x100+400+300')
        self.resizable(False, False)

        label = tk.Label(self, text='Поиск')
        label.place(x=50, y=20)
        entry = tk.Entry(self)
        entry.place(x=105, y=20, width=150)
        button_close = ttk.Button(self, text='Закрыть', command=self.destroy)
        button_close.place(x=185,y=50)
        #button_search = ttk.Button(self, text='Поиск', command=lambda:self.parent_view.search_record(entry.get()))
        button_search = ttk.Button(self, text='Поиск')
        button_search.bind('<Button-1>', lambda event:self.parent_view.search_record(entry.get()))
        button_search.bind('<Button-1>', lambda event: self.destroy(), add='+')
        button_search.place(x=105, y=50)

        self.grab_set()
        self.focus_set()

class DB:
    def __init__(self):
        self.connect = sqlite3.connect('finance.db')
        self.cursor = self.connect.cursor()
        self.cursor.execute('CREATE TABLE IF NOT EXISTS finance (id INTEGER PRIMARY KEY, description TEXT, cost TEXT, total REAL)')
        self.connect.commit()

    def insert_data(self, description, cost, total):
        self.cursor.execute('INSERT INTO finance (description, cost, total) VALUES (?,?,?)', (description,cost,total))
        self.connect.commit()        

if __name__ == '__main__':
    db = DB()
    root = tk.Tk()
    app = Main(root, db)
    app.pack()
    root.title("Финансики")
    root.geometry("650x450+300+200")
    root.resizable(False, False)
    root.mainloop()
