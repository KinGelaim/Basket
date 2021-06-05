# -*- coding: utf-8 -*-

#from lesson6_module import *
#from lesson6_module import foo
#import lesson6_module
import lesson6_module as library

def printer():
    print(x)

def modifier():
    global x
    x += 10
    print(x)

print('Main module')
library.foo(3)
library.foo(4)

#printer() #error - x is not defined
x = library.bar(1, 6)
print(x)

library.create_object("Круг1")
library.create_object("Круг2")
library.create_object("Круг3")
library.print_objects()

for obj in library.objects:
    print(obj)

printer()
modifier()
