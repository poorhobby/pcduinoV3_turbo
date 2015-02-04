/*
 * cWorkTaskDelay.cpp
 *
 *  Created on: 2015年2月5日
 *      Author: paul
 */

#include "cWorkTaskDelay.h"

CWorkTaskDelay::CWorkTaskDelay(CWorkTask task, long timeout):
m_task(task), m_timeout(timeout)
{
}

CWorkTaskDelay::~CWorkTaskDelay() {
}

long CWorkTaskDelay::get_timeout()
{
	return m_timeout;
}

CWorkTask& CWorkTaskDelay::get_task()
{
	return m_task;
}
