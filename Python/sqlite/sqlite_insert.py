# -*- coding: cp1251 -*-

import sqlite3

conn = sqlite3.connect('example.db')
print "������� �����������"

c = conn.cursor()
print "������ ������"

c.execute('''INSERT INTO `t1` (`id`, `val`) VALUES ('1', 'qwerty');''')
c.execute('''INSERT INTO `t1` (`id`, `val`) VALUES ('2', 'asd');''')
print "������� ��� �������"

conn.commit()
print "��������� ������"

conn.close()
print "���������� � �� �������"

