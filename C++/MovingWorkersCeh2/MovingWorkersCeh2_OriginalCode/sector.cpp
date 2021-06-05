#include "sector.h"

Sector::Sector()
{

}

Sector::Sector(QString name)
{
    this->name = name;
}

Sector::Sector(int id, QString name)
{
    this->id = id;
    this->name = name;
}

void Sector::SetID(int id)
{
    this->id = id;
}

void Sector::SetName(QString name)
{
    this->name = name;
}

int Sector::GetID()
{
    return this->id;
}


QString Sector::GetName()
{
    return this->name;
}
