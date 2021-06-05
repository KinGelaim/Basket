# -*- coding: cp1251 -*-
# ����������
import threading, time
res = {'A': threading.Lock(), 'B': threading.Lock()}

def proc(n, rs):
    for r in rs:
        print u"�������", n, u"���������� � �������", r
        #������ �� ��������� �����
        res[r].acquire()
        print u"�������", n, u"����� ������", r
        time.sleep(4)
        print u"�������", n, u"������������"

    for r in rs:
        #������ �� ��������� �����
        res[r].release()
        print u"�������", n, u"��������"
    
p1 = threading.Thread(target=proc, args=[1, "AB"])
p1.start()

time.sleep(2)

p2 = threading.Thread(target=proc, args=[2, "BA"])
p2.start()
