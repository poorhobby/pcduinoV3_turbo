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
using namespace std;

int main() {
    CListener listener;
    listener.run();
    sleep(3);
    listener.wait();
	return 0;
}
