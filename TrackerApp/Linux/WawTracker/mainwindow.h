#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>

#include <QSystemTrayIcon>

class QMenu;
class SharedData;
class MNetworkManager;
class QNetworkReply;
class QTimer;
class QLibrary;

namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();

    bool isLogin;
    static SharedData* sharedData();

    void updateUserDataComponent();

protected:
    void showEvent(QShowEvent *event);
    void closeEvent(QCloseEvent *event);

private slots:
    void iconActivated(QSystemTrayIcon::ActivationReason reason);

    void startTrack();
    void stopTrack();
    void logout();

    void handleNetworkData(QNetworkReply* reply);

    void trackBtnClicked();
    void trackEvent();

private:
    void createActions();
    void updateActionsStatus();

    void createTrayIcon();
    void uploadCacheData();

    Ui::MainWindow *ui;

    QSystemTrayIcon *trayIcon;
    QMenu *trayIconMenu;

    QAction *startAction;
    QAction *stopAction;
    QAction *logoutAction;
    QAction *exitAction;

    bool isTracking;

    MNetworkManager* networkManager;
    QTimer* timer;

    quint32 timeOffset;
    quint32 nextSnapTime;

    QString currentContractId;

    QPixmap originalPixmap;

    QJsonObject* activities;

    // Windows WawTracker.dll
    QLibrary* library;

    bool isUploadingCache;
};

#endif // MAINWINDOW_H
