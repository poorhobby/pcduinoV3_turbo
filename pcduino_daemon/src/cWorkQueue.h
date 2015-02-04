/**
 * @brief cWorkQueue.h
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#ifndef CWORKQUEUE_H_
#define CWORKQUEUE_H_

#include "cWorkTask.h"
#include "cWorkTaskDelay.h"
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
    enum EWorkQueueMutexType
	{
    	WQ_MUTEX_WORKING,
		WQ_MUTEX_WAITING,

		WQ_MUTEX_MAX,
	};
    enum EWorkQueueCondType
	{
    	WQ_COND_WORKING,
		WQ_COND_WAITING,

		WQ_COND_MAX,
	};
private:
    CWorkQueue();
public:
    static CWorkQueue* get_queue();
    virtual ~CWorkQueue();
    bool register_work_task(CWorkTask &task);
    bool register_work_task_delay(CWorkTask &task, long delay);
    bool on_schedule();
    bool on_wait();
    bool pull_up();

private:
    std::list<CWorkTask> m_workingQueue;
    pthread_mutex_t m_mutex[WQ_MUTEX_MAX];///< protect the task queue.
    pthread_cond_t m_cond[WQ_COND_MAX];
    pthread_t m_waitThread;
    pthread_t m_workThread;

    std::list<CWorkTaskDelay> m_waitingQueue;
};

#endif /* CWORKQUEUE_H_ */
