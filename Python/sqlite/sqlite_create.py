# -*- coding: cp1251 -*-
import sqlite3

conn = sqlite3.connect('example.db')
print "������������"

c = conn.cursor()
print "������� ������"

c.execute('''CREATE TABLE `t1` (`id` INT NOT NULL,`val` VARCHAR(45) NULL,PRIMARY KEY (`id`));''')
print "������� ������ �� �������� �������"

conn.commit()
print "��������� ������ � ��"

conn.close()
print "����������� �������"
