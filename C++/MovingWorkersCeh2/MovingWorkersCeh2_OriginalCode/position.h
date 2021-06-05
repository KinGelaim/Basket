#ifndef POSITION_H
#define POSITION_H

#include <QString>

class Position
{
public:
    Position();
    Position(int id, QString name, int idSector);

    void SetID(int id);
    void SetName(QString name);
    void SetSectorID(int idSector);
	void SetNameSector(QString nameSector);

    int GetID();
    QString GetName();
    int GetSectorID();
	QString GetNameSector();

private:
    int id;
    QString name;
    int idSector;
	QString nameSector;
};

#endif // POSITION_H
