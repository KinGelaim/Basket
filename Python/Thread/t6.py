# -*- coding: cp1251 -*-
# Блокировки
import threading, time
res = {'A': threading.Lock(), 'B': threading.Lock()}

def proc(n, rs):
    for r in rs:
        print u"Процесс", n, u"обращается к ресурсу", r
        #запрос на запирание замка
        res[r].acquire()
        print u"Процесс", n, u"запер ресурс", r
        time.sleep(4)
        print u"Процесс", n, u"продолжается"

    for r in rs:
        #запрос на отпирание замка
        res[r].release()
        print u"Процесс", n, u"завершен"
    
p1 = threading.Thread(target=proc, args=[1, "AB"])
p1.start()

time.sleep(2)

p2 = threading.Thread(target=proc, args=[2, "BA"])
p2.start()
