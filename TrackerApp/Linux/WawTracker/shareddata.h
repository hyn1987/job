#ifndef SHAREDDATA_H
#define SHAREDDATA_H

class QJsonObject;
class QString;

class SharedData
{
public:
    SharedData();

    QJsonObject* userData();
    void setUserData(QJsonObject& userData);

    QString token();

    double timestamp;

    QJsonObject* cacheData;
private:
    QJsonObject* _userData;
};

#endif // SHAREDDATA_H
