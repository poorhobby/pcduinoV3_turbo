/**
 * @brief cWorkQueue.cpp
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#include "cWorkQueue.h"
#include "pthread.h"
#include "sys/sysinfo.h"

CWorkQueue::CWorkQueue():
m_waitThread(0), m_workThread(0)
{
	pthread_mutexattr_t attr;
	int i;
	pthread_mutexattr_init(&attr);
	for (i = 0; i < CWorkQueue::WQ_MUTEX_MAX; i++) {
		pthread_mutex_init(&m_mutex[i], &attr);
	}
	pthread_mutexattr_destroy(&attr);
	for (i = 0; i < CWorkQueue::WQ_COND_MAX; i++) {
		pthread_cond_init(&m_cond[i], NULL);
	}

}

CWorkQueue* CWorkQueue::get_queue()
{
	static CWorkQueue queue;
	return &queue;
}

CWorkQueue::~CWorkQueue()
{
}

static long get_up_time()
{
    struct sysinfo info;

    sysinfo(&info);
    return info.uptime;
}


/**
 * @brief register a work task to this queue
 * @param task a descriptor of CWorkTask
 * @return bool true for success, and false for failed.
 */
bool CWorkQueue::register_work_task(CWorkTask& task)
{
	int need_wake_up = 0;
	pthread_mutex_lock(&m_mutex[CWorkQueue::WQ_COND_WORKING]);
	if (m_workingQueue.empty()) {
		need_wake_up = 1;
	}
	m_workingQueue.push_back(task);
	if (need_wake_up) {
		pthread_cond_broadcast(&m_cond[WQ_COND_WORKING]);
	}
    pthread_mutex_unlock(&m_mutex[CWorkQueue::WQ_COND_WORKING]);
    return true;
}

bool CWorkQueue::register_work_task_delay(CWorkTask &task, long delay)
{
    std::list<CWorkTaskDelay>::iterator iter;
    long now = get_up_time();
    long timeout = now + delay;
    CWorkTaskDelay task_d(task, timeout);
    pthread_mutex_lock(&m_mutex[CWorkQueue::WQ_MUTEX_WAITING]);
    for (iter = m_waitingQueue.begin(); iter != m_waitingQueue.end(); iter++) {
    	if ((*iter).get_timeout() >= timeout) {
    		break;
    	}
    }
    /*insert the task to the right position of the waiting queue*/
    m_waitingQueue.insert(iter, task_d);
    pthread_mutex_unlock(&m_mutex[CWorkQueue::WQ_MUTEX_WAITING]);

    /*wake up the on_wait thread.*/
    pthread_cond_broadcast(&m_cond[WQ_COND_WAITING]);
}

static void* pull_wait(void * pArg)
{
	CWorkQueue *self = (CWorkQueue*)pArg;
	self->on_wait();
    return NULL;
}

static void* pull_schedule(void * pArg)
{
	CWorkQueue *self = (CWorkQueue*)pArg;
	self->on_schedule();
    return NULL;
}

bool CWorkQueue::pull_up()
{
	pthread_attr_t attr;
	pthread_attr_init(&attr);
	pthread_attr_setdetachstate(&attr,PTHREAD_CREATE_DETACHED);
	pthread_create(&m_waitThread, &attr, pull_wait, (void*)this);
	pthread_create(&m_workThread, &attr, pull_schedule, (void*)this);
	pthread_attr_destroy(&attr);
	return true;
}

/**
 * @brief this is a single thread for delayed task schedule.
 */
bool CWorkQueue::on_wait()
{
	long now;
	std::list<CWorkTaskDelay>::iterator iter;
	do {
		pthread_mutex_lock(&m_mutex[CWorkQueue::WQ_MUTEX_WAITING]);
		iter = m_waitingQueue.begin();
		if((*iter).get_timeout() > now) {
			long mDelay = (*iter).get_timeout() - now;
			timespec delay;
			delay.tv_sec = mDelay;
			delay.tv_nsec = 0;
			pthread_cond_timedwait(&m_cond[WQ_COND_WAITING], &m_mutex[CWorkQueue::WQ_MUTEX_WAITING], &delay);
		} else {
			// no task, wait for wake up by register_work_task_delay
			pthread_cond_wait(&m_cond[WQ_COND_WAITING], &m_mutex[CWorkQueue::WQ_MUTEX_WAITING]);
		}

		now = get_up_time();

		for (iter = m_waitingQueue.begin(); iter != m_waitingQueue.end();) {
			if ((*iter).get_timeout() <= now) {
				register_work_task((*iter).get_task());

				// iter will auto change to the next element after current element erased.
				iter = m_waitingQueue.erase(iter);
			} else {
				break;
			}
		}
		pthread_mutex_unlock(&m_mutex[CWorkQueue::WQ_MUTEX_WAITING]);
	} while(1);

	return true;
}

/**
 * @brief this is a single thread for task executing.
 */
bool CWorkQueue::on_schedule()
{
    CWorkTask task;
Work:
	while(! m_workingQueue.empty()) {
		task = m_workingQueue.front();
		m_workingQueue.pop_front();
		task.exec_task();
	}

    pthread_mutex_lock(&m_mutex[CWorkQueue::WQ_MUTEX_WORKING]);
Wait:
    if (! m_workingQueue.empty()) {
    	/*Another thread add a task just now.*/
    	pthread_mutex_unlock(&m_mutex[CWorkQueue::WQ_MUTEX_WORKING]);
    	goto Work;
    } else {
    	/*unlock the m_mutex, and sleep on the m_cond[WQ_COND_WORING].*/
    	pthread_cond_wait(&m_cond[WQ_COND_WORKING], &m_mutex[CWorkQueue::WQ_MUTEX_WORKING]);

    	/*
    	 * m_mutex is re-locked when pthread_cond_wait returned.
    	 * Someone must have registered some tasks.
    	 */
    	goto Wait;
    }

    /*Will never return*/
    return true;
}



