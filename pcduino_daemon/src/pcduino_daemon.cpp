//============================================================================
// Name        : pcduino_daemon.cpp
// Author      : Puhui
// Version     :
// Copyright   : SONY
// Description : Hello World in C++, Ansi-style
//============================================================================

#include <iostream>
#include "cListener.h"
#include "unistd.h"
#include "cWorkQueue.h"
using namespace std;

int main() {
    CListener listener;
    CWorkQueue* pQueue = CWorkQueue::get_queue();
    pQueue->pull_up();
    sleep(1);
    listener.run();
    sleep(3);
    listener.wait();
	return 0;
}
