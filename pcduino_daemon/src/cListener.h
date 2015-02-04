/**
 * @brief cListener.h
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#ifndef CLISTENER_H_
#define CLISTENER_H_

#include "pthread.h"

#define LISTENER_PORT 0xAAEE
class CListener
{
public:
    CListener();
    virtual ~CListener();

public:
    void run();
    void wait();
    void thread_handle();
    void thread_loop();
    void msg_handle(char *pBuf, unsigned long len);

private:
    int m_udps; ///< socket
    int m_exit; ///< set this to exit thread.
    pthread_t m_threadId;///< listener's thread id
};

#endif /* CLISTENER_H_ */
