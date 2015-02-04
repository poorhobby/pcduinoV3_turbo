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
m_awake(0)
{
	pthread_mutexattr_t attr;
	pthread_mutexattr_init(&attr);
	pthread_mutex_init(&m_mutex, &attr);
	pthread_mutex_init(&m_cmutex, &attr);
	pthread_mutexattr_destroy(&attr);

	pthread_cond_init(&m_cond, NULL);
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
bool CWorkQueue::register_work_task(CWorkTask task)
{
	std::list<CWorkTask>::iterator iter;
	CWorkTask t;
	pthread_mutex_lock(&m_mutex);

	for (iter = m_queue.begin(); iter < m_queue.end(); ) {
		t = *iter;
		if (t.m_timeout < task.m_timeout) {
			m_queue.insert(iter, task);
		}
	}
    if (! m_awake) {
    	m_awake = 1;
    	pthread_cond_broadcast(&m_cond);
    }
    pthread_mutex_unlock(&m_mutex);
    return true;
}

bool CWorkQueue::register_work_task_delay(CWorkTask task, long delay)
{
	m_waitQueue.push_back(task);
}

/**
 * @brief this is a single thread for task executing.
 */
bool CWorkQueue::on_wait()
{
}

/**
 * @brief this is a single thread for task executing.
 */
bool CWorkQueue::on_schedule()
{
    CWorkTask task;
    std::list<CWorkTask>::iterator iter = m_queue.begin();
//	long upTime = get_up_time();
    int jumpOut;
Again:
	pthread_mutex_lock(&m_mutex);
	m_awake = 0;
	pthread_mutex_unlock(&m_mutex);
	for (jumpOut = 0; iter < m_queue.end() && !jumpOut; ) {
		task = *iter;
		CWorkTask::EWorkTaskExecResult result;
		result = task.exec_task();
		switch (result)
		{
		case CWorkTask::WTER_AGAIN:
			break;
		case CWorkTask::WTER_TIME_NOT_ARRIVE:
			jumpOut = 1;
			break;
		case CWorkTask::WTER_OVER:
			/* When deleting node, prohibit from inserting node.*/
			pthread_mutex_lock(&m_mutex);
			iter = m_queue.erase(iter);
			pthread_mutex_unlock(&m_mutex);
			continue;
		default:
			break;
		}
		iter++;
	}

    pthread_mutex_lock(&m_mutex);
    if (m_awake) {
    	pthread_mutex_unlock(&m_mutex);
    	goto Again;
    } else {
    	// goto sleep and wait.
    	pthread_cond_wait(&m_cond, &m_cmutex);
    }
    pthread_mutex_unlock(&m_mutex);
    return true;
}
