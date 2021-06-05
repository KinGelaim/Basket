/********************************************************************************
** Form generated from reading UI file 'sectorswindow.ui'
**
** Created: Wed Nov 25 20:50:55 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_SECTORSWINDOW_H
#define UI_SECTORSWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_SectorsWindow
{
public:
    QVBoxLayout *verticalLayout;
    QLabel *label;
    QLineEdit *lineEdit;
    QHBoxLayout *horizontalLayout;
    QPushButton *pushButton_2;
    QSpacerItem *horizontalSpacer;
    QPushButton *pushButton;

    void setupUi(QDialog *SectorsWindow)
    {
        if (SectorsWindow->objectName().isEmpty())
            SectorsWindow->setObjectName(QStringLiteral("SectorsWindow"));
        SectorsWindow->resize(400, 111);
        verticalLayout = new QVBoxLayout(SectorsWindow);
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        label = new QLabel(SectorsWindow);
        label->setObjectName(QStringLiteral("label"));

        verticalLayout->addWidget(label);

        lineEdit = new QLineEdit(SectorsWindow);
        lineEdit->setObjectName(QStringLiteral("lineEdit"));

        verticalLayout->addWidget(lineEdit);

        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setObjectName(QStringLiteral("horizontalLayout"));
        pushButton_2 = new QPushButton(SectorsWindow);
        pushButton_2->setObjectName(QStringLiteral("pushButton_2"));
        pushButton_2->setStyleSheet(QLatin1String("QPushButton {\n"
"	background-color: #ff002d;\n"
"	color: white;s\n"
"}"));

        horizontalLayout->addWidget(pushButton_2);

        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout->addItem(horizontalSpacer);

        pushButton = new QPushButton(SectorsWindow);
        pushButton->setObjectName(QStringLiteral("pushButton"));

        horizontalLayout->addWidget(pushButton);


        verticalLayout->addLayout(horizontalLayout);


        retranslateUi(SectorsWindow);

        QMetaObject::connectSlotsByName(SectorsWindow);
    } // setupUi

    void retranslateUi(QDialog *SectorsWindow)
    {
        SectorsWindow->setWindowTitle(QApplication::translate("SectorsWindow", "\320\243\321\207\320\260\321\201\321\202\320\276\320\272", 0));
        label->setText(QApplication::translate("SectorsWindow", "\320\235\320\260\320\270\320\274\320\265\320\275\320\276\320\262\320\260\320\275\320\270\320\265 \321\203\321\207\320\260\321\201\321\202\320\272\320\260", 0));
        pushButton_2->setText(QApplication::translate("SectorsWindow", "\320\243\320\264\320\260\320\273\320\270\321\202\321\214", 0));
        pushButton->setText(QApplication::translate("SectorsWindow", "\320\237\321\200\320\270\320\275\321\217\321\202\321\214", 0));
    } // retranslateUi

};

namespace Ui {
    class SectorsWindow: public Ui_SectorsWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_SECTORSWINDOW_H
