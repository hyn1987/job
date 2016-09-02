#include "shareddata.h"
#include <QJsonObject>

SharedData::SharedData()
{
    _userData = NULL;
}

QJsonObject* SharedData::userData() {
    return _userData;
}

void SharedData::setUserData(QJsonObject &userData) {
    if (_userData != NULL) {
        delete _userData;
    }
    _userData = new QJsonObject(userData);
}

QString SharedData::token() {
    if (_userData != NULL) {
        return _userData->value("token").toString();
    }
    return QString();
}
