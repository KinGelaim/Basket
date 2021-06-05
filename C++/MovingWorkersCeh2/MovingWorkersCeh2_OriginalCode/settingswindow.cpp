#include "settingswindow.h"
#include "ui_settingswindow.h"

SettingsWindow::SettingsWindow(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::SettingsWindow)
{
    ui->setupUi(this);
}

SettingsWindow::~SettingsWindow()
{
    delete ui;
}

void SettingsWindow::recieveData(QString databasePath)
{
    ui->lineEdit->setText(databasePath);
}

void SettingsWindow::on_pushButton_clicked()
{
    QTextCodec::setCodecForLocale(QTextCodec::codecForName("UTF-8"));

    QString FILE_NAME = QCoreApplication::applicationDirPath() + "/Settings/settings.txt";

    QFile out( FILE_NAME );
    if( out.open( QIODevice::WriteOnly ) ) {
        QTextStream stream( &out );
        stream << ui->lineEdit->text();
        out.close();
    }
    QWidget::close();
}

void SettingsWindow::on_pushButton_2_clicked()
{
    ui->lineEdit->setText(QFileDialog::getExistingDirectory(0, "Directory Dialog", "") + "/");

}
