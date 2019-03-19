Feature: Check if I can get the balance of a user properly
    In order to ensure user balance endpoint
    As a developer 
    I want to check if all the cases are covered

    Scenario: Normal get balance flow
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John doe" and balance "1000"
        When I do a "GET" request to "/api/user/069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1/balance"
        Then the response code should be "200"
        And the response should match json:
        """
        {
            "data": {
                "type": "App\\Core\\Domain\\Entity\\Balance",
                "attributes": {
                    "value": 1000
                },
                "links": {
                    "self": "\/App\\Core\\Domain\\Entity\\Balance\/"
                }
            }
        }
        """ 

    Scenario: If user is invalid then a bad request response should be returned 
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John doe" and balance "1000"
        When I do a "GET" request to "/api/user/12345aninvaliduser/balance"
        Then the response code should be "404"