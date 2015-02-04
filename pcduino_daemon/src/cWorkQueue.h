/**
 * @brief cWorkQueue.h
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#ifndef CWORKQUEUE_H_
#define CWORKQUEUE_H_

#include "cWorkTask.h"
#include "list"
class CWorkQueue
{
public:
    enum EWorkQueuePriority
    {
        WQ_PRI_FIRST,
        WQ_PRI_SECOND,
        WQ_PRI_THIRD,
    };
    CWorkQueue();
    virtual ~CWorkQueue();
    bool register_work_task(CWorkTask task);
    bool on_schedule();
private:
    std::list<CWorkTask> m_queue;
};

#endif /* CWORKQUEUE_H_ */
