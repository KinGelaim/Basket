#ifndef USERSWINDOW_H
#define USERSWINDOW_H

#include <QMessageBox>

#include <QDialog>
#include "database.h"

namespace Ui {
class UsersWindow;
}

class UsersWindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit UsersWindow(QWidget *parent = 0);
    ~UsersWindow();

    void recieveData(User user, QList<Sector> sectorsList, QList<Position> positionsList);
    void recieveData(QList<Sector> sectorsList, QList<Position> positionsList);
    
private slots:
    void on_pushButton_clicked();

    void on_comboBox_currentIndexChanged(int index);

    void on_pushButton_2_clicked();

    void on_checkBox_toggled(bool checked);

private:
    Ui::UsersWindow *ui;

    DataBase *db;

    bool isUpdate;
	User user;
    QList<Sector> sectorsList;
    QList<Position> positionsList;

    void initData();
};

#endif // USERSWINDOW_H
