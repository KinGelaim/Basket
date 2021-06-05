#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include "database.h"
#include <QDir>
#include <QList>
#include <QStandardItemModel>
#include <QTextCodec>
#include <QDesktopServices>
#include <QUrl>
#include <QFileDialog>

#include "sectorswindow.h"
#include "sector.h"

#include "positionswindow.h"
#include "position.h"

#include "userswindow.h"
#include "user.h"

#include "settingswindow.h"

#include "usersdismisswindow.h"

#include "abouttheprogram.h"

#include "reportpositionswindow.h"

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT
    
public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();
    
private slots:
    void on_pushButton_5_clicked();

    void on_pushButton_3_clicked();

    void on_pushButton_2_clicked();

    void on_actionOpen_triggered();

    void on_actionSummaryReport_triggered();

    void on_actionExit_triggered();

    void on_actionSettings_triggered();

    void on_actionAbout_the_program_triggered();

    void on_tableView_doubleClicked(const QModelIndex &index);

    void on_tableView_2_doubleClicked(const QModelIndex &index);

    void on_treeView_doubleClicked(const QModelIndex &index);

    void on_pushButton_clicked();

    void on_actionSectorsReport_triggered();

    void on_actionPositionsReport_triggered();

    void on_actionUsersReport_triggered();

    void on_pushButton_6_clicked();

    void on_pushButton_4_clicked();

private:
    Ui::MainWindow  *ui;
    // Объект для взаимодействия с информацией в базе данных
    DataBase      *db;

    QList<Sector> sectorsList;
    QList<Position> positionsList;
    QList<User> usersList;

    QString dataBasePath;

private:
    void initializationData();
    void setupModel(const QString &tableName, const QStringList &headers);
    void createUI();

    void showSectors();
    void getSectorsData();
    void refreshSectors();

    void showPositions();
    void getPositionsData();
    void refreshPositions();

    void showUsers();
    void getUsersData();
    void refreshUsers();

    void refreshAllData();

    void loadSettings();
};

#endif // MAINWINDOW_H
