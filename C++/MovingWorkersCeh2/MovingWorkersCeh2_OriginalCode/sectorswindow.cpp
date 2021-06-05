#include "sectorswindow.h"
#include "ui_sectorswindow.h"

SectorsWindow::SectorsWindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::SectorsWindow)
{
    ui->setupUi(this);
    this->isUpdate = false;

    ui->pushButton_2->hide();
}

SectorsWindow::~SectorsWindow()
{
    delete ui;
}

void SectorsWindow::recieveData(Sector sector)
{
    this->isUpdate = true;
	this->sector = sector;
	
	initData();
}

void SectorsWindow::initData()
{
	ui->lineEdit->setText(this->sector.GetName());

    ui->pushButton_2->show();
}

void SectorsWindow::on_pushButton_clicked()
{
    if(!isUpdate)
	{
		QVariantList data;
		data.append(ui->lineEdit->text());
		db->insertIntoSectorsTable(data);
	}
	else
	{
		this->sector.SetName(ui->lineEdit->text());
		db->updateSectorsTable(this->sector);
	}
    QWidget::close();
}

void SectorsWindow::on_pushButton_2_clicked()
{
    if(isUpdate)
    {
        QMessageBox::StandardButton reply =
                QMessageBox::question(this,
                                      "Подтверждение",
                                      "Вы уверены, что желаете удалить участок? Также будут удалены все должности, расположенные на данном участке и сотрудники, занимающие соответствующие должности!",
                                      QMessageBox::Yes | QMessageBox::No);
        if(reply == QMessageBox::Yes) {
            db->deleteFromSectorsTable(sector.GetID());
            QWidget::close();
        }
    }
}
