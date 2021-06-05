# -*- coding: utf-8 -*-

import tkinter


def func():
    label_visible_false.place(x=0, y=50)
    button_visible_false.place(x=0, y=100)
    label_visible_true.place_forget()
    button_visible_true.place_forget()


def visible_true():
    label_visible_false.place_forget()
    button_visible_false.place_forget()
    label_visible_true.place(x=0, y=50)
    button_visible_true.place(x=0, y=100)


root = tkinter.Tk()
root.geometry("400x400")
label_visible_true = tkinter.Label(root, text='Не скрытый текст')
label_visible_true.place(x=0, y=50)

button_visible_true = tkinter.Button(root, text='Не скрытая кнопка', command=func)
button_visible_true.place(x=0, y=100)

label_visible_false = tkinter.Label(root, text='Скрытый текст')

button_visible_false = tkinter.Button(root, text='Скрытая кнопка', command=visible_true)

root.mainloop()



