#include "usersdismisswindow.h"
#include "ui_usersdismisswindow.h"

UsersDismissWindow::UsersDismissWindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::UsersDismissWindow)
{
    ui->setupUi(this);
}

UsersDismissWindow::~UsersDismissWindow()
{
    delete ui;
}

void UsersDismissWindow::recieveData(QList<Sector> sectorsList,
                              QList<Position> positionsList)
{
    this->sectorsList = sectorsList;
    this->positionsList = positionsList;
    initData();
}

void UsersDismissWindow::initData()
{
    this->usersList = db->getDataFromUsers(true);
    QStandardItemModel* model = new QStandardItemModel(0, 5);

    for(int i=0; i < usersList.length(); i++ )
    {
        QList<QStandardItem*> items;
        QStandardItem* surname = new QStandardItem(usersList[i].GetSurname());
        QStandardItem* name = new QStandardItem(usersList[i].GetName());
        QStandardItem* patronymic = new QStandardItem(usersList[i].GetPatronymic());
        QStandardItem* nameSector = new QStandardItem(usersList[i].GetNameSector());
        QStandardItem* namePosition = new QStandardItem(usersList[i].GetNamePosition());
        QStandardItem* dateIncoming = new QStandardItem(usersList[i].GetDateIncoming());
        QStandardItem* dateDismiss = new QStandardItem(usersList[i].GetDateDismiss());
        items.append(surname);
        items.append(name);
        items.append(patronymic);
        items.append(nameSector);
        items.append(namePosition);
        items.append(dateIncoming);
        items.append(dateDismiss);
        model->appendRow(items);
    }

    model->setHorizontalHeaderItem( 0, new QStandardItem( "Фамилия" ) );
    model->setHorizontalHeaderItem( 1, new QStandardItem( "Имя" ) );
    model->setHorizontalHeaderItem( 2, new QStandardItem( "Отчество" ) );
    model->setHorizontalHeaderItem( 3, new QStandardItem( "Участок" ) );
    model->setHorizontalHeaderItem( 4, new QStandardItem( "Должность" ) );
    model->setHorizontalHeaderItem( 5, new QStandardItem( "Дата принятия" ) );
    model->setHorizontalHeaderItem( 6, new QStandardItem( "Дата увольнения" ) );

    ui->tableView->setModel(model);
}

void UsersDismissWindow::on_pushButton_clicked()
{
    QWidget::close();
}

void UsersDismissWindow::on_tableView_doubleClicked(const QModelIndex &index)
{
    UsersWindow uw;
    uw.recieveData(usersList[index.row()], sectorsList, positionsList);
    uw.setModal(true);
    uw.exec();
    initData();
}
