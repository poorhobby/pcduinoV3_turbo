#ifndef CLOGGER_H
#define CLOGGER_H

#include <iostream>
#include <fstream>

#define __DDEBUG_LEVEL__ (CLogger::DDEBUG_DEBUG)

class CLogger
{
public:
    enum EDDebugLevel
    {
        DDEBUG_INFO,
        DDEBUG_DEBUG,
        DDEBUG_WARNING,
        DDEBUG_ERROR,
    };
    explicit CLogger();
    static CLogger &DInfo();
    static CLogger &DDebug();
    static CLogger &DError();
    static CLogger &DWarning();
private:
    static CLogger &GetLogger();
    std::fstream m_logger;
    EDDebugLevel m_level;
public:
    virtual ~CLogger()
    {
        m_logger.close();
    }
    template<typename T> inline CLogger &operator<<(T t)
    {
        m_logger << t;
        m_logger.flush();
        return *this;
    }
};

#define DDEBUG() \
    CLogger::DDebug() << __func__ << "-" << __LINE__ << ":"

#define DWARNING() \
    CLogger::DWarning() << __func__ << "-" << __LINE__ << ":"

#define DERROR() \
    CLogger::DError() << __func__ << "-" << __LINE__ << ":"

#endif // CLOGGER_H
