#include "user.h"

User::User()
{
}

User::User(int id, QString surname, QString name, QString patronymic,
           int idSector, int idPosition, QString dateIncoming,
           QString dateDismiss)
{
    this->id = id;
    this->surname = surname;
    this->name = name;
    this->patronymic = patronymic;
    this->idSector = idSector;
    this->idPosition = idPosition;
    this->dateIncoming = dateIncoming;
    this->dateDismiss = dateDismiss;
}

void User::SetID(int id)
{
    this->id = id;
}

void User::SetSurname(QString surname)
{
    this->surname = surname;
}

void User::SetName(QString name)
{
    this->name = name;
}

void User::SetPatronymic(QString patronymic)
{
    this->patronymic = patronymic;
}

void User::SetSectorID(int idSector)
{
    this->idSector = idSector;
}

void User::SetNameSector(QString nameSector)
{
    this->nameSector = nameSector;
}

void User::SetPositionID(int idPosition)
{
    this->idPosition = idPosition;
}

void User::SetNamePosition(QString namePosition)
{
    this->namePosition = namePosition;
}

void User::SetDateIncoming(QString dateIncoming)
{
    this->dateIncoming = dateIncoming;
}

void User::SetDateDismiss(QString dateDismiss)
{
    this->dateDismiss = dateDismiss;
}

int User::GetID()
{
    return this->id;
}

QString User::GetSurname()
{
    return this->surname;
}

QString User::GetName()
{
    return this->name;
}

QString User::GetPatronymic()
{
    return this->patronymic;
}

int User::GetSectorID()
{
    return this->idSector;
}

QString User::GetNameSector()
{
    return this->nameSector;
}

int User::GetPositionID()
{
    return this->idPosition;
}

QString User::GetNamePosition()
{
    return this->namePosition;
}

QString User::GetDateIncoming()
{
    return this->dateIncoming;
}

QString User::GetDateDismiss()
{
    return this->dateDismiss;
}
