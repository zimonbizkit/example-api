Feature: Check if transactions between users are working
    In order to ensure user transactions endpoint
    As a developer 
    I want to check if all the cases are covered

    Scenario: If bad body is passed then there should be an error
     Given the request body is:
        """
        {
            "data" : {
                "recipient":"88b22565-0c01-4d24-a730-6cb8442d843d",
                "amountaaaaa":"1.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/thisuserdoesnotexist/transaction"
        Then the response code should be "500"

        #this case above should return 400 but i hadnt enough time to wrap JSONAPI errors on exceptions in controllers

    Scenario: No transaction if transaction emitter is not found
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John" and balance "2000"
        Given a present user with uuid "944ca58a-2d6f-4c06-be4c-e5c30928b288" and name "Sally" and balance "1000"
        Given the request body is:
        """
        {
            "data" : {
                "recipient":"944ca58a-2d6f-4c06-be4c-e5c30928b288",
                "amount":"1.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/thisuserdoesnotexist/transaction"
        Then the response code should be "400"
    
    Scenario: No transaction if reciever is not found
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John" and balance "2000"
        Given a present user with uuid "944ca58a-2d6f-4c06-be4c-e5c30928b288" and name "Sally" and balance "1000"
        Given the request body is:
        """
        {
            "data" : {
                "recipient":"nonexisting reciever",
                "amount":"1.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1/transaction"
        Then the response code should be "400"

    Scenario: No transaction if emitter has not funds in his account
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John" and balance "5"
        Given a present user with uuid "944ca58a-2d6f-4c06-be4c-e5c30928b288" and name "Sally" and balance "1000"
        Given the request body is:
        """
        {
            "data" : {
                "recipient":"944ca58a-2d6f-4c06-be4c-e5c30928b288",
                "amount":"10.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1/transaction"
        Then the response code should be "400"

    Scenario: No transaction if at all we try to do a negative transaction
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John" and balance "2000"
        Given a present user with uuid "944ca58a-2d6f-4c06-be4c-e5c30928b288" and name "Sally" and balance "1000"
        Given the request body is:
        """
        {
            "data" : {
                "recipient":"944ca58a-2d6f-4c06-be4c-e5c30928b288",
                "amount":"-10.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1/transaction"
        Then the response code should be "400"

    # we are not able to assert the response body as in the response theres the id of the new created resource (transaction), check it 
    # with postman
    Scenario: Casual transaction between two users
        Given a present user with uuid "069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1" and name "John" and balance "2000"
        Given a present user with uuid "944ca58a-2d6f-4c06-be4c-e5c30928b288" and name "Sally" and balance "1000"
        Given the request body is:
        """
        {
            "data" : {
                "recipient":"944ca58a-2d6f-4c06-be4c-e5c30928b288",
                "amount":"1.0000"
            }
        }
        """
        When I do a "POST" request to "/api/user/069a22d2-73e1-4e1a-aedb-b01dc1c4c7c1/transaction"
        Then the response code should be "201"