#include "logindialog.h"
#include "ui_logindialog.h"
#include "common.h"
#include "mainwindow.h"
#include "shareddata.h"
#include "mnetworkmanager.h"
#include <QApplication>
#include <QNetworkReply>
#include <QDesktopWidget>
#include <QJsonDocument>
#include <QJsonObject>
#include <QMessageBox>
#include <QUrl>
#include <QSettings>

#define LOGIN_API_URL "/api/v1/login"
#define VALID_API_URL "/api/v1/valid"

LoginDialog::LoginDialog(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::LoginDialog)
{
    ui->setupUi(this);

    networkManager = new MNetworkManager(this);
    connect(networkManager, SIGNAL(finished(QNetworkReply*)),
            this, SLOT(handleNetworkData(QNetworkReply*)));

    QSettings settings("WawJob", "WawTracker");
    if (settings.value("remember").toBool()) {
        ui->loginEdit->setText(settings.value("username","").toString());
        ui->passwordEdit->setText("********");
        ui->rememberChk->setChecked(true);

        QJsonObject params;
        params["token"] = settings.value("token").toString();
        QMessageLogger().debug() << settings.value("token").toString();

        networkManager->sendJWT(VALID_API_URL, params, "get")->setObjectName("valid");
        this->setEnabled(false);
    }
}

LoginDialog::~LoginDialog()
{
    delete networkManager;
    delete ui;
}

void LoginDialog::showEvent(QShowEvent *) {
    QRect rect = QApplication::desktop()->availableGeometry();
    this->setGeometry(rect.width() - this->frameGeometry().width(),rect.height() - this->frameGeometry().height(),300,570);
}

void LoginDialog::onLoginBtnClicked()
{
    QString username = ui->loginEdit->text();
    QString password = ui->passwordEdit->text();

    QJsonObject params;
    params["username"] = username;
    params["password"] = password;

    networkManager->sendJWT(LOGIN_API_URL, params, "post")->setObjectName("login");
    this->setEnabled(false);
}

void LoginDialog::handleNetworkData(QNetworkReply *networkReply) {
    if (!networkReply->error()) {
        QByteArray response(networkReply->readAll());
        QJsonDocument jsonDoc = QJsonDocument::fromJson(response);

        QJsonObject result = jsonDoc.object();

        QMessageLogger().debug() << networkReply->objectName();
        this->setEnabled(true);

        if (networkReply->objectName() == "login") {
            if (result.value("error") != QJsonValue::Undefined) {
                QMessageBox msgBox;
                msgBox.setText(result["error"].toString());
                msgBox.exec();
                QMessageLogger().debug() << response;
                ui->passwordEdit->setText("");
            } else {
                MainWindow::sharedData()->setUserData(result);

                QMessageLogger().debug() << result.value("token").toString();
                QSettings settings("WawJob", "WawTracker");
                if (ui->rememberChk->isChecked()) {
                    settings.setValue("remember", true);
                } else {
                    settings.setValue("remember", false);
                }
                settings.setValue("username", ui->loginEdit->text());
                settings.setValue("token", MainWindow::sharedData()->token());
                this->accept();
            }
        } else if (networkReply->objectName() == "valid"){
            if (result.value("error") != QJsonValue::Undefined) {
                QMessageBox msgBox;
                msgBox.setText(result["error"].toString());
                msgBox.exec();
                QMessageLogger().debug() << response;
                ui->passwordEdit->setText("");
            } else {
                QSettings settings("WawJob", "WawTracker");
                QString token = settings.value("token").toString();
                result["token"] = token;
                MainWindow::sharedData()->setUserData(result);
                this->accept();
            }
        }
    } else {
        this->setEnabled(true);

        QMessageBox msgBox;
        msgBox.setText(networkReply->errorString());
        msgBox.exec();
        QMessageLogger().debug() << networkReply->errorString();
    }

    networkReply->deleteLater();
}

