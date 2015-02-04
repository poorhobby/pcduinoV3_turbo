/**
 * @brief cMsgSender.cpp
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#include "cMsgSender.h"
#include "iostream"
#include "cLogger.h"
#include "sys/socket.h"
#include "stdio.h"
#include "unistd.h"
#include "netinet/in.h"
#include "iostream"
#include <wait.h>
#include "stdlib.h"
#include "string.h"

CMsgSender::CMsgSender():
m_udp(-1)
{
    // TODO Auto-generated constructor stub
    int ret;
    struct sockaddr_in addr_for_connect;
    m_udp = socket(AF_INET, SOCK_DGRAM, 0);
    bzero(&addr_for_connect, sizeof(addr_for_connect));
    addr_for_connect.sin_family = AF_INET;
    addr_for_connect.sin_addr.s_addr = htonl(INADDR_LOOPBACK);
    addr_for_connect.sin_port = htons(0xAAEE);
    ret = ::connect(m_udp, (sockaddr*)&addr_for_connect, sizeof(addr_for_connect));
    if(ret == -1)
    {
        DERROR() << "connect to" << addr_for_connect.sin_port << "failed.";
        ::close (m_udp);
        m_udp = -1;
    }
}

CMsgSender::~CMsgSender()
{
    // TODO Auto-generated destructor stub
}

bool CMsgSender::send_msg(char *pBuf, unsigned long len)
{
    if (pBuf == NULL || len < 8)
    {
        DERROR() << "send buffer is invalid";
        return false;
    }
    long sendlen;

    sendlen = ::send(m_udp, (void*)(pBuf), len, 0);
    DDEBUG() << sendlen << "bytes sent.";
    //delete[] pBuf;
    return true;
}

