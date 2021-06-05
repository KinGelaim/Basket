# -*- coding: cp1251 -*-
import sqlite3

conn = sqlite3.connect('example.db')
print "Подключились"

c = conn.cursor()
print "Создали курсор"

c.execute('''CREATE TABLE `t1` (`id` INT NOT NULL,`val` VARCHAR(45) NULL,PRIMARY KEY (`id`));''')
print "Создали запрос на создание таблицы"

conn.commit()
print "Отправлен запрос в БД"

conn.close()
print "Подключение закрыто"
