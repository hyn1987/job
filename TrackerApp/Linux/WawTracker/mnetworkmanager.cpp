#include "mnetworkmanager.h"
#include <QJsonObject>
#include <QJsonDocument>
#include <QMessageAuthenticationCode>
#include <QNetworkRequest>
#include <QNetworkReply>
#include <QtNetwork/QHttpPart>
#include <QtNetwork/QHttpMultiPart>
#include <QFile>

#define SECRET "ePOIUmH$jABFUXd#3G~~dUviV!gNd15P"
#define APIHOST "http://www.wawjob.com"
//#define APIHOST "http://100.100.7.101"

MNetworkManager::MNetworkManager(QObject *parent) :
    QNetworkAccessManager(parent)
{
}

QNetworkReply* MNetworkManager::sendJWT(const QString url, QJsonObject &params, QString method ) {
    QJsonObject header;
    header["typ"] = "JWT";
    header["alg"] = "sha256";

    QByteArray encode;
    encode.append(QJsonDocument(header).toJson().toBase64(QByteArray::Base64Encoding));
    encode.append(".");
    encode.append(QJsonDocument(params).toJson().toBase64(QByteArray::Base64Encoding));
    QByteArray signature = QMessageAuthenticationCode::hash(encode, QByteArray(SECRET), QCryptographicHash::Sha256).toHex();

    QByteArray jwt;
    jwt.append(encode);
    jwt.append(".");
    jwt.append(signature);

    QString apiUrl = APIHOST;
    apiUrl.append(url);

    QNetworkRequest request;
    request.setUrl(QUrl(apiUrl));
    request.setRawHeader("JWT", jwt);

    QHttpMultiPart *multiPart = new QHttpMultiPart(QHttpMultiPart::FormDataType);
    QNetworkReply* reply;

    if (method == "post") {
        reply = this->post(request,multiPart);
    } else {
        reply = this->get(request);
    }
    multiPart->setParent(reply);

    return reply;
}

QNetworkReply* MNetworkManager::sendJWT(const QString url, QJsonObject &params, QString method, const QString snapshotUrl, quint32 timestamp) {
    QJsonObject header;
    header["typ"] = "JWT";
    header["alg"] = "sha256";

    QByteArray encode;
    encode.append(QJsonDocument(header).toJson().toBase64(QByteArray::Base64Encoding));
    encode.append(".");
    encode.append(QJsonDocument(params).toJson().toBase64(QByteArray::Base64Encoding));
    QByteArray signature = QMessageAuthenticationCode::hash(encode, QByteArray(SECRET), QCryptographicHash::Sha256).toHex();

    QByteArray jwt;
    jwt.append(encode);
    jwt.append(".");
    jwt.append(signature);

    QString apiUrl = APIHOST;
    apiUrl.append(url);

    QNetworkRequest request;
    request.setUrl(QUrl(apiUrl));
    request.setRawHeader("JWT", jwt);

    QNetworkReply* reply;
    QHttpMultiPart *multiPart = new QHttpMultiPart(QHttpMultiPart::FormDataType);

    QHttpPart imagePart;
    imagePart.setHeader(QNetworkRequest::ContentTypeHeader, QVariant("image/png"));
    imagePart.setHeader(QNetworkRequest::ContentDispositionHeader, QVariant("form-data; name=\"screenshot_" + QString("%1").arg(timestamp) + "\"; filename=\"screenshot.jpg\""));
    QFile *file = new QFile(snapshotUrl);
    file->open(QIODevice::ReadOnly);
    imagePart.setBodyDevice(file);
    file->setParent(multiPart); // we cannot delete the file now, so delete it with the multiPart
    multiPart->append(imagePart);

    if (method == "post") {
        reply = this->post(request, multiPart);
    } else {
        reply = this->get(request);
    }
    multiPart->setParent(reply);
    reply->setProperty("imagePath", snapshotUrl);
    reply->setProperty("timestamp", timestamp);
    return reply;
}
