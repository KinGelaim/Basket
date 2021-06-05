#ifndef USERSDISMISSWINDOW_H
#define USERSDISMISSWINDOW_H

#include <QDialog>
#include "database.h"

#include <QStandardItemModel>

#include "userswindow.h"

namespace Ui {
class UsersDismissWindow;
}

class UsersDismissWindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit UsersDismissWindow(QWidget *parent = 0);
    ~UsersDismissWindow();

    void recieveData(QList<Sector> sectorsList, QList<Position> positionsList);
    
private slots:
    void on_pushButton_clicked();

    void on_tableView_doubleClicked(const QModelIndex &index);

private:
    Ui::UsersDismissWindow *ui;

    DataBase *db;

    QList<User> usersList;
    QList<Sector> sectorsList;
    QList<Position> positionsList;

    void initData();
};

#endif // USERSDISMISSWINDOW_H
