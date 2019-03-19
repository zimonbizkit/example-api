#!/bin/bash

touch hostip
cd /root/go/src/github.com/DATA-DOG/godog/cmd/godog
go build
export PATH=$PATH:/root/go/src/github.com/DATA-DOG/godog/cmd/godog
cd -
ln -s /root/go/src/github.com/DATA-DOG/godog/cmd/godog/godog godog
ln -s e2e_tests/features/main_test.go main_test.go
ln -s e2e_tests/featuers/apiclient/ apiclient
