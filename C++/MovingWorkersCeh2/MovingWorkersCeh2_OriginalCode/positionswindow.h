#ifndef POSITIONSWINDOW_H
#define POSITIONSWINDOW_H

#include <QDialog>
#include "database.h"
#include "mainwindow.h"

namespace Ui {
class PositionsWindow;
}

class PositionsWindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit PositionsWindow(QWidget *parent = 0);
    ~PositionsWindow();

	void recieveData(Position position, QList<Sector> sectorsList);
    void recieveData(QList<Sector> sectorsList);
    
private slots:
    void on_pushButton_clicked();

    void on_pushButton_2_clicked();

private:
    Ui::PositionsWindow *ui;

    DataBase *db;

    bool isUpdate;
	Position position;
    QList<Sector> sectorsList;

    void initData();
};

#endif // POSITIONSWINDOW_H
