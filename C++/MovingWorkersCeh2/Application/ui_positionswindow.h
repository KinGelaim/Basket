/********************************************************************************
** Form generated from reading UI file 'positionswindow.ui'
**
** Created: Wed Nov 25 20:50:55 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_POSITIONSWINDOW_H
#define UI_POSITIONSWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QComboBox>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_PositionsWindow
{
public:
    QVBoxLayout *verticalLayout;
    QHBoxLayout *horizontalLayout;
    QLabel *label;
    QLineEdit *lineEdit;
    QHBoxLayout *horizontalLayout_2;
    QLabel *label_2;
    QComboBox *comboBox;
    QHBoxLayout *horizontalLayout_3;
    QPushButton *pushButton_2;
    QSpacerItem *horizontalSpacer;
    QPushButton *pushButton;

    void setupUi(QDialog *PositionsWindow)
    {
        if (PositionsWindow->objectName().isEmpty())
            PositionsWindow->setObjectName(QStringLiteral("PositionsWindow"));
        PositionsWindow->resize(400, 154);
        verticalLayout = new QVBoxLayout(PositionsWindow);
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setObjectName(QStringLiteral("horizontalLayout"));
        label = new QLabel(PositionsWindow);
        label->setObjectName(QStringLiteral("label"));

        horizontalLayout->addWidget(label);

        lineEdit = new QLineEdit(PositionsWindow);
        lineEdit->setObjectName(QStringLiteral("lineEdit"));

        horizontalLayout->addWidget(lineEdit);


        verticalLayout->addLayout(horizontalLayout);

        horizontalLayout_2 = new QHBoxLayout();
        horizontalLayout_2->setObjectName(QStringLiteral("horizontalLayout_2"));
        label_2 = new QLabel(PositionsWindow);
        label_2->setObjectName(QStringLiteral("label_2"));

        horizontalLayout_2->addWidget(label_2);

        comboBox = new QComboBox(PositionsWindow);
        comboBox->setObjectName(QStringLiteral("comboBox"));

        horizontalLayout_2->addWidget(comboBox);


        verticalLayout->addLayout(horizontalLayout_2);

        horizontalLayout_3 = new QHBoxLayout();
        horizontalLayout_3->setObjectName(QStringLiteral("horizontalLayout_3"));
        pushButton_2 = new QPushButton(PositionsWindow);
        pushButton_2->setObjectName(QStringLiteral("pushButton_2"));
        pushButton_2->setStyleSheet(QLatin1String("QPushButton {\n"
"	background-color: #ff002d;\n"
"	color: white;\n"
"}"));

        horizontalLayout_3->addWidget(pushButton_2);

        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout_3->addItem(horizontalSpacer);

        pushButton = new QPushButton(PositionsWindow);
        pushButton->setObjectName(QStringLiteral("pushButton"));

        horizontalLayout_3->addWidget(pushButton);


        verticalLayout->addLayout(horizontalLayout_3);


        retranslateUi(PositionsWindow);

        QMetaObject::connectSlotsByName(PositionsWindow);
    } // setupUi

    void retranslateUi(QDialog *PositionsWindow)
    {
        PositionsWindow->setWindowTitle(QApplication::translate("PositionsWindow", "\320\224\320\276\320\273\320\266\320\275\320\276\321\201\321\202\321\214", 0));
        label->setText(QApplication::translate("PositionsWindow", "\320\224\320\276\320\273\320\266\320\275\320\276\321\201\321\202\321\214", 0));
        label_2->setText(QApplication::translate("PositionsWindow", "\320\243\321\207\320\260\321\201\321\202\320\276\320\272", 0));
        pushButton_2->setText(QApplication::translate("PositionsWindow", "\320\243\320\264\320\260\320\273\320\270\321\202\321\214", 0));
        pushButton->setText(QApplication::translate("PositionsWindow", "\320\237\321\200\320\270\320\275\321\217\321\202\321\214", 0));
    } // retranslateUi

};

namespace Ui {
    class PositionsWindow: public Ui_PositionsWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_POSITIONSWINDOW_H
