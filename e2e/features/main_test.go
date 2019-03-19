package main

import (
	"database/sql"
	"e2e_tests/features/apiclient"
	"fmt"
	"os"
	"strconv"

	"github.com/DATA-DOG/godog"
	"github.com/DATA-DOG/godog/gherkin"
	_ "github.com/go-sql-driver/mysql"
)

type apiFeature struct {
	cli     *apiclient.APIClient
	resp    *apiclient.APIResponse
	request string
}

type dbConnection struct {
	conn *sql.DB
}

func (a *apiFeature) iDoARequestTo(method, endpoint string) error {

	r, err := a.cli.NewRequest(method, endpoint, a.request)
	if err != nil {
		return err
	}

	a.resp, err = a.cli.Do(r)
	if err != nil {
		return err
	}

	return nil
}

func (a *apiFeature) theRequestBodyIs(bodyRequest *gherkin.DocString) error {
	a.request = bodyRequest.Content

	return nil
}

func (a *apiFeature) theResponseCodeShouldBe(code string) error {
	//fmt.Printf("%+v\n", code)
	//fmt.Printf("%+v\n", strconv.Itoa(a.resp.StatusCode))
	if code != strconv.Itoa(a.resp.StatusCode) {
		return fmt.Errorf("expected response code to be: %s, but actual is: %d", code, a.resp.StatusCode)
	}
	return nil
}

func (db *dbConnection) aPresentUserWithUuidAndNameAndBalance(uuid, name, balance string) error {

	_, err := db.conn.Exec("INSERT INTO user VALUES('" + uuid + "','" + name + "'," + balance + ")")

	if err != nil {
		return err
	}

	return nil
}

func (a *apiFeature) theResponseShouldMatchJson(body *gherkin.DocString) error {
	if a.resp.Response != body.Content {
		return fmt.Errorf("expected response body to be: %s, but actual is: %s", body.Content, a.resp.Response)
	}

	return nil
}

func FeatureContext(s *godog.Suite) {

	api := &apiFeature{}
	dbConn := &dbConnection{}
	s.Step(`^a present user with uuid "([^"]*)" and name "([^"]*)" and balance "([^"]*)"$`, dbConn.aPresentUserWithUuidAndNameAndBalance)
	s.Step(`^I do a "([^"]*)" request to "([^"]*)"$`, api.iDoARequestTo)
	s.Step(`^the response code should be "([^"]*)"$`, api.theResponseCodeShouldBe)
	s.Step(`^the response should match json:$`, api.theResponseShouldMatchJson)
	s.Step(`^the request body is:$`, api.theRequestBodyIs)

	s.BeforeScenario(func(interface{}) {
		buf := string(os.Getenv("HOSTIP"))
		fmt.Println(buf)
		apihost := fmt.Sprintf("http://%s:8000", string(buf))
		api.cli = apiclient.NewApliClient("", apihost)
		dbConn.conn, _ = sql.Open("mysql", "root:root@tcp("+buf+":3306)/symfony")
		dbConn.conn.Exec("DELETE FROM user")
	})

}
