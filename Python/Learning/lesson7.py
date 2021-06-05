# -*- coding: utf-8 -*-

# Классы и объекты в Python

# объект = данные + код
# класс - это тип объекта

class Goat:
    age = 0
    name = ''
    weight = 0.0

    def show(self):
        print(self.name)
        print(self.age)
        print(self.weight)

    def add_age(self, age):
        self.age += age



a = Goat()
a.age = 3
a.age = a.age + 1
a.name = 'Zorka'
a.weight = 3.7
a.show()
print(type(a))
print(a)
print()

b = Goat()
b.age = 1
b.name = 'Nurka'
b.weight = 1.4
b.show()
print(type(b))
print(b)
print()

a.add_age(b.age)
a.show()
print()



class Student():
    def __init__(self, name, age):
        self.name = name
        self.age = age

    def show(self):
        print('Name:', self.name)
        print('Age:', self.age)



a = Student('Vasya', 17)
a.show()
a.tail = True # New attribute for object (NOT CLASS!!!!)
print(a.tail)

b = Student(18, 'Anton')
b.show()
#print(b.tail) # ERROR



print(type(Goat))
print(dir(a))
