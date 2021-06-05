#-------------------------------------------------
#
# Project created by QtCreator 2020-11-07T22:38:07
#
#-------------------------------------------------

QT       += core gui sql

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = MovingWorkersCeh2
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    database.cpp \
    sectorswindow.cpp \
    sector.cpp \
    positionswindow.cpp \
    position.cpp \
    user.cpp \
    userswindow.cpp \
    settingswindow.cpp \
    usersdismisswindow.cpp \
    abouttheprogram.cpp \
    reportpositionswindow.cpp

HEADERS  += mainwindow.h \
    database.h \
    sectorswindow.h \
    sector.h \
    positionswindow.h \
    position.h \
    user.h \
    userswindow.h \
    settingswindow.h \
    usersdismisswindow.h \
    abouttheprogram.h \
    reportpositionswindow.h

FORMS    += mainwindow.ui \
    sectorswindow.ui \
    positionswindow.ui \
    userswindow.ui \
    settingswindow.ui \
    usersdismisswindow.ui \
    abouttheprogram.ui \
    reportpositionswindow.ui
