EXECUTABLE = test4
TARGET = fasm-win-x86-64
CC = php -d auto_prepend_file=src/errorHandler.php src/index.php

all: compile compile-exe run

build: compile compile-exe


compile-php:
	${CC} ${EXECUTABLE}.kr -t php > temp && (move /y temp ${EXECUTABLE}.php > nul) || del temp


dump-ast:
	${CC} ${EXECUTABLE}.kr -ast > temp && (move /y temp ${EXECUTABLE}.ast > nul) || del temp

compile:
	${CC} ${EXECUTABLE}.kr -t ${TARGET} > temp && (move /y temp ${EXECUTABLE}.asm > nul) || del temp

compile-exe:
	fasm.exe ${EXECUTABLE}.asm

run:
	${EXECUTABLE}.exe

clean:
	del ${EXECUTABLE}.asm 2> nul
	del ${EXECUTABLE}.php 2> nul
	del ${EXECUTABLE}.ast 2> nul
	del ${EXECUTABLE}.hex 2> nul
	del ${EXECUTABLE}.exe 2> nul
	del temp 2> nul