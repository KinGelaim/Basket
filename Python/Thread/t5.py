# -*- coding: cp1251 -*-
# Пример работы с потоками
import threading
import time

def proc(n,s):
    time.sleep(s)
    print(u'Поток', str(n), u'завершился')

for x in range(1,4):    
    print(u'Поток', str(x), u'стартовал')
    print(u'Количество активных потоков ',threading.activeCount())
    
    if(x == 1): 
        threading.Thread(target=proc, args=[x,3]).start()
    elif(x == 2):
        threading.Thread(target=proc, args=[x,1]).start()
    elif(x == 3):
        threading.Thread(target=proc, args=[x,2]).start()

time.sleep(10)
