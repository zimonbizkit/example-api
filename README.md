# Payments API  
### Requirements
`docker` and `docker-compose`

### Installation
Go to the project root directory and run
```console
$ make up && make setup && make migrate
```
_NOTE_:If for some reason, the step `make migrate` fails, wait an instant and execute the following command:
```console
docker-compose exec php bin/console doctrine:migrations:migrate
```
If everything went well and the docker environment was created properly, just run `make e2e` to run the integration tests to the API. 
To explore other project options,just run `make`

### Docker containers
They are managed via the docker-compose.yml file in the project. Services are:
- **php** ->  for PHP as name says
- **nginx** -> for the server
- **db** -> for storage of data
- **goe2e** -> for e2e tests execution

### API Architecture conventions
The project is built with the Symfony framework, in PHP language. It aims to follow the following principles:
- Hexagonal architechture
- Domain driven design
- JSONAPI standard (few tunings left but running for the two endpoints)

The e2e tests are written in Gherkin and are implemented using Go, using the godog package.

### Endpoints
 - [GET] `/api/user/[user_uuid]/balance`
    - Gets the balance of an existing user  
 - [POST] `/api/user/[user_uuid]/transaction`
    - Post a transaction , from the user specified on the path, to the user specified on the request body
 - [GET] `/api/doc.json`
    - Retrieves the api documentation as in the sandbox, in JSON format  
 
To see the api sandbox environment, go to [http://127.0.0.1:8000/api/doc](http://127.0.0.1:8000/api/doc). The other endpoints 
### Testing
To run the integration tests and e2e tests, just run `make e2e`
To run the unit tests of the API itself, run `make test`

### Why e2e tests on go?
The initial aim of the e2e tests in go is the ability that [godog](https://github.com/DATA-DOG/godog) can run tests concurrently, something that's interesting...
...if it weren't that the API is coupled to a database as it uses the same docker-compose file, and each scenario, once completed, wipes the database automatically. That way we can't take advantage of running the e2e tests in parallel. Either way:
- It's interesitng as we can have an isolated environment in a different languages to run the same API.
- It's a good chance to enhance and prove intention of usage and knowledge of Go and its ecosystem
- A good chance to apply learnings from my last employer
___
### What's left
- Fine tune a bit the JSONAPI adherence to the API
    - Some errors left unwrapped as the base response
    - Responses that imply a resource (both /balance and /transaction) return their PHP namespace, when they should return a endpoint link to themselves in the API to refer to themselves. This is not done as this would imply doing more endpoints (GET balance and GET transaction).
-    Enhance the e2e tests to assert upon the response of some requests
        - This wasn't done for the POST /transaction, as its response includes an automattically generated ID that's dynamic. 
### What could be done as extra of the test
- Implement an authentication system for the API (for example with [JWT](https://jwt.io/))
- Implement an ACL by endpoint
- Implement the endpoint [GET] `/api/user/[user_uuid]` that will return a 'User' resource with all its data (hence marked as [INCOMPLETE] in the swagger sandbox)


