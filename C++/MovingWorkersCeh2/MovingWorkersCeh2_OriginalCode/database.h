#ifndef DATABASE_H
#define DATABASE_H

#include <QObject>
#include <QSql>
#include <QSqlQuery>
#include <QSqlError>
#include <QSqlDatabase>
#include <QSqlTableModel>
#include <QFile>
#include <QDate>
#include <QDebug>

#include <sys/types.h>
#include <sys/stat.h>

#include <QList>

#include "sector.h"
#include "position.h"
#include "user.h"

/* Директивы имен таблицы, полей таблицы и базы данных */
#define DATABASE_HOSTNAME   "MovingWorkersSecondCehDataBase"
#define DATABASE_NAME       "SecondCehDataBase.db"

class DataBase : public QObject
{
    Q_OBJECT
public:
    explicit DataBase(QObject *parent = 0);
    ~DataBase();
    /* Методы для непосредственной работы с классом
     * Подключение к базе данных и вставка записей в таблицу
     * */
    void connectToDataBase(QString path);

	bool insertIntoSectorsTable(const QVariantList &data);
	bool insertIntoPositionsTable(const QVariantList &data);
    bool insertIntoUsersTable(const QVariantList &data);

    QList<Sector> getDataFromSectors();
    QList<Position> getDataFromPositions();
    QList<User> getDataFromUsers(bool dismiss=false);
    QList<User> getDataFromUsers(QString idPosition);

    bool updateSectorsTable(Sector sector);
    bool updatePositionsTable(Position position);
    bool updateUsersTable(User user);
	
    bool deleteFromSectorsTable(int id);
    bool deleteFromPositionsTable(int id);
    bool deleteFromUsersTable(int id);

private:
    // Сам объект базы данных
    QSqlDatabase    db;
    // Модель представления таблицы базы данных
    //QSqlTableModel  *model;

private:
    // Внутренние методы для работы с базой данных
    bool openDataBase(QString path);
    bool restoreDataBase(QString path);
    void closeDataBase();
    bool createTableUsers();
    bool createTablePositions();
    bool createTableSectors();
};

#endif // DATABASE_H
