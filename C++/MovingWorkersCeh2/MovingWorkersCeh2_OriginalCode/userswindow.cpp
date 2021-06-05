#include "userswindow.h"
#include "ui_userswindow.h"

UsersWindow::UsersWindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::UsersWindow)
{
    ui->setupUi(this);

    ui->pushButton_2->hide();
}

UsersWindow::~UsersWindow()
{
    delete ui;
}

void UsersWindow::recieveData(QList<Sector> sectorsList,
                              QList<Position> positionsList)
{
    this->isUpdate = false;
    this->sectorsList = sectorsList;
    this->positionsList = positionsList;
    initData();
}

void UsersWindow::recieveData(User user, QList<Sector> sectorsList,
                              QList<Position> positionsList)
{
    this->isUpdate = true;
	this->user = user;
    this->sectorsList = sectorsList;
    this->positionsList = positionsList;
    initData();
}

void UsersWindow::initData()
{
    for(int i=0; i < this->sectorsList.length(); i++)
    {
        ui->comboBox->addItem(sectorsList[i].GetName());
    }
    /*
    for(int i=0; i < this->positionsList.length(); i++)
    {
        ui->comboBox_2->addItem(positionsList[i].GetName());
    }
    */
	ui->dateEdit->setDate(QDate::currentDate());

    ui->label_7->hide();
    ui->dateEdit_2->hide();

    if(isUpdate)
	{
		ui->lineEdit->setText(user.GetSurname());
		ui->lineEdit_2->setText(user.GetName());
		ui->lineEdit_3->setText(user.GetPatronymic());
        int indexSector = 0;
        for(int i =0; i < this->sectorsList.length(); i++)
        {
            if(sectorsList[i].GetID() == user.GetSectorID())
                indexSector = i;
        }
        ui->comboBox->setCurrentIndex(indexSector);
        int indexPosition= 0;
        for(int i =0; i < this->positionsList.length(); i++)
        {
            if(positionsList[i].GetSectorID() == user.GetSectorID())
            {
                if(positionsList[i].GetID() == user.GetPositionID())
                    break;
                indexPosition++;
            }
        }
        ui->comboBox_2->setCurrentIndex(indexPosition);
        ui->dateEdit->setDate(QDate::fromString(user.GetDateIncoming(), "yyyy-MM-dd"));
        ui->dateEdit_2->setDate(QDate::fromString(user.GetDateDismiss(), "yyyy-MM-dd"));

        if(user.GetDateDismiss() != "2000-01-01")
            ui->checkBox->toggle();

        ui->pushButton_2->show();
    }
}

void UsersWindow::on_comboBox_currentIndexChanged(int index)
{
    ui->comboBox_2->clear();
    for(int i = 0; i < positionsList.length(); i++)
    {
        if(positionsList[i].GetSectorID() == sectorsList[ui->comboBox->currentIndex()].GetID())
        {
            ui->comboBox_2->addItem(positionsList[i].GetName());
        }
    }
}

void UsersWindow::on_pushButton_clicked()
{
    if(ui->comboBox->currentIndex() == -1)
    {
        QMessageBox::information(this, "Внимание!", "Выберите участок!");
        return;
    }
    if(ui->comboBox_2->currentIndex() == -1)
    {
        QMessageBox::information(this, "Внимание!", "Выберите должность!");
        return;
    }
    if(ui->lineEdit->text() == "")
    {
        QMessageBox::information(this, "Внимание!", "Заполните фамилию!");
        return;
    }
    if(ui->lineEdit_2->text() == "")
    {
        QMessageBox::information(this, "Внимание!", "Заполните имя!");
        return;
    }
    if(ui->lineEdit_3->text() == "")
    {
        QMessageBox::information(this, "Внимание!", "Заполните отчество!");
        return;
    }
    if(!isUpdate)
    {
        QVariantList data;
        data.append(ui->lineEdit->text());
        data.append(ui->lineEdit_2->text());
        data.append(ui->lineEdit_3->text());
        int takeIndexPosition = 0;
        int indexPosition = 0;
        for(int i = 0; i < positionsList.length(); i++)
        {
            if(sectorsList[ui->comboBox->currentIndex()].GetID() == positionsList[i].GetSectorID())
            {
                if(indexPosition == ui->comboBox_2->currentIndex())
                {
                    takeIndexPosition = i;
                    break;
                }
                indexPosition++;
            }
        }
        data.append(positionsList[takeIndexPosition].GetID());
        data.append(ui->dateEdit->text());
        data.append(ui->dateEdit_2->text());
        db->insertIntoUsersTable(data);
    }
    else
    {
        user.SetSurname(ui->lineEdit->text());
        user.SetName(ui->lineEdit_2->text());
        user.SetPatronymic(ui->lineEdit_3->text());
        int takeIndexPosition = 0;
        int indexPosition = 0;
        for(int i = 0; i < positionsList.length(); i++)
        {
            if(sectorsList[ui->comboBox->currentIndex()].GetID() == positionsList[i].GetSectorID())
            {
                if(indexPosition == ui->comboBox_2->currentIndex())
                {
                    takeIndexPosition = i;
                    break;
                }
                indexPosition++;
            }
        }
        user.SetPositionID(positionsList[takeIndexPosition].GetID());
        user.SetDateIncoming(ui->dateEdit->text());
        user.SetDateDismiss(ui->dateEdit_2->text());
        db->updateUsersTable(user);
    }
    QWidget::close();
}

void UsersWindow::on_pushButton_2_clicked()
{
    if(isUpdate)
    {
        QMessageBox::StandardButton reply =
                QMessageBox::question(this,
                                      "Подтверждение",
                                      "Вы уверены, что желаете удалить данного сотрудника?",
                                      QMessageBox::Yes | QMessageBox::No);
        if(reply == QMessageBox::Yes)
        {
            db->deleteFromUsersTable(user.GetID());
            QWidget::close();
        }
    }
}

void UsersWindow::on_checkBox_toggled(bool checked)
{
    if(checked)
    {
        ui->label_7->show();
        ui->dateEdit_2->show();
        ui->dateEdit_2->setDate(QDate::currentDate());
    }
    else
    {
        ui->label_7->hide();
        ui->dateEdit_2->hide();
        ui->dateEdit_2->setDate(QDate::fromString("2000-01-01", "yyyy-MM-dd"));
    }
}
