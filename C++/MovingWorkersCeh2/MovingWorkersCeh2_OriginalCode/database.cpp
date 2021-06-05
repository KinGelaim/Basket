#include "database.h"

DataBase::DataBase(QObject *parent) : QObject(parent)
{

}

DataBase::~DataBase()
{

}

/* Методы для подключения к базе данных
 * */
void DataBase::connectToDataBase(QString path)
{
    /* Перед подключением к базе данных производим проверку на её существование.
     * В зависимости от результата производим открытие базы данных или её восстановление
     * */
    if(!QFile(path+DATABASE_NAME).exists()){
        this->restoreDataBase(path);
    } else {
        this->openDataBase(path);
    }
}

/* Методы восстановления базы данных
 * */
bool DataBase::restoreDataBase(QString path)
{
    if(this->openDataBase(path)){
        if(!this->createTableUsers()){
            return false;
        } else if(!this->createTablePositions()) {
            return false;
        } else if(!this->createTableSectors()) {
            return false;
        } else {
            return true;
        }
        //QFile::setPermissions(path, QFile::ReadGroup | QFile::ReadOther | QFile::ReadOwner | QFile::ReadUser |
          //                    QFile::WriteGroup | QFile::WriteOther | QFile::WriteOwner | QFile::WriteUser |
            //                  QFile::ExeGroup | QFile::ExeOther | QFile::ExeOwner | QFile::ExeUser);
        QByteArray ba = path.toLocal8Bit();
        const char *c_str = ba.data();
        chmod(c_str, S_IRUSR | S_IWUSR | S_IRWXU | S_IWGRP | S_IROTH);
    } else {
        qDebug() << "Не удалось восстановить базу данных";
        return false;
    }
    return false;
}

/* Метод для открытия базы данных
 * */
bool DataBase::openDataBase(QString path)
{
    /* База данных открывается по заданному пути
     * и имени базы данных, если она существует
     * */
    db = QSqlDatabase::addDatabase("QSQLITE");
    db.setHostName(DATABASE_HOSTNAME);
    db.setDatabaseName(path+DATABASE_NAME);
    if(db.open()){
        return true;
    } else {
        return false;
    }
}

/* Методы закрытия базы данных
 * */
void DataBase::closeDataBase()
{
    db.close();
}


/* Методы для создания таблицы в базе данных
 * */
bool DataBase::createTableUsers()
{
    /* В данном случае используется формирование сырого SQL-запроса
     * с последующим его выполнением.
     * */
    QSqlQuery query;
    if(!query.exec( "CREATE TABLE users ("
                            "id INTEGER PRIMARY KEY AUTOINCREMENT, "
                            "surname VARCHAR(255) NOT NULL, "
                            "name VARCHAR(255) NOT NULL, "
                            "patronymic VARCHAR(255) NOT NULL, "
                            "id_position INTEGER NOT NULL, "
                            "date_incoming DATE NOT NULL, "
                            "date_dismiss DATE NULL"
                        " )"
                    )){
        qDebug() << "DataBase: error of create " << "Users";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::createTablePositions()
{
    QSqlQuery query;
    if(!query.exec( "CREATE TABLE positions ("
                            "id INTEGER PRIMARY KEY AUTOINCREMENT, "
                            "name VARCHAR(255) NOT NULL, "
                            "id_sector INTEGER NOT NULL"
                        " )"
                    )){
        qDebug() << "DataBase: error of create " << "Positions";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::createTableSectors()
{
    QSqlQuery query;
    if(!query.exec( "CREATE TABLE sectors ("
                            "id INTEGER PRIMARY KEY AUTOINCREMENT, "
                            "name VARCHAR(255) NOT NULL"
                        " )"
                    )){
        qDebug() << "DataBase: error of create " << "Sectors";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

/* Метод для вставки записи в базу данных
 * */
bool DataBase::insertIntoSectorsTable(const QVariantList &data)
{
    /* Запрос SQL формируется из QVariantList,
     * в который передаются данные для вставки в таблицу.
     * */
    QSqlQuery query;
    /* В начале SQL запрос формируется с ключами,
     * которые потом связываются методом bindValue
     * для подстановки данных из QVariantList
     * */
    query.prepare("INSERT INTO sectors (name) "
                  "VALUES (:Name)");
    query.bindValue(":Name",      data[0].toString());
    // После чего выполняется запросом методом exec()
    if(!query.exec()){
        qDebug() << "error insert into " << "Sectors";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::insertIntoPositionsTable(const QVariantList &data)
{
    /* Запрос SQL формируется из QVariantList,
     * в который передаются данные для вставки в таблицу.
     * */
    QSqlQuery query;
    /* В начале SQL запрос формируется с ключами,
     * которые потом связываются методом bindValue
     * для подстановки данных из QVariantList
     * */
    query.prepare("INSERT INTO positions (name,id_sector) "
                  "VALUES (:Name, :idSector)");
    query.bindValue(":Name",      data[0].toString());
    query.bindValue(":idSector",  data[1].toInt());
    // После чего выполняется запросом методом exec()
    if(!query.exec()){
        qDebug() << "error insert into " << "Positions";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::insertIntoUsersTable(const QVariantList &data)
{
    /* Запрос SQL формируется из QVariantList,
     * в который передаются данные для вставки в таблицу.
     * */
    QSqlQuery query;
    /* В начале SQL запрос формируется с ключами,
     * которые потом связываются методом bindValue
     * для подстановки данных из QVariantList
     * */
    query.prepare("INSERT INTO users (surname,name,patronymic,id_position,date_incoming,date_dismiss) "
                  "VALUES (:Surname, :Name, :Patronymic, :idPosition, :DateIncoming, :DateDismiss )");
    query.bindValue(":Surname",     data[0].toString());
    query.bindValue(":Name",        data[1].toString());
    query.bindValue(":Patronymic",  data[2].toString());
    query.bindValue(":idPosition",  data[3].toInt());
    query.bindValue(":DateIncoming",data[4].toDate());
    query.bindValue(":DateDismiss", data[5].toDate());

    // После чего выполняется запросом методом exec()
    if(!query.exec()){
        qDebug() << "error insert into " << "Users";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

QList<Sector> DataBase::getDataFromSectors()
{
    QSqlQuery query;
    query.prepare("SELECT "
                  "\"id\" ,"
                  "\"name\" "
                  " FROM "
                  "sectors"
                  ";");
    QList<Sector> sectorList;
    if (!query.exec())
    {
        qDebug() << "Ошибка при получении данных из sectors";
        qDebug() << query.lastError();
    }
    else
    {
        while (query.next())
        {
            int id = query.value(0).toInt();
            QString name = query.value(1).toString();
            Sector newSector(id, name);
            sectorList.append(newSector);
        }
    }
    return sectorList;
}

QList<Position> DataBase::getDataFromPositions()
{
    QSqlQuery query;
    query.prepare("SELECT "
                  "positions.id, "
                  "positions.name, "
                  "positions.id_sector, "
                  "sectors.name as nameSector "
                  "FROM "
                  "positions "
				  "JOIN sectors ON positions.id_sector=sectors.id"
                  ";");
    QList<Position> positionsList;
    if (!query.exec())
    {
        qDebug() << "Ошибка при получении данных из positions";
        qDebug() << query.lastError();
    }
    else
    {
        while (query.next())
        {
            int id = query.value(0).toInt();
            QString name = query.value(1).toString();
            int idSector = query.value(2).toInt();
            Position newPosition(id, name, idSector);
			newPosition.SetNameSector(query.value(3).toString());
            positionsList.append(newPosition);
        }
    }
    return positionsList;
}

QList<User> DataBase::getDataFromUsers(bool dismiss)
{
    QString equal = "=";
    if(dismiss)
        equal = "!=";
    QSqlQuery query;
    query.prepare("SELECT "
                  "users.id, "
                  "users.surname, "
                  "users.name, "
                  "users.patronymic, "
                  "users.id_position, "
                  "positions.name as positionName, "
                  "positions.id_sector, "
                  "sectors.name as sectorName, "
                  "users.date_incoming, "
                  "users.date_dismiss "
                  "FROM "
                  "users "
                  "JOIN positions ON users.id_position=positions.id "
                  "JOIN sectors ON positions.id_sector=sectors.id "
                  "WHERE users.date_dismiss"+equal+"\"2000-01-01\""
                  ";");
    QList<User> usersList;
    if (!query.exec())
    {
        qDebug() << "Ошибка при получении данных из users";
        qDebug() << query.lastError();
    }
    else
    {
        while (query.next())
        {
            int id = query.value(0).toInt();
            QString surname = query.value(1).toString();
            QString name = query.value(2).toString();
            QString patronymic = query.value(3).toString();
            int idPosition = query.value(4).toInt();
            QString namePosition = query.value(5).toString();
            int idSector = query.value(6).toInt();
            QString nameSector = query.value(7).toString();
            QString dateIncoming = query.value(8).toString();
            QString dateDismiss = query.value(9).toString();
            User newUser(id, surname, name, patronymic,
                         idSector, idPosition, dateIncoming, dateDismiss);
            newUser.SetNameSector(nameSector);
            newUser.SetNamePosition(namePosition);
            usersList.append(newUser);
        }
    }
    return usersList;
}

QList<User> DataBase::getDataFromUsers(QString idPosition)
{
    QSqlQuery query;
    query.prepare("SELECT "
                  "users.id, "
                  "users.surname, "
                  "users.name, "
                  "users.patronymic, "
                  "users.id_position, "
                  "positions.name as positionName, "
                  "positions.id_sector, "
                  "sectors.name as sectorName, "
                  "users.date_incoming, "
                  "users.date_dismiss "
                  "FROM "
                  "users "
                  "JOIN positions ON users.id_position=positions.id "
                  "JOIN sectors ON positions.id_sector=sectors.id "
                  "WHERE users.id_position=:idPosition"
                  ";");
    query.bindValue(":idPosition", idPosition);
    QList<User> usersList;
    if (!query.exec())
    {
        qDebug() << "Ошибка при получении данных из users where positions";
        qDebug() << query.lastError();
    }
    else
    {
        while (query.next())
        {
            int id = query.value(0).toInt();
            QString surname = query.value(1).toString();
            QString name = query.value(2).toString();
            QString patronymic = query.value(3).toString();
            int idPosition = query.value(4).toInt();
            QString namePosition = query.value(5).toString();
            int idSector = query.value(6).toInt();
            QString nameSector = query.value(7).toString();
            QString dateIncoming = query.value(8).toString();
            QString dateDismiss = query.value(9).toString();
            User newUser(id, surname, name, patronymic,
                         idSector, idPosition, dateIncoming, dateDismiss);
            newUser.SetNameSector(nameSector);
            newUser.SetNamePosition(namePosition);
            usersList.append(newUser);
        }
    }
    return usersList;
}

bool DataBase::updateSectorsTable(Sector sector)
{
    QSqlQuery query;

    query.prepare("UPDATE sectors SET name=:Name WHERE id=:ID");
	
    query.bindValue(":ID",   sector.GetID());
    query.bindValue(":Name", sector.GetName());

    if(!query.exec()){
        qDebug() << "error update " << "Sectors";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::updatePositionsTable(Position position)
{
    QSqlQuery query;

    query.prepare("UPDATE positions SET name=:Name, id_sector=:idSector WHERE id=:ID");
	
    query.bindValue(":ID",       position.GetID());
    query.bindValue(":Name",     position.GetName());
    query.bindValue(":idSector", position.GetSectorID());

    if(!query.exec()){
        qDebug() << "error update " << "Positions";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::updateUsersTable(User user)
{
    QSqlQuery query;

    query.prepare("UPDATE users SET surname=:Surname, name=:Name, patronymic=:Patronymic, id_position=:idPosition, date_incoming=:DateIncoming, date_dismiss=:DateDismiss WHERE id=:ID");
	
    query.bindValue(":ID",           user.GetID());
    query.bindValue(":Surname",      user.GetSurname());
    query.bindValue(":Name",         user.GetName());
    query.bindValue(":Patronymic",   user.GetPatronymic());
    query.bindValue(":idPosition",   user.GetPositionID());
    query.bindValue(":DateIncoming", user.GetDateIncoming());
    query.bindValue(":DateDismiss",  user.GetDateDismiss());

    if(!query.exec()){
        qDebug() << "error update " << "Users";
        qDebug() << query.lastError().text();
        return false;
    } else {
        return true;
    }
    return false;
}

bool DataBase::deleteFromSectorsTable(int id)
{
    /*
    QSqlQuery query;

    query.prepare("DELETE u FROM users u JOIN positions p ON users.id_position=positions.id WHERE id_sector=:idSector");
    query.bindValue(":idSector", id);

    if(!query.exec())
    {
        qDebug() << "error delete from Users where idSector";
        qDebug() << query.lastError().text();
        return false;
    }

    query.prepare("DELETE FROM positions WHERE id_sector=:idSector");
    query.bindValue(":idSector", id);

    if(!query.exec())
    {
        qDebug() << "error delete from Positions where idSector";
        qDebug() << query.lastError().text();
        return false;
    }
    */

    QList<Position> positionsList = getDataFromPositions();
    for(int i = 0; i < positionsList.length(); i++)
    {
        if(positionsList[i].GetSectorID() == id)
        {
            deleteFromPositionsTable(positionsList[i].GetID());
        }
    }

    QSqlQuery query;

    query.prepare("DELETE FROM sectors WHERE id=:ID");
    query.bindValue(":ID", id);

    if(!query.exec())
    {
        qDebug() << "error delete from sectors";
        qDebug() << query.lastError().text();
        return false;
    }else{
        return true;
    }

    return false;
}

bool DataBase::deleteFromPositionsTable(int id)
{
    QSqlQuery query;

    query.prepare("DELETE FROM users WHERE id_position=:idPosition");
    query.bindValue(":idPosition", id);

    if(!query.exec())
    {
        qDebug() << "error delete from Users where idPosition";
        qDebug() << query.lastError().text();
        return false;
    }

    query.prepare("DELETE FROM positions WHERE id=:ID");
    query.bindValue(":ID", id);

    if(!query.exec())
    {
        qDebug() << "error delete from positions";
        qDebug() << query.lastError().text();
        return false;
    }else{
        return true;
    }

    return false;
}

bool DataBase::deleteFromUsersTable(int id)
{
    QSqlQuery query;
    query.prepare("DELETE FROM users WHERE id=:ID");
    query.bindValue(":ID", id);

    if(!query.exec())
    {
        qDebug() << "error delete from Users";
        qDebug() << query.lastError().text();
        return false;
    }else{
        return true;
    }

    return false;
}
