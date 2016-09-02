#ifndef MNETWORKMANAGER_H
#define MNETWORKMANAGER_H

#include <QNetworkAccessManager>

class QJsonObject;

class MNetworkManager : public QNetworkAccessManager
{
    Q_OBJECT
public:
    explicit MNetworkManager(QObject *parent = 0);

    QNetworkReply* sendJWT(const QString url, QJsonObject &params, QString method );
    QNetworkReply* sendJWT(const QString url, QJsonObject &params, QString method, const QString snapshotUrl, quint32 timestamp);

signals:

public slots:

};

#endif // MNETWORKMANAGER_H
