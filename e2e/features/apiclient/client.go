package apiclient

import (
	"bytes"
	"crypto/tls"
	"io/ioutil"
	"net/http"
	"net/url"

	"github.com/pkg/errors"
)

// APIClient representation of the client
type APIClient struct {
	baseURL string
}

// APIResponse representation fo the api response
type APIResponse struct {
	StatusCode int
	Response   string
}

// NewApliClient initialize
func NewApliClient(authToken, baseURL string) *APIClient {
	return &APIClient{baseURL: baseURL}
}

// NewRequest create a new request for calling to jsonapi
func (c *APIClient) NewRequest(method, endpoint, jsonRequest string) (*http.Request, error) {
	uri := c.baseURL + endpoint
	u, err := url.Parse(uri)
	if err != nil {
		return nil, errors.Wrapf(err, "error parsing url %s", uri)
	}
	buff := []byte(jsonRequest)
	req, err := http.NewRequest(method, u.String(), bytes.NewBuffer(buff))
	if err != nil {
		return nil, errors.Wrapf(err, "Error creating a new request")
	}

	req.Header.Set("Content-Type", "application/json")
	req.Header.Set("Accept", "application/vnd.api+json")

	return req, nil
}

// Do execute the request
func (c *APIClient) Do(req *http.Request) (*APIResponse, error) {
	tr := &http.Transport{
		TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
	}

	cli := &http.Client{Transport: tr}
	resp, err := cli.Do(req)
	if err != nil {
		return nil, errors.Wrapf(err, "error proccessing response from server, for request: %v", req)
	}
	defer resp.Body.Close()

	bodyBytes, err := ioutil.ReadAll(resp.Body)
	response := &APIResponse{
		StatusCode: resp.StatusCode,
		Response:   string(bodyBytes),
	}

	return response, nil
}
