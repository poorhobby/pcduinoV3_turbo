/**
 * @brief cMsgHandler.cpp
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#include "cMsgHandler.h"
CMsgHandler::CMsgHandler(unsigned long eid, unsigned long len, CmdMsgHandle pSet):
    m_eid(eid), m_len(len), m_pCmdSet(NULL)
{
    m_pCmdSet = pSet;
}

CMsgHandler::~CMsgHandler()
{

}

CMsgHandler *CMsgHandler::findElementById(unsigned long id, CMsgHandler tab[], int tabCount)
{
    int i;
    for (i = 0; i < tabCount; i++)
    {
       if (id == tab[i].m_eid)
       {
           return &tab[i];
       }
    }
    return NULL;
}

MSG_HANDLE_RETURN_TYPE CMsgHandler::handle(MSG_HANDLE_DEFINE_PARAMS)
{
    if (m_pCmdSet != NULL) {
        return (m_pCmdSet)(MSG_HANDLE_EXEC_PARAMS);
    } else {
        return false;
    }
}
