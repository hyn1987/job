#include <QApplication>
#include <QDesktopWidget>
#include "mainwindow.h"
#include "logindialog.h"
#include <QSystemTrayIcon>
#include <QMessageBox>
#include <QTime>


int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    QApplication::setOrganizationName("WawJob");
    QApplication::setOrganizationDomain("wawjob.com");
    QApplication::setApplicationName("WawTracker");

    qsrand(QTime(0, 0, 0).secsTo(QTime::currentTime()));

    if (!QSystemTrayIcon::isSystemTrayAvailable()) {
        QMessageBox::critical(0, QObject::tr("WawJob"),
                              QObject::tr("I couldn't detect any system tray "
                                          "on this system."));
        return 1;
    }
    QApplication::setQuitOnLastWindowClosed(false);

    MainWindow w;
    LoginDialog loginDlg;

    if (loginDlg.exec() == QDialog::Accepted){
        w.isLogin = true;
        w.show();
        w.updateUserDataComponent();
    } else {
        exit(1);
    }
    return a.exec();
}
