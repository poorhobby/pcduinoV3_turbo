/**
 * @brief cMsgHandler.h
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#ifndef CMSGHANDLER_H_
#define CMSGHANDLER_H_
#include "iostream"

#define TABLE_COUNT(__tab__) (sizeof(__tab__)/sizeof(__tab__[0]))

#define MSG_HANDLE_DEFINE_PARAMS \
    char *pBuf, unsigned long len

#define MSG_HANDLE_EXEC_PARAMS \
    pBuf, len

#define MSG_HANDLE_RETURN_TYPE \
    bool

enum EMsgElementID
{
    MSG_ELEMENT_PCDUINO_CONFIG = 0xAB01,
	MSG_ELEMENT_PCDUINO_WGET,
};
typedef MSG_HANDLE_RETURN_TYPE (*CmdMsgHandle)(MSG_HANDLE_DEFINE_PARAMS);

class CMsgHandler
{
public:
    CMsgHandler(unsigned long eid, unsigned long len, CmdMsgHandle pSet);
    virtual ~CMsgHandler();
    static CMsgHandler *findElementById(unsigned long id, CMsgHandler tab[], int tabCount);
    MSG_HANDLE_RETURN_TYPE handle(MSG_HANDLE_DEFINE_PARAMS);

private:
    unsigned short m_eid;
    unsigned short m_len;
    CmdMsgHandle m_pCmdSet;
};

#endif /* CMSGHANDLER_H_ */
