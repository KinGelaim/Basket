# -*- coding: utf-8 -*-

x = int(input("Введите x: "))
y = 4

if x > 0:
    print("x > 0")
elif y > 0:
    print("y > 0 and x < 0")
else:
    print("x < 0 and y < 0")

while x > 0:
    print(x)
    if x <= 3:
        break
    x -= 2
else: # У вайла есть ELSE!!! И у FOR!!!
    print('x <= 0')


print('For: ')
for x in 1,2,3,4: #любой итерируемый объект
    print(x)
for x in range(-2, 10, 2): #range - генератор арифм. прогресии (старт,стоп,шаг)
    print(x)

a = range(1,10)
print(a)
print(type(a))

print(a[4])

try:
    print('Begin')
    throw('Error')
    print('End')
except:
    print('Error')


def myfunc(x, y):
    return x*y

print(myfunc(2,3))


def p3(x):
    print(x)
    print(x)
    print(x)

x = p3('Hello')
print(x)
print(type(x))


def f(x:int,y:'int > 0')->int: #Что-то типо комментария, компиль не проверяет!
    "Складывает x и y - это документ строка"
    return x+y
print(f(1,2))
print(f('a','v'))
