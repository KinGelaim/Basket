#include "positionswindow.h"
#include "ui_positionswindow.h"

PositionsWindow::PositionsWindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::PositionsWindow)
{
    ui->setupUi(this);

    ui->pushButton_2->hide();
}

PositionsWindow::~PositionsWindow()
{
    delete ui;
}

void PositionsWindow::recieveData(QList<Sector> sectorsList)
{
    this->isUpdate = false;
    this->sectorsList = sectorsList;
    initData();
}

void PositionsWindow::recieveData(Position position, QList<Sector> sectorsList)
{
    this->isUpdate = true;
	this->position = position;
    this->sectorsList = sectorsList;
    initData();
}

void PositionsWindow::initData()
{
    for(int i=0; i < this->sectorsList.length(); i++)
    {
        ui->comboBox->addItem(sectorsList[i].GetName());
    }
	
    if(isUpdate)
	{
		ui->lineEdit->setText(position.GetName());
        int indexSector = 0;
        for(int i=0; i < this->sectorsList.length(); i++)
        {
            if(sectorsList[i].GetID() == position.GetSectorID())
                indexSector = i;
        }
        ui->comboBox->setCurrentIndex(indexSector);

        ui->pushButton_2->show();
	}
}

void PositionsWindow::on_pushButton_clicked()
{
    if(!isUpdate)
	{
		QVariantList data;
		data.append(ui->lineEdit->text());
		data.append(sectorsList[ui->comboBox->currentIndex()].GetID());
		db->insertIntoPositionsTable(data);
	}
	else
	{
		this->position.SetName(ui->lineEdit->text());
		this->position.SetSectorID(sectorsList[ui->comboBox->currentIndex()].GetID());
		db->updatePositionsTable(this->position);
	}
    QWidget::close();
}

void PositionsWindow::on_pushButton_2_clicked()
{
    if(isUpdate)
    {
        QMessageBox::StandardButton reply =
                QMessageBox::question(this, "Подтверждение",
                                      "Вы уверены, что желаете удалить должность? Также будут удалены все сотрудники занимающие данную должность!",
                                      QMessageBox::Yes | QMessageBox::No);
        if(reply == QMessageBox::Yes)
        {
            db->deleteFromPositionsTable(position.GetID());
            QWidget::close();
        }
    }
}
