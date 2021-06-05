#ifndef SECTORSWINDOW_H
#define SECTORSWINDOW_H

#include <QDialog>
#include "database.h"

#include <QMessageBox>

namespace Ui {
class SectorsWindow;
}

class SectorsWindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit SectorsWindow(QWidget *parent = 0);
    ~SectorsWindow();
	
	void recieveData(Sector sector);
    
private slots:
    void on_pushButton_clicked();

    void on_pushButton_2_clicked();

private:
    Ui::SectorsWindow *ui;
    DataBase *db;
	Sector sector;
    bool isUpdate;
	
	void initData();
};

#endif // SECTORSWINDOW_H
