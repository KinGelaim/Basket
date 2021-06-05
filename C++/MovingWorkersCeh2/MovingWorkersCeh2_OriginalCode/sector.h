#ifndef SECTOR_H
#define SECTOR_H

#include <QString>

class Sector
{
public:
    Sector();
    Sector(QString name);
    Sector(int id, QString name);

    void SetID(int id);
    void SetName(QString name);

    int GetID();
    QString GetName();

private:
    int id;
    QString name;
};

#endif // SECTOR_H
