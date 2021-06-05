#ifndef USER_H
#define USER_H

#include <QString>

class User
{
public:
    User();
    User(int id, QString surname, QString name, QString patronymic,
         int idSector, int idPosition, QString dateIncoming, QString dateDismiss);

    void SetID(int id);
    void SetSurname(QString surname);
    void SetName(QString name);
    void SetPatronymic(QString patronymic);
    void SetSectorID(int idSector);
    void SetNameSector(QString nameSector);
    void SetPositionID(int idPosition);
    void SetNamePosition(QString namePosition);
    void SetDateIncoming(QString dateIncoming);
    void SetDateDismiss(QString dateDismiss);

    int GetID();
    QString GetSurname();
    QString GetName();
    QString GetPatronymic();
    int GetSectorID();
    QString GetNameSector();
    int GetPositionID();
    QString GetNamePosition();
    QString GetDateIncoming();
    QString GetDateDismiss();

private:
    int id;
    QString surname;
    QString name;
    QString patronymic;
    int idSector;
    QString nameSector;
    int idPosition;
    QString namePosition;
    QString dateIncoming;
    QString dateDismiss;
};

#endif // USER_H
