# -*- coding: utf-8 -*-

objects = []

def foo(x):
    print('foo', x)

def bar(a, b):
    return a + b

def create_object(name):
    objects.append("object[" + name + "]")

def print_objects():
    print("All objects:")
    for obj in objects:
        print(obj)

print('Module imported or just executed')

if __name__ == '__main__':
    print('Module executed')
    print('Test start:')
    if bar(2, 2) == 4:
        print('Ok')
    else:
        print('Fail')
