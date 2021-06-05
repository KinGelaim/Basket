#include "position.h"

Position::Position()
{
}

Position::Position(int id, QString name, int idSector)
{
    this->id = id;
    this->name = name;
    this->idSector = idSector;
}

void Position::SetID(int id)
{
    this->id = id;
}

void Position::SetName(QString name)
{
    this->name = name;
}

void Position::SetSectorID(int idSector)
{
    this->idSector = idSector;
}

void Position::SetNameSector(QString nameSector)
{
	this->nameSector = nameSector;
}

int Position::GetID()
{
    return id;
}

QString Position::GetName()
{
    return name;
}

int Position::GetSectorID()
{
    return idSector;
}

QString Position::GetNameSector()
{
	return nameSector;
}