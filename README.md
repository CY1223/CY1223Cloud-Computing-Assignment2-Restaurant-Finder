# s3557584-CY1223Cloud-Computing-Assignment2-Restaurant-Finder
Simple Cloud Based Restaurant Finder
Solution by Ching(s3557584@student.rmit.edu.au)
# Description

# Languages, platform and services used:
Php, Javascript, HTML, CSS was used to produce this assignment.
Google App Engine for hosting the website.
CloudSQL and Google Storage for storing user information.
Zomato and Google Maps API were used for the main funtionalities.
PhpMyAdmin Database UI plugin as database UI for CloudSQL.
RSA encryption algorithmn(javascript).

# Overview of the website
Simple cloud based restaurant finder using Zomato and Google Maps API. Users are able to search for their restaurants and add them as their favourites. Details of each restaurant will be shown during the search.

# Login and register
User must register and login before entering the main page of the site. CloudSQL database is used to store user credentials. RSA encryption algorithmn was used to ecrypt user password before storing it to the database. 

# Search and add favourite restaurants
Users are able to search or look for nearby restaurants by entering the restaurant name or a food related keyword like "pizza".
After searching users are able to add the restaurants as favourites. Google Cloud Storage is used to store individual user's favourite restaurants.

# Viewing favourites
Users are able to view their favourites by go to the favourites page.
All the user favourite restaurants will be shown in the form of an interactable google map and users are able to click on the markers to access the restaurant's zomato page. 
