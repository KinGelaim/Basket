# -*- coding: cp1251 -*-

import sqlite3
import time

conn = sqlite3.connect('example.db')
print("Создано соединение")

c = conn.cursor()
print("Создан курсор")

for rec in c.execute('''SELECT id, val FROM t1;'''):
	print(rec[0])
	print(rec[1])
	print('')
	

conn.close()
print("Соединение закрыто")

time.sleep(10)
