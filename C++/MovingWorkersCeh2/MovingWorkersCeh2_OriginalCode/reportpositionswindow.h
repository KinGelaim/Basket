#ifndef REPORTPOSITIONSWINDOW_H
#define REPORTPOSITIONSWINDOW_H

#include <QDialog>

#include "database.h"

#include <QMessageBox>
#include <QDir>
#include <QFileDialog>
#include <QTextCodec>

namespace Ui {
class reportpositionswindow;
}

class reportpositionswindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit reportpositionswindow(QWidget *parent = 0);
    ~reportpositionswindow();

    void recieveData(QList<Sector> sectorsList, QList<Position> positionsList);
    
private slots:
    void on_pushButton_clicked();

    void on_comboBox_currentIndexChanged(int index);

private:
    Ui::reportpositionswindow *ui;

    DataBase *db;

    QList<Sector> sectorsList;
    QList<Position> positionsList;

    void initData();
};

#endif // REPORTPOSITIONSWINDOW_H
