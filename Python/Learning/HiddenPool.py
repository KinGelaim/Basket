# -*- coding: utf-8 -*-

x = 10
y = 10
z = y

print(x, ' ', y, ' ', z)
print('type x -', type(x))
print('type y -', type(y))
print('type z -', type(z))
input()

print('hex_id y -', hex(id(y)))
print('hex_id z -', hex(id(z)))
print()
input()

y += 174
print('y =', y)
print('z =', z)
print()
input()

print('hex_id y -', hex(id(y)))
print('hex_id z -', hex(id(z)))
print()
input()

y = 10
d = 10
print('hex_id x -', hex(id(x)))
print('hex_id y -', hex(id(y)))
print('hex_id z -', hex(id(z)))
print('hex_id d -', hex(id(d)))
print()
