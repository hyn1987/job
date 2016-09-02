#ifndef LOGINDIALOG_H
#define LOGINDIALOG_H

#include <QDialog>

class MNetworkManager;
class QNetworkReply;

namespace Ui {
class LoginDialog;
}

class LoginDialog : public QDialog
{
    Q_OBJECT

public:
    explicit LoginDialog(QWidget *parent = 0);
    ~LoginDialog();

private slots:
    void onLoginBtnClicked();
    void handleNetworkData(QNetworkReply* reply);

protected:
    void showEvent(QShowEvent *);

private:
    MNetworkManager* networkManager;

    Ui::LoginDialog *ui;
};

#endif // LOGINDIALOG_H
