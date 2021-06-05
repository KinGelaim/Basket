# -*- coding: utf-8 -*-

a = 2+2
print(a)
print(type(a))

b = 3 * 4
print(b)
print(type(b))

c = 7/2
print(c)
print(type(c))

d = 7//2 #Целочисленное деление
print(d)
print(type(d))

e = 7%2 #Взятие остатка
print(e)
print(type(e))

f = 2**3 #Возведение в степень
print(f)

g = 2**1000 #Длинная арифметика
print(g)
print(type(g))

s = str(2+2)*int('2'+'2')
print(s)
len(s)

#int('abc')

#help(int)

#int, float, str, bool - базовые типы значений

print(bool(12))
print(bool(-1))
print(bool(0))

s = 'Hello,\nWorld!'
print(s)

print(5,10,20, sep=':') #именованный параметр

x = 5
print("%02d:%02d:%02d" % (x,8,20))

name = input()
print('Hello, ' + name)

print(3**0.5) # Корень

print(3**3**3==3**27)

print('Как тебя зовут?')
name = input()
print('Привет, ', name, '!', sep='')
age = int(input('Сколько тебе лет? '))
print('А я думал, тебе', age+4, end='')

x = age + 4
if x >= 11 and x <= 19:
    print(' лет', end='')
else:
    if x % 10 == 1:
        print(' год', end='')
    elif x % 10 >=2 and x % 10 <= 4:
        print(' года', end='')
    else:
        print(' лет', end='')

print('!...')

