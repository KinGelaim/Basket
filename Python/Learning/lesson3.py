# -*- coding: utf-8 -*-

# Списки и кортежи в Python
a = [1, 2, 3, '4', 5, True, (30,40), [50, 60]]
b = a
b[0] = -1
print(a)

x = 10
x = int('AB', base = 16)
print(x)

c = list(a)
c[1] = 8
print(a) 
print(c)


d = a.copy()
d[2] = 10
print(a)
print(d)

g = (1, 2, 3)
#g[0] = -1
print(g)

a.append(-19)
print(a)

a.append(a)
print(a)

x = 2
y = x
print(x is y)
y = 10
print(x)
print(y)
print(x is y)

a = 2
b = 2
print(a is b)

a = list(range(1,22,1))
b = [x for x in a if x % 7 == 0]   # list complectaion (генератор списков)
print(a)
print(b)

b = list()
for i in range(len(a)):
    if a[i] % 7 == 0:
        b.append(a[i])
print(a)
print(b)

b = list()
for x in a:
    if x % 7 == 0:
        b.append(x)
print(a)
print(b)

# next
from graph import *

def triangle(x, y, c):
    brushColor(c)
    polygon([(x, y), (x, y - 60),
             (x + 100, y), (x,y)])

penColor("black")
triangle(100, 100, "blue")
triangle(200, 100, "green")
triangle(200, 160, "red")

# next
x = 40
y = 40
for i in range(5):
    x += i * 60
    for j in range(3):
        y += j * 40
        circle(x, y, 20)

# next
from random import randint, choice

colors = ["red", "green", "blue", "black", "#FFFF00"]
def newPoint():
  x = randint(0, 150)
  y = randint(0, 150)
  if randint(0,1) == 0:
    r = randint(0, 255)
    g = randint(0, 255)
    b = randint(0, 255)
    penColor(r, g, b)
  else:  
    col = choice(colors)
    penColor( col )
  point(x, y)
 
def keyPressed(event):
  if event.keycode == VK_ESCAPE:
    close()   

onKey(keyPressed)
onTimer(newPoint, 10)

run()
