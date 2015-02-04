/**
 * @brief cListener.cpp
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#include "cListener.h"
#include "stdio.h"
#include "sys/socket.h"
#include "stdio.h"
#include "unistd.h"
#include "netinet/in.h"
#include "iostream"
#include <wait.h>
#include "stdlib.h"
#include "string.h"
#include "cLogger.h"
#include "cMsgHandler.h"
#include "fstream"
//#include "json/json.h"
//#include "cPcduinoWanSetting.h"
//#include "cPcduinoLanSetting.h"
//#include "cPcduinoWifiSetting.h"

CListener::CListener():
m_udps(-1), m_exit(0), m_threadId(0)
{
    // TODO Auto-generated constructor stub

}

CListener::~CListener()
{
    // TODO Auto-generated destructor stub
}

static void* daemon_thread(void * pArg)
{
    CListener *pListener = (CListener *)pArg;
    pListener->thread_handle();
    return NULL;
}

void CListener::thread_handle()
{
    int yes = 1;
    int ret = -1;
    struct sockaddr_in addr_for_bind;
    m_udps = socket(AF_INET, SOCK_DGRAM, 0);
    if (m_udps == -1) {
        DERROR() << "Socket create error...";
        return;
    }
    setsockopt(m_udps, SOL_SOCKET, SO_REUSEADDR, &yes, sizeof(yes));
    bzero(&addr_for_bind, sizeof(addr_for_bind));
    addr_for_bind.sin_family = AF_INET;
    addr_for_bind.sin_addr.s_addr = htonl(INADDR_LOOPBACK);
    addr_for_bind.sin_port = htons(LISTENER_PORT);
    ret = ::bind(m_udps, (sockaddr*)&addr_for_bind, sizeof(addr_for_bind));
    if(ret == -1) {
        DERROR() << "bind to" << ntohs(addr_for_bind.sin_port) << "failed.";
        ::close (m_udps);
        return;
    }
    thread_loop();
    return;
}

void CListener::run()
{
    pthread_attr_t attr;
    pthread_attr_init(&attr);
    pthread_attr_setdetachstate(&attr,PTHREAD_CREATE_JOINABLE);
    m_exit = 0;
    pthread_create(&m_threadId, &attr, daemon_thread, (void*)this);
    pthread_attr_destroy(&attr);
}

void CListener::wait()
{
    void * retValue;
    pthread_join(m_threadId, &retValue);
}

#define LISTENER_RECV_BUFFER_SIZE 8192
void CListener::thread_loop()
{
    struct timeval timeout;
    int ret;
    int max = 0;
    fd_set fdset;
    size_t recvlen;
    char *pBuf = new char[LISTENER_RECV_BUFFER_SIZE];
    if (pBuf == NULL) {
        DERROR() << "creating buffer error\n";
        return;
    }
    while (!m_exit) {
        timeout.tv_sec = 0;
        timeout.tv_usec = 1000;//10ms
        FD_ZERO(&fdset);
        FD_SET(m_udps, &fdset);
        max = m_udps;
        ret = select(max+1, &fdset, NULL, NULL, &timeout);
        if(ret == 0) {
        } else if (ret == -1) {
            perror("select error");
        } else {
            recvlen = recv(m_udps, (void *)pBuf, LISTENER_RECV_BUFFER_SIZE, MSG_DONTWAIT);
            if (recvlen > 0) {
                DDEBUG() << "Received" << recvlen << "bytes data";
            } else {
                continue;
            }
            // @todo handle this message
            msg_handle(pBuf, recvlen);
        }
    }
}


static MSG_HANDLE_RETURN_TYPE pcduino_config(MSG_HANDLE_DEFINE_PARAMS)
{
#if 0
    char path[256] = {0};
    std::fstream fs;
    char jsonData[8192];
    if (pBuf == NULL)
    {
        return false;
    }
    std::cout << pBuf << std::endl;
    if (len > sizeof(path))
    {
        DERROR() << "path is too long.";
        return false;
    }
    memcpy(path, pBuf, len);
    if( access(path, F_OK) && access(path, R_OK))
    {
        return false;
    }
    fs.open(path);
    if( !fs.is_open() )
    {
        std::cout << "open failed..." << std::endl;
        return false;
    }
    fs.getline(jsonData,8192);
    Json::Value root;   // will contain the root value after parsing.
    Json::Reader reader;
    //Json::Writer writer;
    bool parsingSuccessful = reader.parse( std::string(jsonData), root );
    if ( !parsingSuccessful )
    {
        // report to the user the failure and their locations in the document.
        std::cout  << "Failed to parse configuration\n"
                   << reader.getFormattedErrorMessages()
                   << std::endl;
        //reader.
        return false;
    }

    const Json::Value wan = root["wan"];
    const Json::Value lan = root["lan"];
    const Json::Value wifi = root["wifi"];

    CPcduinoWanSetting wanSetting(wan);
    CPcduinoLanSetting lanSetting(lan);
    CPcduinoWifiSetting wifiSetting(wifi);
    return wanSetting.apply() && lanSetting.apply() && wifiSetting.apply();
#else
    system("ifconfig eth0 down");
#endif
}


static CMsgHandler s_msgTab[] =
{
    CMsgHandler(MSG_ELEMENT_PCDUINO_CONFIG, 0, pcduino_config),
};

void CListener::msg_handle(char *pBuf, unsigned long len)
{
    unsigned long id;
    unsigned long l;
    char *pPos = pBuf;
    if (pBuf == NULL || len < sizeof(id) + sizeof(l)) {
        return;
    } else {
        {
            id = ntohl(*(unsigned long *)pPos);
            pPos += sizeof(id);
            l = ntohl(*(unsigned long *)pPos);
            pPos += sizeof(l);
        }
        {
            CMsgHandler *pHandler;
            pHandler = CMsgHandler::findElementById(MSG_ELEMENT_PCDUINO_CONFIG, s_msgTab, TABLE_COUNT(s_msgTab));
            if (pHandler == NULL) {
                return;
            } else {
                pHandler->handle(pPos, l);
            }
        }
    }

}
