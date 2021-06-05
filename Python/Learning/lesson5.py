# -*- coding: utf-8 -*-

# Событийно-ориентированное программирование
# Программа иницилизируется
# Затем засыпает до прихода события, когда событие приходит
# Программа просыпается и обрабатывает событие
# Если пришли новые события, а программа обрабатывает старое, то
# Событие помещается в очередь событий и затем постепенно вытаскивается

from tkinter import *

def handler1(event):
    print('Hello World!')

def handler2(event):
    exit()  # kill programm (terminate)

root = Tk()

my_label = Label(root, text="Hello, world!", font=('Times', 40))
my_label.bind('<Button-1>', handler1)
my_label.bind('<Button-3>', handler2)
my_label.pack()



root.mainloop()
