# -*- coding: utf-8 -*-

# Вывод на консоль
s = "Hello World!"
print(s)

# Считывание с консоли
name = input('What is your name?\n')
print('Hi, %s.' % name)

# Ариф действия
a = 10
b = 20.2
c = a * b
print(c)
c = a - b
print(c)
c = b / a
print(c)
c = 2 ** 3
print(c)
print(17 / 3)
print(17 // 3)
print(17 % 3)

def fib(n):
    a, b = 0, 1
    while a < n:
        print(a, end=' ')
        a, b = b, a+b
    print()

fib(1000)

# bool
status = True
