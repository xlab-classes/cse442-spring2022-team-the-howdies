# cse442-spring2022-team-the-howdies
cse442-spring2022-team-the-howdies created by GitHub Classroom

CSE442 Team: The Howdies team project - RateMyClasses
figma: https://www.figma.com/file/hNUIexgEzKkzxtYdGu4P31/Wireframe-RateMyClasses?node-id=5%3A15

Project Description

Team Name: The Howdies
Recitation Section: R5
David Schykerynec : dlschyke@buffalo.edu
Nicholas Tryon : nmtryon@buffalo.edu
Josh Donner : jadonner@buffalo.edu
Benjamin Strozyk : brstrozy@buffalo.edu

Motivation: 
There are no web applications out there dedicated to getting information on any of your upcoming classes, unless you were to look on something like reddit. Reddit isn’t a bad option but it is very common that some questions/replies are old and not very applicable any more. RateMyProfessor doesn’t have a lot of information on individual classes other than a short answer about the workload. Currently there is no system for viewing the relative difficulty, workload, or time consumption of a course until you are actually enrolled.

Proposed Solution:
Create a web application similar to ratemyprofessor but for classes in general. This will allow students to plan out their future course schedules while taking things like difficulty, workload, and time consumption into account. There may also be opportunities to absorb some functionality from ratemyprofessor in order to make the application more versatile.

Technology Stack:
Same as department (Apache, PHP, MySQL)
Python backend
NodeJS

End Goals:
Users able to login and navigate through a list of universities to one of their choosing
Users able to search through a list of courses or search for one specifically (perhaps search by department, pathway, gen ed, etc.)
Users then able to search through a list of reviews of that course
Each course could have an average rating
Each review could have “thumbs up/down” that other users can use to rate the review
Perhaps each course could have a comment section
Users able to post reviews for those classes
Perhaps add tags to help navigate through classes/reviews

Four Major Milestones:
Sprint 1: Have a working skeleton website
Sprint 2: Add some basic functionality to the website (login, posting, etc.) as well as good looking frontend
Sprint 3: Finish most of the frontend and backend. Have an almost completed web application.
Sprint 4: Clean up any remaining frontend and backend of the website as well as work on the final presentation/demo




Front End API Documentation

HTML
HTML will be our main markup language used to configure the base of our webpage.
To my knowledge, most of us are comfortable with HTML
Tutorials:
https://www.w3schools.com/html/
https://www.youtube.com/watch?v=qz0aGYrrlhU
Documentation:
https://en.wikipedia.org/wiki/HTML
https://html.com/
https://developer.mozilla.org/en-US/docs/Web/HTML

CSS
CSS is what we will use to style our HTML and make it look more appealing to our users
It will be closely tied with figma since we will be able to get CSS styles using figma
CSS will be our method describing how to render our webpage’s elements onto a screen
We are all most likely somewhat familiar with CSS as well as HTML
Tutorials:
https://www.w3schools.com/css/
Documentation:
https://cssprofile.collegeboard.org/
https://developer.mozilla.org/en-US/docs/Web/CSS


JavaScript
JavaScript will be our method of creating an interactive web application as well as assist in communicating with the backend
Tutorials:
https://www.w3schools.com/js/
https://www.youtube.com/watch?v=W6NZfCO5SIk
Documentation:
https://developer.mozilla.org/en-US/docs/Web/JavaScript
https://devdocs.io/javascript/

React
React is a JavaScript library used for building user interfaces
React is meant for UI development
Can be used for both web and mobile applications/development
This is one that the whole team will have to get the most comfortable with
Tutorials:
https://www.youtube.com/watch?v=w7ejDZ8SWv8
https://www.w3schools.com/REACT/DEFAULT.ASP
Documentation
https://reactjs.org/
https://reactjs.org/docs/getting-started.html
https://reactjs.org/docs/create-a-new-react-app.html
https://create-react-app.dev/




Back End API Documentation

Django framework
Django will be our main framework for implementing the backend
It is a high-level framework written in Python to accomplish the following goals
Completeness: perform all common tasks “out of the box” without external APIs
Versatile: Can work with nearly any client-side framework and build nearly any type of website
Secure: provides secure management of user accounts / passwords and other best practices such as password hashing, protection from SQL injection, other security vulnerabilities.
Scalable & maintainable: code is modular and DRY
Portable: python runs on many platforms so it should be able to run on most server infrastructure, hopefully including the CSE servers
Tutorials:
https://developer.mozilla.org/en-US/docs/Learn/Server-side/Django/Introduction
https://docs.djangoproject.com/en/4.0/intro/tutorial01/
Documentation:
https://docs.djangoproject.com/en/4.0/
Database (mySQL) integration:
https://docs.djangoproject.com/en/4.0/topics/install/#database-installation
Django and python provide drivers that adhere to PEP 249. Django documentation recommends using mysqlclient
Mysqlclient documentation:
https://pypi.org/project/mysqlclient/
Mysqlclient tutorial: 
https://github.com/PyMySQL/mysqlclient/blob/main/doc/user_guide.rst


MySQL
MySQL is an open source SQL database management system developed by Oracle.
MySQL is relational; database structures are organized into separate tables that allow for flexibility in programming such as relationships between data fields and pointers between tables. 
MySQL supports multithreading (mysqlclient and Django are also threadsafe)
Supports many primitive data types as well as variable length strings
Documentation: 
https://dev.mysql.com/doc/
Tutorial: 
https://dev.mysql.com/doc/refman/8.0/en/tutorial.html
