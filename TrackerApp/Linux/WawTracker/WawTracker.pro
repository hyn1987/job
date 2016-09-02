#-------------------------------------------------
#
# Project created by QtCreator 2016-03-30T10:56:49
#
#-------------------------------------------------

QT       += core gui

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets network

TARGET = WawTracker
TEMPLATE = app
QTPLUGIN += qjpeg    # image formats

SOURCES += main.cpp\
        mainwindow.cpp \
    logindialog.cpp \
    shareddata.cpp \
    mnetworkmanager.cpp

HEADERS  += mainwindow.h \
    logindialog.h \
    common.h \
    shareddata.h \
    mnetworkmanager.h \
    windows.h

FORMS    += mainwindow.ui \
    logindialog.ui

RC_FILE = Resources/app.rc

RESOURCES += \
    resource.qrc

OTHER_FILES +=
