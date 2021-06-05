import sqlite3

dbFile=u'ADDROBJ.db'
conn = sqlite3.connect(dbFile)

def GetChildren(node):
	
	children={}
	c = conn.cursor()
	for rec in c.execute("select AOGUID, AOLEVEL, FORMALNAME, SHORTNAME from addrobj where parentguid='%s'" % node):
		AOGUID=rec[0]
		AOLEVEL=rec[1]		
		FORMALNAME=rec[2]
		SHORTNAME=rec[3]
		print AOGUID, AOLEVEL, FORMALNAME, SHORTNAME
		children1=GetChildren(AOGUID)
		children[AOGUID]={'AOGUID': AOGUID, 'AOLEVEL': AOLEVEL, 'FORMALNAME': FORMALNAME, 'SHORTNAME': SHORTNAME, 'children': children1}
	return children

def NodeToXml(node):
	s=u''
	if node:	
		s+=u'<node AOGUID="%s" AOLEVEL="%s" FORMALNAME="%s" SHORTNAME="%s">' % (node['AOGUID'], node['AOLEVEL'], node['FORMALNAME'], node['SHORTNAME'])
		for subnode in node['children'].values():
			s1=NodeToXml(subnode)		
			s+=s1
		s+=u'</node>'
	return s
	
	
parent='cc73d6af-6e2e-4a1f-be8e-682c289b0b57'
#parent='987298a0-2d3d-42e5-92b9-5ff689bcbf72'
OKTMO='65751000'

nodes={}
c = conn.cursor()
query="select AOGUID, AOLEVEL, FORMALNAME, SHORTNAME from addrobj where AOGUID='%s'" % parent
query='''SELECT AOGUID, AOLEVEL, FORMALNAME, SHORTNAME, parentguid, oktmo
FROM [ADDROBJ]
where actstatus=1 and substr(oktmo, 1, 8)='65751000' and actstatus=1  and (parentguid='498a36b2-3311-4cbe-993e-eb706d25f8bd' or parentguid='92b30014-4d52-4e2e-892d-928142b924bf')'''

#query='''SELECT AOGUID, AOLEVEL, FORMALNAME, SHORTNAME, parentguid, oktmo
#FROM [ADDROBJ]
#where actstatus=1 and substr(oktmo, 1, 8)='65751000' and actstatus=1  and (parentguid='498a36b2-3311-4cbe-993e-eb706d25f8bd' or parentguid='2b30014-4d52-4e2e-892d-928142b924bf')'''


for rec in c.execute(query):
	AOGUID=rec[0]
	AOLEVEL=rec[1]	
	FORMALNAME=rec[2]
	SHORTNAME=rec[3]
	print AOGUID, AOLEVEL, FORMALNAME, SHORTNAME
	children1=GetChildren(AOGUID)
	nodes[AOGUID]={'AOGUID': AOGUID, 'AOLEVEL': AOLEVEL, 'FORMALNAME': FORMALNAME, 'SHORTNAME': SHORTNAME, 'children': children1}
	
#print nodes
s=u''
if nodes:	
	s+=u'<nodes>'
	for subnode in nodes.values():
		s1=NodeToXml(subnode)
		s+=s1
	s+=u'</nodes>'
print s

f=open('1.xml', 'w')
f.write(s.encode('utf8'))
f.close()

conn.close()

