#!/usr/bin/env bash

if id -u "$1" >/dev/null 2>&1; then
        echo "user exists"
else
        echo "user does not exist"
fi