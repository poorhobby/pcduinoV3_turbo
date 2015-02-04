/**
 * @brief cWorkTask.cpp
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#include "cWorkTask.h"
#include "iostream"
#include <sys/sysinfo.h>

CWorkTask::CWorkTask():
m_task(NULL), m_pArg(NULL), m_count(1), m_timeout(0)
{
}

CWorkTask::~CWorkTask()
{
}

static long get_up_time()
{
    struct sysinfo info;

    sysinfo(&info);
    return info.uptime;
}

CWorkTask::EWorkTaskExecResult CWorkTask::exec_task()
{
    if (m_timeout != 0) {
        long upTime = get_up_time();
        if (upTime < m_timeout) {
            return CWorkTask::WTER_TIME_NOT_ARRIVE;
        }
    }
    if (m_task != NULL) {
        m_task(m_pArg);
    }
    if (m_count == 0 || --m_count > 0) {
        return CWorkTask::WTER_AGAIN;
    } else {
        return CWorkTask::WTER_SUCCESS;
    }
}
