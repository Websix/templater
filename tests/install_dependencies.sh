#!/bin/bash

wget "http://nodejs.org/dist/v0.12.2/node-v0.12.2.tar.gz"
tar -xvzf "node-v0.12.2.tar.gz"
cd "node-v0.12.2"
./configure
make install
if [ $(node --version) = "v0.12.2" ]; then
    exit 0
else
    exit 1
fi