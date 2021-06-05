# -*- coding: utf-8 -*-

import os
import json

def main():
    # Строка JSON в объект JSON
    x = '{"name":"Misha","age":7, "city":"Tagil"}'
    y = json.loads(x)
    print(y['age'])

    # Словарь в JSON объект
    x = {
        "name": "Миша",
        "age" : 7,
        "city": "Tagil"
        }
    y = json.dumps(x)
    print(y)
    y = json.dumps(x, ensure_ascii=False)
    print(y)
    
if __name__ == '__main__':
    main()
