from dbf import base, fields
from datetime import datetime

db=base.DBF(open('1.dbf', 'rb'), fieldspecs=None)

for rec in db:
    print(rec)
    print(rec['pk'])
    print(rec['fid'])
    print(rec['fstr'])
    print(rec['fint'])
    print(rec['fdec'])
    print(rec['fbool'])
    print(rec['fdate'])
    print(rec['fe'])	
	
	
    print('')
