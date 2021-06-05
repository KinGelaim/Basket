#ifndef ABOUTTHEPROGRAM_H
#define ABOUTTHEPROGRAM_H

#include <QDialog>

namespace Ui {
class AboutTheProgram;
}

class AboutTheProgram : public QDialog
{
    Q_OBJECT
    
public:
    explicit AboutTheProgram(QWidget *parent = 0);
    ~AboutTheProgram();
    
private:
    Ui::AboutTheProgram *ui;
};

#endif // ABOUTTHEPROGRAM_H
