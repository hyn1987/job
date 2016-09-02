#include "common.h"
#include "mainwindow.h"
#include "ui_mainwindow.h"
#include <QDesktopWidget>
#include <QMenu>
#include <QCloseEvent>
#include "logindialog.h"
#include "shareddata.h"
#include <QJsonObject>
#include <QJsonArray>
#include "mnetworkmanager.h"
#include <QNetworkReply>
#include <QJsonObject>
#include <QJsonDocument>
#include <QMessageBox>
#include <QSettings>
#include <QTimer>
#include <QLibrary>
#include <QThread>
#include <QDir>
#include <QScreen>
#include <QStandardPaths>
#include <QCryptographicHash>

#define LOGOUT_API_URL  "/api/v1/logout"
#define SYNC_API_URL    "/api/v1/sync"
#define TIMELOG_API_URL    "/api/v1/timelog"

typedef void (*RegisterHookProc)();
typedef void (*UnregisterHookProc)();
typedef int (*GetKeyStrokeCountProc)();
typedef int (*GetMouseClickCountProc)();

RegisterHookProc RegisterHook = NULL;
UnregisterHookProc UnregisterHook = NULL;
GetKeyStrokeCountProc GetKeyStrokeCount = NULL;
GetMouseClickCountProc GetMouseClickCount = NULL;

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    isLogin = false;
    isTracking = false;
    timeOffset = 0;
    nextSnapTime = 0;
    isUploadingCache = false;

    createActions();
    createTrayIcon();
    ui->setupUi(this);
    ui->contractsCombo->view()->setTextElideMode(Qt::ElideRight);
    ui->contractsCombo->view()->setHorizontalScrollBarPolicy(Qt::ScrollBarAsNeeded);

    connect(trayIcon, SIGNAL(activated(QSystemTrayIcon::ActivationReason)),
            this, SLOT(iconActivated(QSystemTrayIcon::ActivationReason)));

    // Network Manager Config
    networkManager = new MNetworkManager(this);
    connect(networkManager, SIGNAL(finished(QNetworkReply*)),
            this, SLOT(handleNetworkData(QNetworkReply*)));
    // Timer Config
    timer = new QTimer();
    connect(timer, SIGNAL(timeout()), this, SLOT(trackEvent()));

    activities = new QJsonObject();

    // ---------- Windows ----------
    library = new QLibrary ("WawTracker.dll");
    if (library->load()) {
        RegisterHook = (RegisterHookProc)library->resolve("RegisterHook");
        UnregisterHook = (UnregisterHookProc)library->resolve("UnregisterHook");
        GetKeyStrokeCount = (GetKeyStrokeCountProc)library->resolve("GetKeyStrokeCount");
        GetMouseClickCount = (GetMouseClickCountProc)library->resolve("GetMouseClickCount");
    } else {
        QMessageLogger().debug() << library->errorString();
    }
    // ---------- ~Windows ----------

    QDir dir(QStandardPaths::writableLocation(QStandardPaths::DataLocation));
    if (!dir.exists()) {
        dir.mkpath(QStandardPaths::writableLocation(QStandardPaths::DataLocation));
    }
}


MainWindow::~MainWindow()
{
    if (UnregisterHook) {
        UnregisterHook();
    }

    timer->stop();
    library->unload();
    delete networkManager;
    delete timer;
    delete library;
    delete activities;
    delete ui;
}

SharedData* MainWindow::sharedData() {
    static SharedData* sharedData = NULL;
    if (sharedData == NULL) {
        sharedData = new SharedData();
    }
    return sharedData;
}

void MainWindow::showEvent(QShowEvent *) {
    QRect rect = QApplication::desktop()->availableGeometry();
    this->setGeometry(rect.width() - this->frameGeometry().width(),rect.height() - this->frameGeometry().height(),300,570);
}

void MainWindow::closeEvent(QCloseEvent *event) {
    if (trayIcon->isVisible()) {
        hide();
        event->ignore();
    }
}

void MainWindow::updateUserDataComponent() {
    QJsonObject* userData = MainWindow::sharedData()->userData();

    ui->usernameLabel->setText(userData->value("name").toString());
    QJsonArray contracts = userData->value("contracts").toArray();

    QMessageLogger().debug() << " Contracts " <<QJsonDocument(contracts).toJson();
    for (int i=0; i< contracts.size(); i++) {
        QJsonObject contract = contracts[i].toObject();
        QString strContract = contract.value("buyer").toString() + " - " + contract.value("title").toString();
        ui->contractsCombo->addItem(strContract, contract.value("id").toInt());
    }
}

void MainWindow::createActions() {
    startAction = new QAction(tr("&Start"), this);
    connect(startAction, SIGNAL(triggered()), this, SLOT(startTrack()));

    stopAction = new QAction(tr("S&top"), this);
    connect(stopAction, SIGNAL(triggered()), this, SLOT(stopTrack()));

    logoutAction = new QAction(tr("Log&out"), this);
    connect(logoutAction, SIGNAL(triggered()), this, SLOT(logout()));

    exitAction = new QAction(tr("E&xit"), this);
    connect(exitAction, SIGNAL(triggered()), qApp, SLOT(quit()));
}

void MainWindow::updateActionsStatus() {
    if (isLogin) {
        if (isTracking) {
            startAction->setEnabled(false);
            stopAction->setEnabled(true);
        } else {
            startAction->setEnabled(true);
            startAction->setEnabled(false);
        }
        logoutAction->setEnabled(true);
    } else {
        startAction->setEnabled(false);
        startAction->setEnabled(false);
        logoutAction->setEnabled(false);
    }
}

void MainWindow::createTrayIcon()
{
    trayIconMenu = new QMenu(this);
    trayIconMenu->addAction(startAction);
    trayIconMenu->addAction(stopAction);
    trayIconMenu->addAction(logoutAction);
    trayIconMenu->addSeparator();
    trayIconMenu->addAction(exitAction);

    trayIcon = new QSystemTrayIcon(QIcon(":/Resources/images/favicon.png"));
    trayIcon->setContextMenu(trayIconMenu);
    trayIcon->show();
}

void MainWindow::iconActivated(QSystemTrayIcon::ActivationReason reason) {
    switch (reason) {
    case QSystemTrayIcon::Trigger:
        if (this->isLogin) {
            if (this->isHidden()){
                this->show();
            } else {
                this->activateWindow();
            }
        }
        break;
    case QSystemTrayIcon::DoubleClick:
        if (this->isLogin && this->isHidden()) {
            this->show();
        }
        break;
    case QSystemTrayIcon::MiddleClick:
        break;
    default:
        ;
    }
}

void MainWindow::startTrack() {

    currentContractId = QString("%1").arg(ui->contractsCombo->currentData().toInt());
    ui->trackBtn->setText("Stop");
    isTracking = true;
    this->updateActionsStatus();
    ui->contractsCombo->setEnabled(false);

    QJsonObject params;
    params["token"] = MainWindow::sharedData()->token();
    networkManager->sendJWT(SYNC_API_URL, params, "get")->setObjectName("sync");

    if (RegisterHook) {
        RegisterHook();
    }
}

void MainWindow::stopTrack() {
    isTracking = false;
    ui->trackBtn->setText("Start");
    this->updateActionsStatus();
    ui->contractsCombo->setEnabled(true);

    timer->stop();

    if (UnregisterHook) {
        UnregisterHook();
    }
}

void MainWindow::logout() {
    stopTrack();
    QJsonObject params;
    params["token"] = MainWindow::sharedData()->token();
    networkManager->sendJWT(LOGOUT_API_URL, params, "get")->setObjectName("logout");

    QSettings settings("WawJob", "WawTracker");
    settings.remove("remember");
    settings.remove("token");
    settings.remove("username");

    // LogOut Process
    this->isLogin = false;
    this->hide();
    this->updateActionsStatus();

    // Show Login Dialog
    LoginDialog loginDlg;
    if (loginDlg.exec() == QDialog::Accepted){
        this->isLogin = true;
        this->show();
        this->updateActionsStatus();
        this->updateUserDataComponent();
    } else {
        exit(1);
    }
}

void MainWindow::trackBtnClicked() {
    if (isTracking) {
        stopTrack();
    } else {
        startTrack();
    }
}

void MainWindow::handleNetworkData(QNetworkReply *networkReply) {
    if (!networkReply->error()) {
        QByteArray response(networkReply->readAll());
        QJsonDocument jsonDoc = QJsonDocument::fromJson(response);

        QJsonObject result = jsonDoc.object();
        QMessageLogger().debug() <<networkReply->objectName();
        if (networkReply->objectName() == "logout") {
            if (result.value("error").toBool(true)) {
                QMessageBox msgBox;
                msgBox.setText(result["error"].toString());
                msgBox.exec();
                QMessageLogger().debug() << response;
            }
        } else if (networkReply->objectName() == "sync") {
            if (result.value("time") != QJsonValue::Undefined) {
                MainWindow::sharedData()->timestamp = (quint32)result.value("time").toDouble();
                timer->start(60000);
            } else {
                if (result.value("error") != QJsonValue::Undefined) {
                    QMessageBox msgBox;
                    msgBox.setText(result["error"].toString());
                    msgBox.exec();
                    QMessageLogger().debug() << response;
                }

                ui->trackBtn->setText("Start");
                isTracking = false;
                this->updateActionsStatus();
                ui->contractsCombo->setEnabled(true);
            }
        } else if (networkReply->objectName() == "timelog") {
            QJsonObject status = result.value("status").toObject();
            QStringList list = status.keys();
            bool success = false;
            int errorCode = 0;
            for (int i=0; i<list.count(); i++) {
                QString key = list.value(i);
                QJsonObject snap = status.value(key).toObject();
                if (snap.value("error").toInt() == 0) {
                    success = true;
                } else {
                    errorCode = snap.value("error").toInt();
                }
            }

            if (success || (errorCode == 2)) {
                trayIcon->showMessage("Screenshot", "Just uploaded screenshot and logs of keyboard and mouse.",QSystemTrayIcon::Information, 5000);
                QString imagePath = networkReply->property("imagePath").toString();
                QFile file(imagePath);
                file.remove();
                if (!isUploadingCache)
                    uploadCacheData();
            } else {
                quint32 timestamp = (quint32)networkReply->property("timestamp").toDouble();
                QString imagePath = networkReply->property("imagePath").toString();
                QByteArray jwt = networkReply->request().rawHeader("JWT");

                QSettings settings("WawJob", "WawTracker");
                settings.setValue("cache/" + QString("%1").arg(timestamp) + "/imagePath", imagePath);
                settings.setValue("cache/" + QString("%1").arg(timestamp) + "/jwt", jwt);
            }
            QFile data("logging.txt");
            if (data.open(QFile::WriteOnly | QFile::Append)) {
                QTextStream out(&data);
                out << response;
            }
            QMessageLogger().debug() << response;
        } else if (networkReply->objectName() == "timelog_cache") {
            QMessageLogger().debug() << response;
            QJsonObject status = result.value("status").toObject();
            QStringList list = status.keys();
            bool success = false;
            int errorCode = 0;
            for (int i=0; i<list.count(); i++) {
                QString key = list.value(i);
                QJsonObject snap = status.value(key).toObject();
                if (snap.value("error").toInt() == 0) {
                    success = true;
                } else {
                    errorCode = snap.value("error").toInt();
                }
            }

            if (success || (errorCode == 2)) {
                QString imagePath = networkReply->property("imagePath").toString();
                QFile file(imagePath);
                file.remove();

                QString timestamp = networkReply->property("timestamp").toString();
                QSettings settings("WawJob", "WawTracker");
                settings.remove("cache/"+timestamp);

                uploadCacheData();
            } else {
                isUploadingCache = false;
            }
        }
    } else {
        if (networkReply->objectName() == "sync") {
            stopTrack();
        } else if (networkReply->objectName() == "timelog") {
            quint32 timestamp = (quint32)networkReply->property("timestamp").toDouble();
            QString imagePath = networkReply->property("imagePath").toString();
            QByteArray jwt = networkReply->request().rawHeader("JWT");

            QSettings settings("WawJob", "WawTracker");
            settings.setValue("cache/" + QString("%1").arg(timestamp) + "/imagePath", imagePath);
            settings.setValue("cache/" + QString("%1").arg(timestamp) + "/jwt", jwt);
        }else if (networkReply->objectName() == "timelog_cache") {
            isUploadingCache = false;
        }else {
            QMessageBox msgBox;
            msgBox.setText(networkReply->errorString());
            msgBox.exec();
        }
        QMessageLogger().debug() <<networkReply->objectName() << networkReply->errorString();
        QFile data("logging.txt");
        if (data.open(QFile::WriteOnly | QFile::Append)) {
            QTextStream out(&data);
            out <<networkReply->objectName() << networkReply->errorString();
        }

    }

    networkReply->deleteLater();
}

void MainWindow::uploadCacheData() {
    QSettings settings("WawJob", "WawTracker");
    settings.beginGroup("cache");
    QStringList caches = settings.childGroups();
    if (caches.size() > 0) {
        QString timestamp = caches.first();
        settings.beginGroup(timestamp);
        QString imagePath = settings.value("imagePath").toString();

        QByteArray jwt = settings.value("jwt").toByteArray();
        QJsonObject params = QJsonDocument::fromJson(QByteArray::fromBase64(jwt.split('.').at(1))).object();
        params["token"] = MainWindow::sharedData()->token();
        QMessageLogger().debug() << params;
        isUploadingCache = true;
        networkManager->sendJWT(TIMELOG_API_URL, params, "post", imagePath, (quint32)timestamp.toLong())->setObjectName("timelog_cache");
        settings.endGroup();
    } else {
        isUploadingCache = false;
    }
    settings.endGroup();
}

void MainWindow::trackEvent() {
    double timestamp = MainWindow::sharedData()->timestamp;
    timeOffset += 60;
    int kCount = 0, mCount = 0;

    GetKeyStrokeCount = (GetKeyStrokeCountProc)library->resolve("GetKeyStrokeCount");
    if (GetKeyStrokeCount) {
        kCount = GetKeyStrokeCount();
    }

    GetMouseClickCount = (GetMouseClickCountProc)library->resolve("GetMouseClickCount");
    if (GetMouseClickCount) {
        mCount = GetMouseClickCount();
    }

    QDateTime datetime;
    datetime.setMSecsSinceEpoch((timestamp + timeOffset) * 1000);
    QString key = datetime.time().toString("hh:mm");

    QJsonObject act;
    act["k"] = kCount; act["m"] = mCount;
    activities->insert(key, act);

    if (timeOffset >= nextSnapTime ){
        QScreen *screen = QGuiApplication::primaryScreen();
        if (screen)
            originalPixmap = screen->grabWindow(0);
        ui->screenshotLabel->setPixmap(originalPixmap.scaled(ui->screenshotLabel->size(),
                                                             Qt::KeepAspectRatio,
                                                             Qt::SmoothTransformation));

        quint32 synctime = (timestamp + timeOffset);
        QString synctime_str = QString("%1").arg(synctime);
        QString filename = QCryptographicHash::hash(synctime_str.toUtf8(), QCryptographicHash::Md5).toHex() + ".png";
        QString path = QStandardPaths::writableLocation(QStandardPaths::DataLocation) + "/" + filename;

        QFile data("logging.txt");
        if (data.open(QFile::WriteOnly | QFile::Append)) {
            QTextStream out(&data);
            out << "Save Image:" << path <<"\n";
        }

        if (!originalPixmap.save(path, "PNG")) {
            QFile data("logging.txt");
            if (data.open(QFile::WriteOnly | QFile::Append)) {
                QTextStream out(&data);
                out << "Fail to save Image";
            }
        }

        QJsonObject params, logs, snapshot;
        snapshot["contract"] = currentContractId;
        snapshot["comment"] = ui->commentText->toPlainText();
        snapshot["active_window"] = "Test";
        snapshot["activities"] = *activities;

        logs.insert(QString("%1").arg(synctime), snapshot);
        params["token"] = MainWindow::sharedData()->token();
        params["logs"] = logs;

        networkManager->sendJWT(TIMELOG_API_URL, params, "post", path, synctime)->setObjectName("timelog");

        QDateTime datetime;
        datetime.setMSecsSinceEpoch((quint64)synctime * 1000);
        QMessageLogger().debug() << " Take Snapshot - " <<datetime.toString();
        QMessageLogger().debug() << QJsonDocument(*activities).toJson();
        int mins = datetime.time().minute();
        quint32 offset = (((mins / 10) + 1) * 10 + (qrand() % 9)) - mins;
        nextSnapTime = timeOffset + (offset * 60);
//        nextSnapTime = timeOffset + 60;

        QStringList keys = activities->keys();
        for (int i=0; i<keys.count(); i++) {
            activities->remove(keys.at(i));
        }
    }
}
