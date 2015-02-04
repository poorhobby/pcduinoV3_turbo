//============================================================================
// Name        : pcduino_cli.cpp
// Author      : Puhui
// Version     :
// Copyright   : SONY
// Description : Hello World in C++, Ansi-style
//============================================================================

#include <iostream>
#include "cMsgSender.h"

#include "sys/socket.h"
#include "stdio.h"
#include "unistd.h"
#include "netinet/in.h"
#include "iostream"
#include <wait.h>
#include "stdlib.h"
#include "string.h"

using namespace std;

enum EMsgElementID
{
    MSG_ELEMENT_PCDUINO_CONFIG = 0xAB01,
};

void apply_pcduino_setting(const char * const pPath)
{
    char buf[8192] = {0};
    char *pPos = buf;
    *(unsigned long*)pPos = htonl(MSG_ELEMENT_PCDUINO_CONFIG);
    pPos += sizeof (unsigned long);
    unsigned long len = strlen(pPath) + 1;
    *(unsigned long*)pPos = htonl(len);
    pPos += sizeof (unsigned long);
    memcpy(pPos, pPath, len);
    pPos += len;

    unsigned long msgLen = pPos - buf;

    CMsgSender sender;
    sender.send_msg(buf, msgLen);
}

int main(int argc, char* argv[]) {
    int c;
    const char *pConfig = NULL;
    for(;;) {
        c = getopt(argc, argv, "s:");
        if (c < 0)
            break;
        switch(c)
        {
        case 's':
            //wan
            pConfig = optarg;
            apply_pcduino_setting(pConfig);
            break;
        default:
            break;
        }
    }
	return 0;
}
