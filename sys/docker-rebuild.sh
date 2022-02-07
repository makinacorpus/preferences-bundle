#!/bin/bash
APP_DIR="`dirname $PWD`" docker-compose -p msp up -d --build --remove-orphans --force-recreate
