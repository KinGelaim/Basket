/********************************************************************************
** Form generated from reading UI file 'abouttheprogram.ui'
**
** Created: Sun Nov 22 15:52:43 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_ABOUTTHEPROGRAM_H
#define UI_ABOUTTHEPROGRAM_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_AboutTheProgram
{
public:
    QVBoxLayout *verticalLayout;
    QLabel *label;
    QLabel *label_2;
    QLabel *label_3;
    QLabel *label_4;

    void setupUi(QDialog *AboutTheProgram)
    {
        if (AboutTheProgram->objectName().isEmpty())
            AboutTheProgram->setObjectName(QStringLiteral("AboutTheProgram"));
        AboutTheProgram->resize(400, 143);
        verticalLayout = new QVBoxLayout(AboutTheProgram);
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        label = new QLabel(AboutTheProgram);
        label->setObjectName(QStringLiteral("label"));
        label->setAlignment(Qt::AlignCenter);

        verticalLayout->addWidget(label);

        label_2 = new QLabel(AboutTheProgram);
        label_2->setObjectName(QStringLiteral("label_2"));
        label_2->setAlignment(Qt::AlignCenter);

        verticalLayout->addWidget(label_2);

        label_3 = new QLabel(AboutTheProgram);
        label_3->setObjectName(QStringLiteral("label_3"));
        label_3->setAlignment(Qt::AlignCenter);

        verticalLayout->addWidget(label_3);

        label_4 = new QLabel(AboutTheProgram);
        label_4->setObjectName(QStringLiteral("label_4"));
        label_4->setAlignment(Qt::AlignCenter);
        label_4->setWordWrap(true);

        verticalLayout->addWidget(label_4);


        retranslateUi(AboutTheProgram);

        QMetaObject::connectSlotsByName(AboutTheProgram);
    } // setupUi

    void retranslateUi(QDialog *AboutTheProgram)
    {
        AboutTheProgram->setWindowTitle(QApplication::translate("AboutTheProgram", "\320\236 \320\277\321\200\320\276\320\263\321\200\320\260\320\274\320\274\320\265", 0));
        label->setText(QApplication::translate("AboutTheProgram", "MovingWorkersCeh2", 0));
        label_2->setText(QApplication::translate("AboutTheProgram", "\320\222\320\265\321\200\321\201\320\270\321\217 1.0.0.7", 0));
        label_3->setText(QApplication::translate("AboutTheProgram", "\320\244\320\232\320\237 \320\235\320\242\320\230\320\230\320\234 (c) 2020", 0));
        label_4->setText(QApplication::translate("AboutTheProgram", "\320\237\321\200\320\276\320\263\321\200\320\260\320\274\320\274\320\260 \320\264\320\273\321\217 \320\276\321\202\321\201\320\273\320\265\320\266\320\270\320\262\320\260\320\275\320\270\321\217 \320\277\320\265\321\200\320\265\320\274\320\265\321\211\320\265\320\275\320\270\321\217 \321\201\320\276\321\202\321\200\321\203\320\264\320\275\320\270\320\272\320\276\320\262 \320\277\320\276 \321\206\320\265\321\205\320\265 \342\204\2262", 0));
    } // retranslateUi

};

namespace Ui {
    class AboutTheProgram: public Ui_AboutTheProgram {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_ABOUTTHEPROGRAM_H
