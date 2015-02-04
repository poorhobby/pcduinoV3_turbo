/**
 * @brief cWorkTask.h
 * @date 2015年2月4日
 * @author puhui
 * @details 
 */

#ifndef CWORKTASK_H_
#define CWORKTASK_H_


typedef void *(*WorkTaskFunc)(void *pArg);
/**
 * @brief task is very simple,
 * 	only have a task function
 * 	and a param for the function.
 */
class CWorkTask
{
public:
    enum EWorkTaskExecResult {
    	WTER_OVER,
        WTER_TIME_NOT_ARRIVE,
        WTER_AGAIN,
    };
    CWorkTask();
    CWorkTask(WorkTaskFunc f, void* m_pArg);
    virtual ~CWorkTask();
    EWorkTaskExecResult exec_task();

    WorkTaskFunc m_task;///< task function
    void *m_pArg; ///< param for the function
//    long m_timeout;
//    long m_count;
};

#endif /* CWORKTASK_H_ */
