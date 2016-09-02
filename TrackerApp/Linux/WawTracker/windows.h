#ifndef WINDOWS_H
#define WINDOWS_H

#include "qglobal.h"

typedef void* PVOID;
typedef PVOID HANDLE;
typedef HANDLE HHOOK;
typedef qint32 DWORD;

typedef qintptr LRESULT;
typedef qintptr WPARAM;
typedef qintptr LPARAM;

typedef LRESULT (*HOOKPROC)(int, WPARAM, LPARAM);
typedef HHOOK (*SetWindowsHookExFunction)(int, HOOKPROC, HINSTANCE, DWORD);
typedef LRESULT (*CallNextHookExFunction)(HHOOK, int, WPARAM, LPARAM);

#endif // WINDOWS_H
