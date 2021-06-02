# Code-challenge
Code challenge proposed it is to get certificate logging information. The user is required to provide a certificate in order to login and register in the system.
The client authentication set in apache server ask the browser to prompts for a certificated base in the CA, once provided php get the information from the headers. Challenge also requires to save this serial into a database of choice, and the solution has to be created with Symfony framework has to run in a docker container.

# Solution
The solution proposed it is to use Symfony's security component with the built in authentication provider for X.509 Client Certificate Authentication. The database selected it is Mysql and the ORM is of course Doctrine. This choice it the first one that comes to the mind when using Symfony, but also because some relation are used so it is an easier implementation over NOSQL.

# Explanation
User lands into default page, they are prompt for their certificated and still outside the secured area. So they are not still not logged .
Once they proceed to the login/register page, the custom authenticator check by email associated to the certificate if the user is already in the database, if not it create one and save it. Then allows entrance to the home page inside the secured area. Once there, serial number and CN together with the email are shown, under the hood system check if the serial number of the certificated is in database. If it find it the add up one visit to the counter for this serial, if not it save the new serial associated to the user.

# Difficulties 
There has been some dificulties with the docker implementation, because lack of time there has been the necesity to upgrade php version inside the docker to 7.4. An no time for testing. 

# Installation 
Once cloned the repo , docker-compose up will create two containers. One with the mysql server and another with the php-apache image provided.
Navigating to https://localhost or if set in host dns to challenge.firmaprofesional.com will get into the url

# screenshots

Landing 
![image](https://user-images.githubusercontent.com/15227541/120407232-01800f80-c34d-11eb-8171-0f26227ea9c9.png)

Login/register
![image](https://user-images.githubusercontent.com/15227541/120407663-e366df00-c34d-11eb-9977-8e72239ef9fe.png)






