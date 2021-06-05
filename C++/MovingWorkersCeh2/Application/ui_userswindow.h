/********************************************************************************
** Form generated from reading UI file 'userswindow.ui'
**
** Created: Sun Dec 6 19:02:22 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_USERSWINDOW_H
#define UI_USERSWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QCheckBox>
#include <QtWidgets/QComboBox>
#include <QtWidgets/QDateEdit>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QLabel>
#include <QtWidgets/QLineEdit>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_UsersWindow
{
public:
    QVBoxLayout *verticalLayout_3;
    QHBoxLayout *horizontalLayout;
    QLabel *label;
    QLineEdit *lineEdit;
    QHBoxLayout *horizontalLayout_2;
    QLabel *label_2;
    QLineEdit *lineEdit_2;
    QHBoxLayout *horizontalLayout_3;
    QLabel *label_3;
    QLineEdit *lineEdit_3;
    QHBoxLayout *horizontalLayout_4;
    QLabel *label_4;
    QComboBox *comboBox;
    QHBoxLayout *horizontalLayout_5;
    QLabel *label_5;
    QComboBox *comboBox_2;
    QHBoxLayout *horizontalLayout_6;
    QVBoxLayout *verticalLayout;
    QSpacerItem *verticalSpacer_2;
    QLabel *label_6;
    QDateEdit *dateEdit;
    QVBoxLayout *verticalLayout_2;
    QCheckBox *checkBox;
    QSpacerItem *verticalSpacer;
    QLabel *label_7;
    QDateEdit *dateEdit_2;
    QHBoxLayout *horizontalLayout_7;
    QPushButton *pushButton_2;
    QSpacerItem *horizontalSpacer;
    QPushButton *pushButton;

    void setupUi(QDialog *UsersWindow)
    {
        if (UsersWindow->objectName().isEmpty())
            UsersWindow->setObjectName(QStringLiteral("UsersWindow"));
        UsersWindow->resize(400, 322);
        verticalLayout_3 = new QVBoxLayout(UsersWindow);
        verticalLayout_3->setObjectName(QStringLiteral("verticalLayout_3"));
        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setObjectName(QStringLiteral("horizontalLayout"));
        label = new QLabel(UsersWindow);
        label->setObjectName(QStringLiteral("label"));

        horizontalLayout->addWidget(label);

        lineEdit = new QLineEdit(UsersWindow);
        lineEdit->setObjectName(QStringLiteral("lineEdit"));

        horizontalLayout->addWidget(lineEdit);


        verticalLayout_3->addLayout(horizontalLayout);

        horizontalLayout_2 = new QHBoxLayout();
        horizontalLayout_2->setObjectName(QStringLiteral("horizontalLayout_2"));
        label_2 = new QLabel(UsersWindow);
        label_2->setObjectName(QStringLiteral("label_2"));

        horizontalLayout_2->addWidget(label_2);

        lineEdit_2 = new QLineEdit(UsersWindow);
        lineEdit_2->setObjectName(QStringLiteral("lineEdit_2"));

        horizontalLayout_2->addWidget(lineEdit_2);


        verticalLayout_3->addLayout(horizontalLayout_2);

        horizontalLayout_3 = new QHBoxLayout();
        horizontalLayout_3->setObjectName(QStringLiteral("horizontalLayout_3"));
        label_3 = new QLabel(UsersWindow);
        label_3->setObjectName(QStringLiteral("label_3"));

        horizontalLayout_3->addWidget(label_3);

        lineEdit_3 = new QLineEdit(UsersWindow);
        lineEdit_3->setObjectName(QStringLiteral("lineEdit_3"));

        horizontalLayout_3->addWidget(lineEdit_3);


        verticalLayout_3->addLayout(horizontalLayout_3);

        horizontalLayout_4 = new QHBoxLayout();
        horizontalLayout_4->setObjectName(QStringLiteral("horizontalLayout_4"));
        label_4 = new QLabel(UsersWindow);
        label_4->setObjectName(QStringLiteral("label_4"));

        horizontalLayout_4->addWidget(label_4);

        comboBox = new QComboBox(UsersWindow);
        comboBox->setObjectName(QStringLiteral("comboBox"));

        horizontalLayout_4->addWidget(comboBox);


        verticalLayout_3->addLayout(horizontalLayout_4);

        horizontalLayout_5 = new QHBoxLayout();
        horizontalLayout_5->setObjectName(QStringLiteral("horizontalLayout_5"));
        label_5 = new QLabel(UsersWindow);
        label_5->setObjectName(QStringLiteral("label_5"));

        horizontalLayout_5->addWidget(label_5);

        comboBox_2 = new QComboBox(UsersWindow);
        comboBox_2->setObjectName(QStringLiteral("comboBox_2"));

        horizontalLayout_5->addWidget(comboBox_2);


        verticalLayout_3->addLayout(horizontalLayout_5);

        horizontalLayout_6 = new QHBoxLayout();
        horizontalLayout_6->setObjectName(QStringLiteral("horizontalLayout_6"));
        verticalLayout = new QVBoxLayout();
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        verticalSpacer_2 = new QSpacerItem(20, 40, QSizePolicy::Minimum, QSizePolicy::Expanding);

        verticalLayout->addItem(verticalSpacer_2);

        label_6 = new QLabel(UsersWindow);
        label_6->setObjectName(QStringLiteral("label_6"));

        verticalLayout->addWidget(label_6);

        dateEdit = new QDateEdit(UsersWindow);
        dateEdit->setObjectName(QStringLiteral("dateEdit"));

        verticalLayout->addWidget(dateEdit);


        horizontalLayout_6->addLayout(verticalLayout);

        verticalLayout_2 = new QVBoxLayout();
        verticalLayout_2->setObjectName(QStringLiteral("verticalLayout_2"));
        checkBox = new QCheckBox(UsersWindow);
        checkBox->setObjectName(QStringLiteral("checkBox"));

        verticalLayout_2->addWidget(checkBox);

        verticalSpacer = new QSpacerItem(20, 40, QSizePolicy::Minimum, QSizePolicy::Expanding);

        verticalLayout_2->addItem(verticalSpacer);

        label_7 = new QLabel(UsersWindow);
        label_7->setObjectName(QStringLiteral("label_7"));

        verticalLayout_2->addWidget(label_7);

        dateEdit_2 = new QDateEdit(UsersWindow);
        dateEdit_2->setObjectName(QStringLiteral("dateEdit_2"));

        verticalLayout_2->addWidget(dateEdit_2);


        horizontalLayout_6->addLayout(verticalLayout_2);


        verticalLayout_3->addLayout(horizontalLayout_6);

        horizontalLayout_7 = new QHBoxLayout();
        horizontalLayout_7->setObjectName(QStringLiteral("horizontalLayout_7"));
        pushButton_2 = new QPushButton(UsersWindow);
        pushButton_2->setObjectName(QStringLiteral("pushButton_2"));
        pushButton_2->setAutoFillBackground(false);
        pushButton_2->setStyleSheet(QLatin1String("QPushButton {\n"
"	background-color: #ff002d;\n"
"	color: white;\n"
"}"));

        horizontalLayout_7->addWidget(pushButton_2);

        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout_7->addItem(horizontalSpacer);

        pushButton = new QPushButton(UsersWindow);
        pushButton->setObjectName(QStringLiteral("pushButton"));

        horizontalLayout_7->addWidget(pushButton);


        verticalLayout_3->addLayout(horizontalLayout_7);


        retranslateUi(UsersWindow);

        QMetaObject::connectSlotsByName(UsersWindow);
    } // setupUi

    void retranslateUi(QDialog *UsersWindow)
    {
        UsersWindow->setWindowTitle(QApplication::translate("UsersWindow", "\320\241\320\276\321\202\321\200\321\203\320\264\320\275\320\270\320\272", 0));
        label->setText(QApplication::translate("UsersWindow", "\320\244\320\260\320\274\320\270\320\273\320\270\321\217", 0));
        label_2->setText(QApplication::translate("UsersWindow", "\320\230\320\274\321\217", 0));
        label_3->setText(QApplication::translate("UsersWindow", "\320\236\321\202\321\207\320\265\321\201\321\202\320\262\320\276", 0));
        label_4->setText(QApplication::translate("UsersWindow", "\320\243\321\207\320\260\321\201\321\202\320\276\320\272", 0));
        label_5->setText(QApplication::translate("UsersWindow", "\320\224\320\276\320\273\320\266\320\275\320\276\321\201\321\202\321\214", 0));
        label_6->setText(QApplication::translate("UsersWindow", "\320\224\320\260\321\202\320\260 \320\277\321\200\320\270\320\275\321\217\321\202\320\270\321\217", 0));
        dateEdit->setDisplayFormat(QApplication::translate("UsersWindow", "yyyy-MM-dd", 0));
        checkBox->setText(QApplication::translate("UsersWindow", "\320\243\320\262\320\276\320\273\320\265\320\275", 0));
        label_7->setText(QApplication::translate("UsersWindow", "\320\224\320\260\321\202\320\260 \321\203\320\262\320\276\320\273\321\214\320\275\320\265\320\275\320\270\321\217", 0));
        dateEdit_2->setDisplayFormat(QApplication::translate("UsersWindow", "yyyy-MM-dd", 0));
        pushButton_2->setText(QApplication::translate("UsersWindow", "\320\243\320\264\320\260\320\273\320\270\321\202\321\214", 0));
        pushButton->setText(QApplication::translate("UsersWindow", "\320\237\321\200\320\270\320\275\321\217\321\202\321\214", 0));
    } // retranslateUi

};

namespace Ui {
    class UsersWindow: public Ui_UsersWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_USERSWINDOW_H
