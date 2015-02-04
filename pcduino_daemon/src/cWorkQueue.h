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
#include "pthread.h"
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
    bool register_work_task_delay(CWorkTask task, long delay);
    bool on_schedule();
    bool on_wait();
    bool wake_up_queue();
    bool wake_up_queue_delay(long wait);
private:
    std::list<CWorkTask> m_queue;
    pthread_mutex_t m_mutex;///< protect the task queue.
    pthread_cond_t m_cond;
    pthread_mutex_t m_cmutex;
    int m_awake;

    std::list<CWorkTask> m_waitQueue;
};

#endif /* CWORKQUEUE_H_ */
