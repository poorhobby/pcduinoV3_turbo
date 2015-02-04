/**
 * @brief cMsgSender.h
 * @date 2015年1月30日
 * @author puhui
 * @details 
 */

#ifndef CMSGSENDER_H_
#define CMSGSENDER_H_

class CMsgSender
{
public:
    CMsgSender();
    virtual ~CMsgSender();
    bool send_msg(char *pBuf, unsigned long len);
private:
    int m_udp;///< send socket.
};

#endif /* CMSGSENDER_H_ */
