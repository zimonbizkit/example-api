Feature: Check if API is up
    In order to guarantee API reachability
    As a developer with a bit of devops
    I need to check if API is reachable

    Scenario: Check if the api-doc is available
        When I do a "GET" request to "/api/doc"
        Then the response code should be "200"