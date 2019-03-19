
FROM golang:1.11

LABEL maintainer="Eduard Simon <simon.gallegos.edu@gmail.com>"

WORKDIR /root/go/

ENV GOPATH=/root/go

RUN go get github.com/DATA-DOG/godog/cmd/godog
RUN go install github.com/DATA-DOG/godog/cmd/godog

RUN go get github.com/go-sql-driver/mysql
RUN go install github.com/go-sql-driver/mysql

RUN go get github.com/pkg/errors
RUN go install github.com/pkg/errors

CMD ["/bin/bash"]
