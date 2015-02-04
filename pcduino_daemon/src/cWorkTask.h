/**
 * @brief cWorkTask.h
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#ifndef CWORKTASK_H_
#define CWORKTASK_H_


typedef void *(*WorkTaskFunc)(void *pArg);
class CWorkTask
{
public:
    enum EWorkTaskExecResult {
    	WTER_OVER,
        WTER_TIME_NOT_ARRIVE,
        WTER_AGAIN,
    };
    CWorkTask();
    virtual ~CWorkTask();
    EWorkTaskExecResult exec_task();

    WorkTaskFunc m_task;
    void *m_pArg;
    long m_timeout;
    long m_count;
};

#endif /* CWORKTASK_H_ */
