#include "abouttheprogram.h"
#include "ui_abouttheprogram.h"

AboutTheProgram::AboutTheProgram(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::AboutTheProgram)
{
    ui->setupUi(this);
}

AboutTheProgram::~AboutTheProgram()
{
    delete ui;
}
