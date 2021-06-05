#include "reportpositionswindow.h"
#include "ui_reportpositionswindow.h"

reportpositionswindow::reportpositionswindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::reportpositionswindow)
{
    ui->setupUi(this);
}

reportpositionswindow::~reportpositionswindow()
{
    delete ui;
}

void reportpositionswindow::recieveData(QList<Sector> sectorsList, QList<Position> positionsList)
{
    this->sectorsList = sectorsList;
    this->positionsList = positionsList;

    initData();
}

void reportpositionswindow::initData()
{
    for(int i=0; i < this->sectorsList.length(); i++)
    {
        ui->comboBox->addItem(sectorsList[i].GetName());
    }
}

void reportpositionswindow::on_comboBox_currentIndexChanged(int index)
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

void reportpositionswindow::on_pushButton_clicked()
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
    int idPosition = positionsList[takeIndexPosition].GetID();

    QList<User> usersList = db->getDataFromUsers(QString::number(idPosition));

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

    QWidget::close();
}
