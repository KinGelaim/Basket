# -*- coding: utf-8 -*-

# Проблема композиции (интеграции (взаимодействие между частями кода))
# Решается:
# 1) Компиляцией (строгая проверка типов) - не про Python
# 2) Использование контрактов:
# - предусловия
# - постусловия
# - инварианты

x = int(input())
while x > 0:
    digit = x % 10
    print(digit)
    x = x // 10

assert x == 0 # Выбрасывает Exception, если x != 0
assert x > -10, 'Пользователь ввёл число меньше -10...'

print('End')

from contracts import contract

@contract(x='int,>=0')
def print_number_digit_by_digit(x):
    while x > 0:
        digit = x % 10
        print(digit)
        x = x // 10

@contract(returns='int,>=0')
def foo(x):
    return x

@contract
def f(x):
    """Function descrtiption.
       :type x: int,>0
       :rtype: <=1
    """
    pass

@contract
def bar(x: 'int,>=0|<-10') -> '>=1':
    pass

@contract
def b(x: int) -> int:
    pass

x = int(input("Введите x >= 0:"))
print_number_digit_by_digit(x)

print(foo(5))
print(foo('asd'))

print('All End')
