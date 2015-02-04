/**
 * @brief cWorkTask.cpp
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#include "cWorkTask.h"
#include "iostream"
#include <sys/sysinfo.h>

CWorkTask::CWorkTask(WorkTaskFunc f, void* m_pArg):
m_task(f), m_pArg(m_pArg)
{
}

CWorkTask::CWorkTask():
m_task(NULL), m_pArg(NULL)
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
    if (m_task != NULL) {
        m_task(m_pArg);
    }
    return CWorkTask::WTER_OVER;
}
