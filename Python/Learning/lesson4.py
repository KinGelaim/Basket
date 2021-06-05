# -*- coding: utf-8 -*-

from graph import *

# Структурное программирование (отсутствие GOTO!!!!!!!!!!)
# Программа выполняется сверху-вниз
# Присутствует циклы, ветвления
# Программа разделяется на подпрограммы (процедуры или функции или ещё что-то)
# Что позволяет создавать языковая конструкция

def main():
    windowSize(600, 600)
    canvasSize(600,600)
    paint_house(100, 100, 50, 50)
    run()

def paint_house(x, y, width, height):
    """
    Function for draw house
    (x,y) - left bottom point of roof
    """
    #pass
    #return
    paint_walls(x, y, width, height // 2)
    paint_roof(x, y, width, height // 2)
    w_height = height // 6
    w_width = width // 3
    paint_window(x + w_width, y + w_height, w_width, w_height)

def paint_walls(x, y, width, height):
    rectangle(x, y, x + width, y + height)

def paint_roof(x, y, width, height):
    polygon([(x, y), (x + width, y), (x + width / 2, y - height)])

def paint_window(x, y, width, height):
    rectangle(x, y, x + width, y + height)



if __name__ == "__main__":
    main()
