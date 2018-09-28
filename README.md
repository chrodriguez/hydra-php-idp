# Sample ORY Hydra PHP integration

This repo defines a sample login and consent PHP application to be used as
starting point.

## Requirements

This example uses docker and docker-compose as main requirements. For testing
purpose, the following ports will be opened on your machine:

* **8000:** ORY/Hydra server public service without ssl
* **4445:** ORY/Hydra server admin service without ssl
* **9010:** Sample resource server created within admin console. _This will require
  runing a command_
* **8080:** sample PHP login and consent application provided by this repo

## Start your installation

Clone this repo in a directory. And then change into that directory

## Runing containers

The `docker-compose.yml` file provided is mainly used for a development envinronment.
As PHP composer libraries are not versioned, you need to download them. To do
that there is a command to help you do that, without installing PHP on your
system:

```
 ./bin/composer-update-sh
```

When this command ends, all required libraries will be downloaded, and
everything will be ready to start your demo.

## Starting the whole demo

Simply run:

```
 docker-compose -p hydra up
```

All docker images will be downloaded and a mysql database will be populated with
hydra required tables an data. When all containers bootstrap, everything will be
running. But there are two step that must be run manually:

### Create a sample Oauth2 Client or resource server

There is a helper command to do this:

```
  ./bin/hydra-setup-client.sh
```

This command defines an Oauth2 client or resource server with:

* **Client id:** sample-hydra-app-photo-resources
* **Callback:** Return address configured as valid. It is set to http://localhost:9010/callback
  * _Note that this callback is provided by hydra using the other script_
* **Scope:** the scope the client is allowd to request. It is set to
  _openid,offline,photos.read_
* **Secret:** client's secret. It is set to _some-secret_
* **Grant types:** a list of allowed grant types. Is is set to
  _authorization_code,refresh_token,client_credentials,implicit_
* **Response types:** a list of response types. It is set to _token,code,id_token_

> If command is run twice, or more times, it will fail because client with that
> ID exists. You shall delete the previous created client

### Starts a web server that initiates and handles OAuth 2.0 requests

After creating the client, a sample client application can be emulated using hydra.
There is a helper command to do this:

```
  ./bin/hydra-setup-sample-app.sh
```

This command starts a sample application that will request authorization to
hydra

* **HTTP port:** 9010
* **Auth-url:** Authorization endpoint. It is set to http://localhost:8000/oauth2/auth
  * _This is our hydra server_
* **Token url:** Token requests endpoint. It is set to http://hydra-server:4444/oauth2/token
* **Client id:** name of the client, It is set to _sample-hydra-app-photo-resources_
* **Client secret:** client secret. It is set to _some-secret_
* **Scope:** wich copes are required. It is set to _openid,offline,photos.read_


## Test the integration example

* Access http://localhost:9010. It will presents you a link to authorize
  application
* Add sample users to idp database:

```
docker-compose -p hydra exec slim-db
mysql -p$MYSQL_ROOT_PASSWORD users
insert into users(username,password,firstname,lastname, gender, birthdate, email, email_verified,address, phone_number, phone_number_verified, admin) values('admin', '1234','Admin Name','Lastname', 'M', '2018-09-28', 'admin@example.net', 1, '1 y 50', '555-11111', 0, 1),
('user', '5678', 'User Name', 'ULastname', 'F', '2017-10-01', 'user@example.net', 0, '2 y 40', '444-2222', 1, 1) ;
```

* Enter your credentials. In this example there is only one user: admin/1234
* After successful login, there will be a consent page that must be accepted
* When consent is accepted, hydra will redirect us to the configured callback,
  where all OAuth2 information is printed

## Restart all this demo

In order to restart this demo you shall:

* Stop the containers once started, and remove them: `docker-compose -p hydra
  down`
* Remove any used volume: `docker volume rm <vol1> <vol2>` or simply run `docker
  volume prune`



