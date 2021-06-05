# -*- coding: utf-8 -*-

from tkinter import *
from tkinter import ttk
from tkinter import scrolledtext
from tkinter import messagebox
from tkinter import filedialog

def main():
    #Настройка окна
    root = Tk()
    root.title("Титульник")
    root.geometry("900x550+300+300")
    root.iconbitmap(root, default='sns.ico')
    
    #Label
    lbl = Label(root, text="Приветик!", font=("Arial Bold", 50))
    lbl.grid(column=0, row=0)
    
    #Buttons    
    btn = Button(root, text="Не нажимать!", command=lambda: lbl.configure(text="Я же просил..."), fg='red', bg='black')
    btn.grid(column=2, row=0)

    btn = Button(root, text="Отправить", command=lambda: clicked(txt1.get()))
    btn.grid(column=2, row=2)
    #TextBox
    txt1 = Entry(root, width=10)
    txt1.grid(column=1, row=2)
    txt1.focus()

    txt = Entry(root, width=20, state='disabled')
    txt.grid(column=0, row=3)

    #ComboBox
    combo = ttk.Combobox(root)  
    combo['values'] = (1, 2, 3, 4, 5, "Текст")
    combo.current(1)  # установите вариант по умолчанию
    combo.grid(column=0, row=4)
    combo.get()

    #CheckButton
    chk_state = BooleanVar()  
    chk_state.set(True)  # задайте проверку состояния чекбокса  
    chk = Checkbutton(root, text='Выбрать', var=chk_state)  
    chk.grid(column=1, row=4)

    #RadioButton
    selected = IntVar()
    rad1 = Radiobutton(root, text='Первый', value=1, command=lambda: clickedRad(selected), variable=selected)
    rad2 = Radiobutton(root, text='Второй', value=2, command=lambda: clickedRad(selected), variable=selected)
    rad3 = Radiobutton(root, text='Третий', value=3, command=lambda: clickedRad(selected), variable=selected)
    rad1.grid(column=0, row=5)
    rad2.grid(column=1, row=5)
    rad3.grid(column=2, row=5)

    #ScrolledText
    txt = scrolledtext.ScrolledText(root, width=40, height=10)  
    txt.grid(column=0, row=6)
    
    txt.delete(1.0, END)
    
    txt.insert(INSERT, 'Текстовое поле')

    #MessageBox
    btnMessage = Button(root, text="MessageBox", command=clickedMessageBox)
    btnMessage.grid(column=0, rows=7)
    
    btnMessageAnswer = Button(root, text="MessageBox", command=clickedMessageBoxAnswer)
    btnMessageAnswer.grid(column=1, rows=7)

    #SpinBox
    spin = Spinbox(root, from_=0, to=100)
    spin.grid(column=2, row=7)

    var = IntVar()
    var.set(3)
    spin = Spinbox(root, values=(3, 8, 11), width=5, textvariable=var)
    spin.grid(column=3, row=7)

    #ProgressBar
    bar = ttk.Progressbar(root, length=200)
    bar['value'] = 70
    bar.grid(column=4, row=7)

    #FileDialog
    btnDialog = Button(root, text="Файл диалог", command=fileDialog)
    btnDialog.grid(column=3, rows=3)

    btnDialog = Button(root, text="Фолдер диалог", command=folderDialog)
    btnDialog.grid(column=4, rows=3)

    #Menu
    menu = Menu(root)
    new_item = Menu(menu, tearoff=0)  
    new_item.add_command(label='Новый')  
    new_item.add_separator()  
    new_item.add_command(label='Изменить')  
    menu.add_cascade(label='Файл', menu=new_item)
    root.config(menu=menu)

    #Notebook
    tab_control = ttk.Notebook(root)
    tab1 = Frame(tab_control)
    tab2 = Frame(tab_control)
    tab_control.add(tab1, text='Первая')
    tab_control.add(tab2, text='Вторая')
    lbl1 = Label(tab1, text='Вкладка 1')
    lbl1.grid(column=0, row=0)
    lbl2 = Label(tab2, text='Вкладка 2', padx=10, pady=10)
    lbl2.grid(column=0, row=0)
    tab_control.grid(column=0, rows=1)

    root.mainloop()

def clicked(txt):
    res = u"Привет, {}".format(txt)
    print(res)

def clickedRad(selected):
    print(u"Кликнули по радио " + str(selected.get()))

def clickedMessageBox():
    messagebox.showinfo('Заголовок', 'Текст')
    messagebox.showwarning('Предупреждение', 'Текст')
    messagebox.showerror('Ошибка', 'Текст')

def clickedMessageBoxAnswer():
    res = messagebox.askquestion('Заголовок', 'Текст')
    res = messagebox.askyesno('Заголовок', 'Текст')
    res = messagebox.askyesnocancel('Заголовок', 'Текст')
    res = messagebox.askokcancel('Заголовок', 'Текст')
    res = messagebox.askretrycancel('Заголовок', 'Текст')

def fileDialog():
    file = filedialog.askopenfilename(filetypes = (("Text files","*.txt"),("all files","*.*")))
    #file = filedialog.askopenfilename()
    #file = filedialog.askopenfilenames()
    print(file)

def folderDialog():
    directory = filedialog.askdirectory()
    print(directory)

if __name__ == '__main__':
    main()
