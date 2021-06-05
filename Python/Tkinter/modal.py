# -*- coding: utf-8 -*-
 
from tkinter import Tk, Toplevel, Button, Label

def func():
    top = Toplevel(root)
    button_top_level = Button(top, text='Нажми', command=lambda: label.config(text='Текст из модального окна')).pack()
    top.transient(root)
    top.grab_set()
    top.focus_set()
    top.wait_window()


root = Tk()
label = Label(root, text='Текст')
label.pack()
button = Button(root, text='openModal', command=func).pack()
root.mainloop()
