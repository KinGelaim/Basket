/********************************************************************************
** Form generated from reading UI file 'usersdismisswindow.ui'
**
** Created: Thu Nov 19 18:17:04 2020
**      by: Qt User Interface Compiler version 5.0.0
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_USERSDISMISSWINDOW_H
#define UI_USERSDISMISSWINDOW_H

#include <QtCore/QVariant>
#include <QtWidgets/QAction>
#include <QtWidgets/QApplication>
#include <QtWidgets/QButtonGroup>
#include <QtWidgets/QDialog>
#include <QtWidgets/QHBoxLayout>
#include <QtWidgets/QHeaderView>
#include <QtWidgets/QPushButton>
#include <QtWidgets/QSpacerItem>
#include <QtWidgets/QTableView>
#include <QtWidgets/QVBoxLayout>

QT_BEGIN_NAMESPACE

class Ui_UsersDismissWindow
{
public:
    QVBoxLayout *verticalLayout;
    QTableView *tableView;
    QHBoxLayout *horizontalLayout;
    QSpacerItem *horizontalSpacer;
    QPushButton *pushButton;

    void setupUi(QDialog *UsersDismissWindow)
    {
        if (UsersDismissWindow->objectName().isEmpty())
            UsersDismissWindow->setObjectName(QStringLiteral("UsersDismissWindow"));
        UsersDismissWindow->resize(700, 400);
        verticalLayout = new QVBoxLayout(UsersDismissWindow);
        verticalLayout->setObjectName(QStringLiteral("verticalLayout"));
        tableView = new QTableView(UsersDismissWindow);
        tableView->setObjectName(QStringLiteral("tableView"));
        tableView->setEditTriggers(QAbstractItemView::NoEditTriggers);
        tableView->setCornerButtonEnabled(false);

        verticalLayout->addWidget(tableView);

        horizontalLayout = new QHBoxLayout();
        horizontalLayout->setObjectName(QStringLiteral("horizontalLayout"));
        horizontalSpacer = new QSpacerItem(40, 20, QSizePolicy::Expanding, QSizePolicy::Minimum);

        horizontalLayout->addItem(horizontalSpacer);

        pushButton = new QPushButton(UsersDismissWindow);
        pushButton->setObjectName(QStringLiteral("pushButton"));

        horizontalLayout->addWidget(pushButton);


        verticalLayout->addLayout(horizontalLayout);


        retranslateUi(UsersDismissWindow);

        QMetaObject::connectSlotsByName(UsersDismissWindow);
    } // setupUi

    void retranslateUi(QDialog *UsersDismissWindow)
    {
        UsersDismissWindow->setWindowTitle(QApplication::translate("UsersDismissWindow", "\320\243\320\262\320\276\320\273\320\265\320\275\320\275\321\213\320\265 \321\201\320\276\321\202\321\200\321\203\320\264\320\275\320\270\320\272\320\270", 0));
        pushButton->setText(QApplication::translate("UsersDismissWindow", "\320\237\321\200\320\270\320\275\321\217\321\202\321\214", 0));
    } // retranslateUi

};

namespace Ui {
    class UsersDismissWindow: public Ui_UsersDismissWindow {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_USERSDISMISSWINDOW_H
