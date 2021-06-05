#include "mainwindow.h"
#include "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);

    initializationData();
}

MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::initializationData()
{
    loadSettings();

    QString path = dataBasePath;
    if(!QDir(dataBasePath).exists())
        QDir().mkdir(dataBasePath);
    db = new DataBase();
    db->connectToDataBase(path);

    refreshUsers();
    refreshPositions();
    refreshSectors();
}

void MainWindow::loadSettings()
{
    QTextCodec::setCodecForLocale(QTextCodec::codecForName("UTF-8"));

    QString FILE_NAME = QCoreApplication::applicationDirPath() + "/Settings/settings.txt";
    if(!QDir(QCoreApplication::applicationDirPath() + "/Settings").exists())
        QDir().mkdir(QCoreApplication::applicationDirPath() + "/Settings");

    QFile in( FILE_NAME );
    if( in.open( QIODevice::ReadOnly ) ) {
        QTextStream stream( &in );
        QString textSettings = stream.readAll();
        dataBasePath = textSettings.split(";")[0];
        in.close();
    }
}

void MainWindow::refreshAllData()
{
    getSectorsData();
    getPositionsData();
    getUsersData();

    showSectors();
    showPositions();
    showUsers();
}

// Участки
void MainWindow::on_pushButton_5_clicked()
{
    SectorsWindow sw;
    sw.setModal(true);
    sw.exec();
    refreshSectors();
}

void MainWindow::getSectorsData()
{
    sectorsList = db->getDataFromSectors();
}

void MainWindow::refreshSectors()
{
    sectorsList = db->getDataFromSectors();
    showSectors();
}

void MainWindow::showSectors()
{
    QStandardItemModel* model = new QStandardItemModel();

    for(int i=0; i < sectorsList.length(); i++ )
    {
        QStandardItem* item = new QStandardItem(sectorsList[i].GetName());
        for(int j = 0; j < positionsList.length(); j++)
        {
            if(positionsList[j].GetSectorID() == sectorsList[i].GetID())
            {
                QStandardItem* item2 = new QStandardItem(positionsList[j].GetName());
                item->appendRow(item2);
            }
        }
        model->appendRow(item);
    }

    model->setHorizontalHeaderItem( 0, new QStandardItem( "Участки" ) );

    ui->treeView->setModel(model);
}

void MainWindow::on_treeView_doubleClicked(const QModelIndex &index)
{
    if(index.parent().row() == -1)
    {
        SectorsWindow sw;
        sw.recieveData(sectorsList[index.row()]);
        sw.setModal(true);
        sw.exec();
        refreshAllData();
    }
}

void MainWindow::on_pushButton_6_clicked()
{
    ui->treeView->expandAll();
}

void MainWindow::on_pushButton_4_clicked()
{
    ui->treeView->collapseAll();
}

// Должности
void MainWindow::on_pushButton_3_clicked()
{
    PositionsWindow pw;
    pw.recieveData(sectorsList);
    pw.setModal(true);
    pw.exec();
    refreshPositions();
}

void MainWindow::getPositionsData()
{
    positionsList = db->getDataFromPositions();
}

void MainWindow::refreshPositions()
{
    positionsList = db->getDataFromPositions();
    showPositions();
}

void MainWindow::showPositions()
{
    QStandardItemModel* model = new QStandardItemModel(0, 8);

    for(int i=0; i < positionsList.length(); i++ )
    {
        QList<QStandardItem*> items;
		
        int idPosition = positionsList[i].GetID();
        QStandardItem* name = new QStandardItem(positionsList[i].GetName());
		QStandardItem* nameSector = new QStandardItem(positionsList[i].GetNameSector());
		
        QString currentUser = "";
        int allCount = 0;
        QString dateLastIncoming = "";
        QString dateLastDismiss = "";
        QString dateLastService = "";
        QString averageService = "";
        int allDaysService = 0;
        int countDaysService = 0;

        for(int j=0; j < usersList.length(); j++)
        {
            if(usersList[j].GetPositionID() == idPosition)
            {
                currentUser = usersList[j].GetSurname() + " " +
                        usersList[j].GetName() + " " +
                        usersList[j].GetPatronymic() + "\n";
                allCount++;
                dateLastIncoming = usersList[j].GetDateIncoming() + "\n";
            }
        }

        QList<User> dismissUsers = db->getDataFromUsers(true);
        for(int j=0; j < dismissUsers.length(); j++)
        {
            if(dismissUsers[j].GetPositionID() == idPosition)
            {
                if(dateLastDismiss == "")
                    dateLastDismiss = dismissUsers[j].GetDateDismiss();
                allCount++;
                long int interval =
                            QDateTime::fromString(dismissUsers[j].GetDateDismiss(), "yyyy-MM-dd").toMSecsSinceEpoch() -
                            QDateTime::fromString(dismissUsers[j].GetDateIncoming(), "yyyy-MM-dd").toMSecsSinceEpoch();
                //if(dateLastService == "")
                    dateLastService = QString::number(interval / 86400000);
                allDaysService += interval / 86400000;
                countDaysService++;
            }
        }

        if(countDaysService != 0)
            averageService = QString::number(allDaysService / countDaysService);

        QStandardItem* currentUserItem = new QStandardItem(currentUser);
        QStandardItem* allCountItem = new QStandardItem(QString::number(allCount));
        QStandardItem* dateLastIncomingItem = new QStandardItem(dateLastIncoming);
        QStandardItem* dateLastDismissItem = new QStandardItem(dateLastDismiss);
        QStandardItem* dateLastServiceItem = new QStandardItem(dateLastService);
        QStandardItem* averageServiceItem = new QStandardItem(averageService);

        items.append(name);
		items.append(nameSector);
        items.append(currentUserItem);
        items.append(allCountItem);
        items.append(dateLastIncomingItem);
        items.append(dateLastDismissItem);
        items.append(dateLastServiceItem);
        items.append(averageServiceItem);

        model->appendRow(items);
    }

    model->setHorizontalHeaderItem( 0, new QStandardItem( "Должность" ) );
    model->setHorizontalHeaderItem( 1, new QStandardItem( "Участок" ) );
    model->setHorizontalHeaderItem( 2, new QStandardItem( "Текущий сотрудник" ) );
    model->setHorizontalHeaderItem( 3, new QStandardItem( "Всего было" ) );
    model->setHorizontalHeaderItem( 4, new QStandardItem( "Дата последнего принятия" ) );
    model->setHorizontalHeaderItem( 5, new QStandardItem( "Дата последнего увольнения" ) );
    model->setHorizontalHeaderItem( 6, new QStandardItem( "Срок последней службы" ) );
    model->setHorizontalHeaderItem( 7, new QStandardItem( "Средний срок службы" ) );

    ui->tableView_2->setModel(model);
    ui->tableView_2->resizeColumnsToContents();
}

void MainWindow::on_tableView_2_doubleClicked(const QModelIndex &index)
{
    PositionsWindow pw;
    pw.recieveData(positionsList[index.row()], sectorsList);
    pw.setModal(true);
    pw.exec();
    refreshAllData();
}


// Сотрудники
void MainWindow::on_pushButton_2_clicked()
{
    UsersWindow uw;
    uw.recieveData(sectorsList, positionsList);
    uw.setModal(true);
    uw.exec();
    refreshUsers();
}

void MainWindow::getUsersData()
{
    usersList = db->getDataFromUsers();
}

void MainWindow::refreshUsers()
{
    usersList = db->getDataFromUsers();
    showUsers();
}

void MainWindow::showUsers()
{
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
        items.append(surname);
        items.append(name);
        items.append(patronymic);
        items.append(nameSector);
        items.append(namePosition);
        items.append(dateIncoming);
        model->appendRow(items);
    }

    model->setHorizontalHeaderItem( 0, new QStandardItem( "Фамилия" ) );
    model->setHorizontalHeaderItem( 1, new QStandardItem( "Имя" ) );
    model->setHorizontalHeaderItem( 2, new QStandardItem( "Отчество" ) );
    model->setHorizontalHeaderItem( 3, new QStandardItem( "Участок" ) );
    model->setHorizontalHeaderItem( 4, new QStandardItem( "Должность" ) );
    model->setHorizontalHeaderItem( 5, new QStandardItem( "Дата принятия" ) );

    ui->tableView->setModel(model);
    ui->tableView->resizeColumnToContents(5);
}

void MainWindow::on_tableView_doubleClicked(const QModelIndex &index)
{
    UsersWindow uw;
    uw.recieveData(usersList[index.row()], sectorsList, positionsList);
    uw.setModal(true);
    uw.exec();
    refreshAllData();
}

void MainWindow::on_pushButton_clicked()
{
    UsersDismissWindow udw;
    udw.recieveData(sectorsList, positionsList);
    udw.setModal(true);
    udw.exec();
    refreshUsers();
}

// Верхнее Меню
void MainWindow::on_actionOpen_triggered()
{
    QString path = QDir::toNativeSeparators(QApplication::applicationDirPath());
    QDesktopServices::openUrl(QUrl::fromLocalFile(path));
}

void MainWindow::on_actionSectorsReport_triggered()
{
    //QTextCodec::setCodecForLocale(QTextCodec::codecForName("cp-866"));
    QString reportsPath = QCoreApplication::applicationDirPath() + "/Reports";
    if(!QDir(reportsPath).exists())
        QDir().mkdir(reportsPath);
    QString strFilter;
    QString str = QFileDialog ::getSaveFileName(0,tr("Сохраняем Ваш файл"),"Reports","*.txt",&strFilter);
    QFile file2(str);
    if(file2.open(QIODevice::WriteOnly))
    {
        int index = 1;
        QTextStream out (&file2);
        out.setCodec(QTextCodec::codecForName("UTF-8"));
        QString nameHeader = "Участок / должность";
        int maxLength = nameHeader.length();
        for(int i=0; i < sectorsList.length(); i++)
        {
            if(maxLength < sectorsList[i].GetName().length())
                maxLength = sectorsList[i].GetName().length();
        }
        for(int i=0; i < positionsList.length(); i++)
        {
            if(maxLength < positionsList[i].GetName().length())
                maxLength = positionsList[i].GetName().length();
        }
        QString emptyString = "";
        out << " №|" + nameHeader.rightJustified(maxLength, ' ') + "|\n";
        out << emptyString.rightJustified(maxLength, '-') + "----\n";
        for(int i=0; i < sectorsList.length(); i++)
        {
            QString stringIndex = QString::number(index);
            stringIndex = stringIndex.rightJustified(2, ' ');
            index++;

            QString stringName = sectorsList[i].GetName();
            stringName = stringName.rightJustified(maxLength, ' ');

            QString outText = stringIndex + "|" +
                    stringName + "|" +
                    "\n";
            out << outText;
            out << emptyString.rightJustified(maxLength, '-') + "----\n";
            for(int j = 0; j < positionsList.length(); j++)
            {
                if(positionsList[j].GetSectorID() == sectorsList[i].GetID())
                {
                    QString outPositionText = positionsList[j].GetName();
                    outPositionText = outPositionText.rightJustified(maxLength, ' ');
                    out << "  |" + outPositionText + "|\n" + emptyString.rightJustified(maxLength, '-') + "----\n";
                }
            }
        }
        file2.close();
    }
}

void MainWindow::on_actionPositionsReport_triggered()
{
    reportpositionswindow rpw;
    rpw.recieveData(sectorsList, positionsList);
    rpw.setModal(true);
    rpw.exec();
}

void MainWindow::on_actionUsersReport_triggered()
{
    QString reportsPath = QCoreApplication::applicationDirPath() + "/Reports";
    if(!QDir(reportsPath).exists())
        QDir().mkdir(reportsPath);
    QString strFilter;
    QString str = QFileDialog ::getSaveFileName(0,tr("Сохраняем Ваш файл"),reportsPath,"*.txt",&strFilter);
    QFile file2(str);
    if(file2.open(QIODevice::WriteOnly))
    {
        int index = 1;
        QTextStream out (&file2);
        out.setCodec(QTextCodec::codecForName("UTF-8"));
        QString nameHeaderFIO = "ФИО";
        QString nameHeaderSector = "Участок";
        QString nameHeaderPosition = "Должность";
        QString nameHeaderDateIncoming = "Дата принятия";
        int maxLengthFIO = 10;
        int maxLengthSector = 10;
        int maxLengthPosition = 10;
        int maxLengthDateIncoming = nameHeaderDateIncoming.length();
        for(int i=0; i < usersList.length(); i++)
        {
            QString prFIO = usersList[i].GetSurname() + " " +
                    usersList[i].GetName() + " " +
                    usersList[i].GetPatronymic();
            if(maxLengthFIO < prFIO.length())
                maxLengthFIO = prFIO.length();
            if(maxLengthSector < usersList[i].GetNameSector().length())
                maxLengthSector = usersList[i].GetNameSector().length();
            if(maxLengthPosition < usersList[i].GetNamePosition().length())
                maxLengthPosition = usersList[i].GetNamePosition().length();
            if(maxLengthDateIncoming < usersList[i].GetDateIncoming().length())
                maxLengthDateIncoming = usersList[i].GetDateIncoming().length();
        }
        out << "  №|" + nameHeaderFIO.rightJustified(maxLengthFIO, ' ') + "|" +
               nameHeaderSector.rightJustified(maxLengthSector, ' ') + "|" +
               nameHeaderPosition.rightJustified(maxLengthPosition, ' ') + "|" +
               nameHeaderDateIncoming.rightJustified(maxLengthDateIncoming, ' ') + "|\n";
        QString emptyString = "";
        out << emptyString.rightJustified(maxLengthFIO + maxLengthSector + maxLengthPosition + maxLengthDateIncoming + 8, '-') + "\n";
        for(int i=0; i < usersList.length(); i++)
        {
            QString stringIndex = QString::number(index);
            stringIndex = stringIndex.rightJustified(3, ' ');
            index++;

            QString stringFIO = usersList[i].GetSurname() + " " +
                    usersList[i].GetName() + " " +
                    usersList[i].GetPatronymic();
            stringFIO = stringFIO.rightJustified(maxLengthFIO, ' ');
            QString stringSector = usersList[i].GetNameSector().rightJustified(maxLengthSector, ' ');
            QString stringPosition = usersList[i].GetNamePosition().rightJustified(maxLengthPosition, ' ');
            QString stringDateIncoming = usersList[i].GetDateIncoming().rightJustified(maxLengthDateIncoming, ' ');

            QString outText = stringIndex + "|" +
                    stringFIO + "|" +
                    stringSector + "|" +
                    stringPosition + "|" +
                    stringDateIncoming + "|" +
                    "\n" +
                    "----" +
                    emptyString.rightJustified(maxLengthFIO, '-') + "-" +
                    emptyString.rightJustified(maxLengthSector, '-') + "-" +
                    emptyString.rightJustified(maxLengthPosition, '-') + "-" +
                    emptyString.rightJustified(maxLengthDateIncoming, '-') + "-\n";
            out << outText;
        }
        file2.close();
    }
}

void MainWindow::on_actionSummaryReport_triggered()
{
    QString reportsPath = QCoreApplication::applicationDirPath() + "/Reports";
    if(!QDir(reportsPath).exists())
        QDir().mkdir(reportsPath);
    QString strFilter;
    QString str = QFileDialog ::getSaveFileName(0,tr("Сохраняем Ваш файл"),reportsPath,"*.txt",&strFilter);
    QFile file2(str);
    if(file2.open(QIODevice::WriteOnly))
    {
        int index = 1;
        QTextStream out (&file2);
        out.setCodec(QTextCodec::codecForName("UTF-8"));
        QString nameHeaderFIO = "ФИО";
        QString nameHeaderSector = "Участок";
        QString nameHeaderPosition = "Должность";
        QString nameHeaderDateIncoming = "Дата принятия";
        int maxLengthFIO = 10;
        int maxLengthSector = 10;
        int maxLengthPosition = 10;
        int maxLengthDateIncoming = nameHeaderDateIncoming.length();

        for(int i=0; i < usersList.length(); i++)
        {
            QString prFIO = usersList[i].GetSurname() + " " +
                    usersList[i].GetName() + " " +
                    usersList[i].GetPatronymic();
            if(maxLengthFIO < prFIO.length())
                maxLengthFIO = prFIO.length();
            if(maxLengthSector < usersList[i].GetNameSector().length())
                maxLengthSector = usersList[i].GetNameSector().length();
            if(maxLengthPosition < usersList[i].GetNamePosition().length())
                maxLengthPosition = usersList[i].GetNamePosition().length();
            if(maxLengthDateIncoming < usersList[i].GetDateIncoming().length())
                maxLengthDateIncoming = usersList[i].GetDateIncoming().length();
        }

        QList<User> dismissUsersList;
        dismissUsersList = db->getDataFromUsers(true);
        for(int i=0; i < dismissUsersList.length(); i++)
        {
            QString prFIO = dismissUsersList[i].GetSurname() + " " +
                    dismissUsersList[i].GetName() + " " +
                    dismissUsersList[i].GetPatronymic();
            if(maxLengthFIO < prFIO.length())
                maxLengthFIO = prFIO.length();
            if(maxLengthSector < dismissUsersList[i].GetNameSector().length())
                maxLengthSector = dismissUsersList[i].GetNameSector().length();
            if(maxLengthPosition < dismissUsersList[i].GetNamePosition().length())
                maxLengthPosition = dismissUsersList[i].GetNamePosition().length();
            if(maxLengthDateIncoming < dismissUsersList[i].GetDateIncoming().length())
                maxLengthDateIncoming = dismissUsersList[i].GetDateIncoming().length();
        }

        out << "  №|" + nameHeaderFIO.rightJustified(maxLengthFIO, ' ') + "|" +
               nameHeaderSector.rightJustified(maxLengthSector, ' ') + "|" +
               nameHeaderPosition.rightJustified(maxLengthPosition, ' ') + "|" +
               nameHeaderDateIncoming.rightJustified(maxLengthDateIncoming, ' ') + "|\n";
        QString emptyString = "";
        out << emptyString.rightJustified(maxLengthFIO + maxLengthSector + maxLengthPosition + maxLengthDateIncoming + 8, '-') + "\n";
        for(int i=0; i < usersList.length(); i++)
        {
            QString stringIndex = QString::number(index);
            stringIndex = stringIndex.rightJustified(3, ' ');
            index++;

            QString stringFIO = usersList[i].GetSurname() + " " +
                    usersList[i].GetName() + " " +
                    usersList[i].GetPatronymic();
            stringFIO = stringFIO.rightJustified(maxLengthFIO, ' ');
            QString stringSector = usersList[i].GetNameSector().rightJustified(maxLengthSector, ' ');
            QString stringPosition = usersList[i].GetNamePosition().rightJustified(maxLengthPosition, ' ');
            QString stringDateIncoming = usersList[i].GetDateIncoming().rightJustified(maxLengthDateIncoming, ' ');

            QString outText = stringIndex + "|" +
                    stringFIO + "|" +
                    stringSector + "|" +
                    stringPosition + "|" +
                    stringDateIncoming + "|" +
                    "\n" +
                    "----" +
                    emptyString.rightJustified(maxLengthFIO, '-') + "-" +
                    emptyString.rightJustified(maxLengthSector, '-') + "-" +
                    emptyString.rightJustified(maxLengthPosition, '-') + "-" +
                    emptyString.rightJustified(maxLengthDateIncoming, '-') + "-\n";
            out << outText;
        }

        for(int i=0; i < dismissUsersList.length(); i++)
        {
            QString stringIndex = QString::number(index);
            stringIndex = stringIndex.rightJustified(3, ' ');
            index++;

            QString stringFIO = dismissUsersList[i].GetSurname() + " " +
                    dismissUsersList[i].GetName() + " " +
                    dismissUsersList[i].GetPatronymic();
            stringFIO = stringFIO.rightJustified(maxLengthFIO, ' ');
            QString stringSector = dismissUsersList[i].GetNameSector().rightJustified(maxLengthSector, ' ');
            QString stringPosition = dismissUsersList[i].GetNamePosition().rightJustified(maxLengthPosition, ' ');
            QString stringDateIncoming = dismissUsersList[i].GetDateIncoming().rightJustified(maxLengthDateIncoming, ' ');

            QString outText = stringIndex + "|" +
                    stringFIO + "|" +
                    stringSector + "|" +
                    stringPosition + "|" +
                    stringDateIncoming + "|" +
                    "\n" +
                    "----" +
                    emptyString.rightJustified(maxLengthFIO, '-') + "-" +
                    emptyString.rightJustified(maxLengthSector, '-') + "-" +
                    emptyString.rightJustified(maxLengthPosition, '-') + "-" +
                    emptyString.rightJustified(maxLengthDateIncoming, '-') + "-\n";
            out << outText;
        }
        file2.close();
    }
}

void MainWindow::on_actionExit_triggered()
{
    QApplication::exit();
}

void MainWindow::on_actionSettings_triggered()
{
    SettingsWindow sw;
    sw.recieveData(this->dataBasePath);
    sw.setModal(true);
    sw.exec();
}

void MainWindow::on_actionAbout_the_program_triggered()
{
    AboutTheProgram arp;
    arp.setModal(true);
    arp.exec();
}
