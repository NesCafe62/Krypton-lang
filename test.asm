format PE64 console 4.0

section '.data' data readable writeable
    format_i32 db '%d', 10, 0
    NULL = 0

section '.text' code readable executable
entry start
start:
    mov     rbp, rsp
    sub     rsp, 48

                                ; declare x
                                ; x = 1
    mov     DWORD [rbp-4], 1
                                ; for (...)
                                ; declare i
                                ; i = 1
    mov     DWORD [rbp-8], 1
    jmp     .L2
.L1:
                                ; io.print (...)
    mov     edx, DWORD [rbp-8]
    lea     rcx, [format_i32]
    call    [printf]
                                ; x += (...)
    mov     eax, DWORD [rbp-8]
    add     DWORD [rbp-4], eax
.L3:
                                ; i += 1
    inc     DWORD [rbp-8]
.L2:
    cmp     DWORD [rbp-8], 10
    jl      .L1
                                ; end for
                                ; io.print (...)
    mov     edx, DWORD [rbp-4]
    lea     rcx, [format_i32]
    call    [printf]

exit:
    mov     rsp, rbp
    xor     rax, rax
    ret


section '.idata' data import readable
    dd RVA msvcrt.lookup, 0, 0, RVA msvcrt_name, RVA msvcrt.address
    dd 0, 0, 0, 0, 0

    msvcrt_name db 'msvcrt.dll', 0

    msvcrt.lookup:
        dq RVA printf_name
        dq 0

    msvcrt.address:
        printf dq RVA printf_name
        dq 0

    printf_name	dw 0
        db 'printf', 0
