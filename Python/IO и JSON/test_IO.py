# -*- coding: utf-8 -*-

import os

def main():
    print("Приказы:")
    loadFile()
    print("Новый приказ:")
    newPrikaz = input()
    newInFile(newPrikaz)

def loadFile():
    f = open('all.txt', 'r')
    allText = f.read()
    f.close()
    print(allText)
    
def newInFile(newPrikaz):
    f = open('all.txt', 'a')
    f.write('\n' + newPrikaz)
    f.close()
    
if __name__ == '__main__':
    main()
