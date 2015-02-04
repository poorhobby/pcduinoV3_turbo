/*
 * cWorkTaskDelay.h
 *
 *  Created on: 2015年2月5日
 *      Author: paul
 */

#ifndef CWORKTASKDELAY_H_
#define CWORKTASKDELAY_H_

#include "cWorkTask.h"

class CWorkTaskDelay
{
public:
	CWorkTaskDelay(CWorkTask task, long delay);
	virtual ~CWorkTaskDelay();
	CWorkTask& get_task();
	long get_timeout();

private:
	CWorkTask m_task;
	long m_timeout;
};

#endif /* CWORKTASKDELAY_H_ */
