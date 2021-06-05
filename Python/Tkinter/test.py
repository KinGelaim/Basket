# -*- coding: utf-8 -*-

import math
from tkinter import *

def main():
    root = Tk()
    root.title("Построение гарфика фукнции y = sin(x)")
    root.geometry("1320x640+300+300")
    
    canvas = Canvas(root, width=1040, height=640, bg='#002')
    canvas.pack(side="right")
    
    # Сетка по вертикали
    for y in range(21):
        k = 50 * y
        canvas.create_line(20+k, 620, 20+k, 20, width=2, fill='blue')

    # Сетка по горизонту
    for x in range(13):
        k = 50 * x
        canvas.create_line(20, 20+k, 1020, 20+k, width=2, fill='blue')

    # Линия осей
    canvas.create_line(20, 20, 20, 620, width=1, arrow=FIRST, fill='white')     #x
    canvas.create_line(10, 320, 1020, 320, width=1, arrow=LAST, fill='white')    #y

    # Подписи осей
    canvas.create_text(20, 10, text="300", fill='white')
    canvas.create_text(20, 630, text="-300", fill='white')
    canvas.create_text(10, 310, text="0", fill='white')
    canvas.create_text(1030, 310, text="1000", fill='white')

    # Переменные
    lblW = Label(root, text="Циклическая частота:")
    lblW.place(x=0, y=10)
    lblPhi = Label(root, text="Смещение графика по X:")
    lblPhi.place(x=0, y=30)
    lblA = Label(root, text="Амплитуда:")
    lblA.place(x=0, y=50)
    lblDy = Label(root, text="Смещение графика по Y:")
    lblDy.place(x=0, y=70)

    entryW = Entry(root)
    entryW.place(x=150, y=10)
    entryW.insert(INSERT, '0.0209')
    entryPhi = Entry(root)
    entryPhi.place(x=150, y=30)
    entryPhi.insert(INSERT, '20')
    entryA = Entry(root)
    entryA.place(x=150, y=50)
    entryA.insert(INSERT, '200')
    entryDy = Entry(root)
    entryDy.place(x=150, y=70)
    entryDy.insert(INSERT, '320')
    
    # Кнопка для построения
    btn1 = Button(root, text="Построить")
    btn1.bind("<Button-1>", lambda event: sinus(canvas, float(entryW.get()), float(entryPhi.get()), float(entryA.get()), float(entryDy.get())))
    btn1.place(x=10, y=100)

    btn1 = Button(root, text="Очистить (ПКМ)")
    btn1.bind("<Button-3>", lambda event: clean(canvas))
    btn1.place(x=100, y=100)
    
    root.mainloop()

# Расчёт графика  и построение
def sinus(canvas, w, phi, a, dy):
    global sin_line
    sin_line = 0
    xy = []
    for x in range(1000):
        y = math.sin(x * w)
        xy.append(10 + x + phi)
        xy.append(y * a + dy)

    # Отрисовка графкиа
    sin_line = canvas.create_line(xy, fill='red')

# Очистка графика
def clean(canvas):
    canvas.delete(sin_line)

if __name__ == '__main__':
    main()
