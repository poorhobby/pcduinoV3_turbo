#include "cLogger.h"
#include <iostream>
#include "time.h"
#include "string.h"

#define INSERT_CURRENT_TIME_TO_LOG(__level__)  \
    do { \
        time_t curTime; \
        ::time(&curTime); \
        char *tStr = ::ctime(&curTime); \
        char *newTimeStr = new char[strlen(tStr)]; \
        if (newTimeStr == NULL) { \
            logger << __func__ << "memory alloc failed."; \
            return logger; \
        } \
        strncpy(newTimeStr, tStr, strlen(tStr)); \
        newTimeStr[strlen(tStr)-1] = '\0'; \
        logger << "[" << newTimeStr << "---" << __level__ << "]"; \
        delete[] newTimeStr; \
    } while(0)

CLogger::CLogger()
{
    // generate file name of the log file.
    std::fstream command;
    char filename[32];
    
    command.open("/proc/self/comm", std::ios_base::in);
    command.getline(filename, 24);
    command.close();
    
    strncat(filename, ".log", 8);
    
    //std::cout << filename << std::endl;
    
    m_logger.open(filename, std::ios_base::in | std::ios_base::out | std::ios_base::trunc);
    //m_logger << "# This is a log file for SimBrowser.";

    m_level = __DDEBUG_LEVEL__;
}

CLogger &CLogger::GetLogger()
{
    static CLogger logger;
    return logger;
}

CLogger &CLogger::DInfo()
{
    CLogger& logger = CLogger::GetLogger();
    //jump to next line.
    logger << "\n";

    INSERT_CURRENT_TIME_TO_LOG("INFO");

    return logger;
}

CLogger &CLogger::DDebug()
{
    CLogger& logger = CLogger::GetLogger();
    //jump to next line.
    logger << "\n";
    
    INSERT_CURRENT_TIME_TO_LOG("DEBUG");
    
    return logger;
}

CLogger &CLogger::DError()
{
    CLogger& logger = CLogger::GetLogger();
    //jump to next line.
    logger << "\n";
    
    INSERT_CURRENT_TIME_TO_LOG("ERROR");
    
    return logger;
}

CLogger &CLogger::DWarning()
{
    CLogger& logger = CLogger::GetLogger();
    //jump to next line.
    logger << "\n";
    
    INSERT_CURRENT_TIME_TO_LOG("WARNING");
    
    return logger;
}
