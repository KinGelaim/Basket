/********************************************************************************
** Form generated from reading UI file 'reportpositionswindow.ui'
**
** Created: Mon Nov 23 20:12:18 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_REPORTPOSITIONSWINDOW_H
#define UI_REPORTPOSITIONSWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QComboBox>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_reportpositionswindow
{
public:
    QVBoxLayout *verticalLayout;
    QLabel *label;
    QComboBox *comboBox;
    QLabel *label_2;
    QComboBox *comboBox_2;
    QPushButton *pushButton;

    void setupUi(QDialog *reportpositionswindow)
    {
        if (reportpositionswindow->objectName().isEmpty())
            reportpositionswindow->setObjectName(QStringLiteral("reportpositionswindow"));
        reportpositionswindow->resize(255, 160);
        verticalLayout = new QVBoxLayout(reportpositionswindow);
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        label = new QLabel(reportpositionswindow);
        label->setObjectName(QStringLiteral("label"));

        verticalLayout->addWidget(label);

        comboBox = new QComboBox(reportpositionswindow);
        comboBox->setObjectName(QStringLiteral("comboBox"));

        verticalLayout->addWidget(comboBox);

        label_2 = new QLabel(reportpositionswindow);
        label_2->setObjectName(QStringLiteral("label_2"));

        verticalLayout->addWidget(label_2);

        comboBox_2 = new QComboBox(reportpositionswindow);
        comboBox_2->setObjectName(QStringLiteral("comboBox_2"));

        verticalLayout->addWidget(comboBox_2);

        pushButton = new QPushButton(reportpositionswindow);
        pushButton->setObjectName(QStringLiteral("pushButton"));

        verticalLayout->addWidget(pushButton);


        retranslateUi(reportpositionswindow);

        QMetaObject::connectSlotsByName(reportpositionswindow);
    } // setupUi

    void retranslateUi(QDialog *reportpositionswindow)
    {
        reportpositionswindow->setWindowTitle(QApplication::translate("reportpositionswindow", "\320\241\320\276\320\267\320\264\320\260\320\275\320\270\320\265 \320\276\321\202\321\207\321\221\321\202\320\260 \320\277\320\276 \320\264\320\276\320\273\320\266\320\275\320\276\321\201\321\202\320\270", 0));
        label->setText(QApplication::translate("reportpositionswindow", "\320\222\321\213\320\261\320\265\321\200\320\270\321\202\320\265 \321\203\321\207\320\260\321\201\321\202\320\276\320\272", 0));
        label_2->setText(QApplication::translate("reportpositionswindow", "\320\222\321\213\320\261\320\265\321\200\320\270\321\202\320\265 \320\264\320\276\320\273\320\266\320\275\320\276\321\201\321\202\321\214", 0));
        pushButton->setText(QApplication::translate("reportpositionswindow", "\320\241\320\276\320\267\320\264\320\260\321\202\321\214", 0));
    } // retranslateUi

};

namespace Ui {
    class reportpositionswindow: public Ui_reportpositionswindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_REPORTPOSITIONSWINDOW_H
