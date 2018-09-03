## Installation

- $ git clone https://github.com/mvanherwijnen/tweet-reach.git
- Create .env file in project root folder (copy from .env.example)
    - Twitter credentials are required and not provided
- $ docker-compose up

## Testing

- $ docker-compose exec myapp phpunit

## API

- GET $HOST/api/tweet/{id}
- GET $HOST/api/tweet/{id}/retweets

In development, $HOST= http://localhost:3000/

## Docker

Used https://hub.docker.com/r/bitnami/laravel/ as base, added redis container

## Architecture

- Middleware-based architecture, no controllers
- Minimal use of facade helpers (using DI instead), to increase testability
- Actions which are not needed to return response are moved to jobs 
    - In this case, only writing to cache is not needed to return response, 
        so this does not result in a huge performance boost

## Middleware

A call to the API results in the execution of middleware in this order:
-  CacheMiddleware
    - returns cached response if exists
    - schedules job to write to cache 
-  DomainModelMiddleware
    - retrieves domain model from repository/service
    - throws error if repository and method are not correctly configured
    - returns 404 if model does not exist
-  ResourceMiddleware
    - passes model to request if single model
    - passes collection to request if relation of model is requested (retweets in a concrete example)
    - returns 400 if requested relation is not supported by the model 
-  HalMiddleware
    - extracts HAL data from the resource 
    - returns JsonResponse with code 200
    
## Jobs

The WriteToCache job writes the response to cache on the route path as key.

## Listeners

The UpdateCache listener listens to the KeyForgotten event of the Cache repository.
This listener is a candidate for refactoring, since it contains duplicate code and is too concrete.
Proposed solution is to replay the request and cache the result