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

#include "cWorkQueue.h"
#include "cWorkTask.h"

CListener::CListener():
m_udps(-1), m_exit(0), m_threadId(0)
{
}

CListener::~CListener()
{
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

static void *set_pcduino_config(void* pArg)
{
	std::string *pPath = (std::string*)pArg;
	std::string phpCmdPath("/usr/sbin/php5");
	std::string phpScriptPath("/usr/settings/pcduinoGenConfig.php");

#define CMD_LINE_SIZE 1024
	char cmd[CMD_LINE_SIZE];

	/*execute php script to write the config files.*/
	snprintf(cmd, CMD_LINE_SIZE, "%s %s %s", phpCmdPath.c_str(), phpScriptPath.c_str(), pPath->c_str());
	system(cmd);

	/*restart the related services.*/
	snprintf(cmd, CMD_LINE_SIZE, "killall hostapd");
	system(cmd);
//	snprintf(cmd, CMD_LINE_SIZE, "service /etc/init.d/networking restart");
//	system(cmd);
	//snprintf(cmd, CMD_LINE_SIZE, "hostapd -B /etc/hostapd/conf/hostapd.conf");
//	system(cmd);
//	snprintf(cmd, CMD_LINE_SIZE, "service isc-dhcp-server restart");
//	system(cmd);

#undef CMD_LINE_SIZE
	/*delete the arg, it's a variable from heap.*/
	delete pPath;
	return NULL;
}

static MSG_HANDLE_RETURN_TYPE pcduino_config(MSG_HANDLE_DEFINE_PARAMS)
{
	DDEBUG() << pBuf; // print the path of the json file.
	std::string *pPath = new std::string(pBuf, len);
    CWorkTask t(set_pcduino_config, (void*)pPath);
    CWorkQueue::get_queue()->register_work_task_delay(t, 2);
    return true;
}

static void *pcduino_wget_download(void* pArg)
{
	//TODO download task should be more detailed.
	// should be able to return the status of the download job.
	std::string *pUrl = (std::string*)pArg;
	std::string outputDir("/tmp/ftp");
	std::string cmd("wget ");

	cmd.append("-x -P ").append(outputDir).append(" ").append(pUrl->c_str()).append("&");

	::system(cmd.c_str());
	std::cout << cmd.c_str();
	std::flush(std::cout);

	delete pUrl;
	return NULL;
}

static MSG_HANDLE_RETURN_TYPE pcduino_wget(MSG_HANDLE_DEFINE_PARAMS)
{
	DDEBUG() << pBuf; // print the path of the json file.
	std::string *pUrl = new std::string(pBuf, len);
	CWorkTask t(pcduino_wget_download, (void*)pUrl);
	CWorkQueue::get_queue()->register_work_task(t);
	return true;
}

static CMsgHandler s_msgTab[] =
{
    CMsgHandler(MSG_ELEMENT_PCDUINO_CONFIG, 0, pcduino_config),
	CMsgHandler(MSG_ELEMENT_PCDUINO_WGET, 0, pcduino_wget),
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
            pHandler = CMsgHandler::findElementById(id, s_msgTab, TABLE_COUNT(s_msgTab));
            if (pHandler == NULL) {
                return;
            } else {
            	DDEBUG() << "ELEMENT LENGTH = " << l;
                pHandler->handle(pPos, l);
            }
        }
    }
}
