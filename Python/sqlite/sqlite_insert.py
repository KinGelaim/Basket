# -*- coding: cp1251 -*-

import sqlite3

conn = sqlite3.connect('example.db')
print "Создано подключение"

c = conn.cursor()
print "Создан курсор"

c.execute('''INSERT INTO `t1` (`id`, `val`) VALUES ('1', 'qwerty');''')
c.execute('''INSERT INTO `t1` (`id`, `val`) VALUES ('2', 'asd');''')
print "Созданы два запроса"

conn.commit()
print "Отправлен запрос"

conn.close()
print "Соединение с БД закрыто"

