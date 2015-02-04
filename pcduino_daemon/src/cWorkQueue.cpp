/**
 * @brief cWorkQueue.cpp
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#include "cWorkQueue.h"

CWorkQueue::CWorkQueue()
{
    // TODO Auto-generated constructor stub

}

CWorkQueue::~CWorkQueue()
{
    // TODO Auto-generated destructor stub
}

/**
 * @brief register a work task to this queue
 * @param task a descriptor of CWorkTask
 * @return bool true for success, and false for failed.
 */
bool CWorkQueue::register_work_task(CWorkTask task)
{
    m_queue.push_back(task);
    return true;
}

bool CWorkQueue::on_schedule()
{
    CWorkTask task;
//    while(m_queue)
    task = m_queue.pop_front();
    CWorkTask::EWorkTaskExecResult result;
    result = task.exec_task();
    switch (result)
    {
    case CWorkTask::WTER_AGAIN:
    case CWorkTask::WTER_TIME_NOT_ARRIVE:
        m_queue.push_back(task);
        break;
    case CWorkTask::WTER_SUCCESS:
        break;
    default:
        break;
    }
    return true;
}
