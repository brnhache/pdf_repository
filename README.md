### Author: Brian Hache

### Date: 2022-09-21

# Lendesk Coding Challenge

Hello! Thanks for the opportunity to show you some of what I can do with Laravel in the context of an API.

This project is my submission for the Coding Challenge as a part of my application to the Junior Laravel Developer at Lendesk.

I have included some notes on my thought process and roadblocks I encountered towards the bottom of this document.

## Project Setup

## API Documentation

### Create User

Route: /api/auth/createUser

Method: Post

Example Body:
{
"name": "Brian",
"email": "brian@email.com",
"password": "thisisapassword"
}

Example Response:
{
"status": true,
"message": "User Created Successfully",
"token": "6|HHznJ23VfA1ehyQHTJj7ia1mkGjok02sLvufQYrO"
}

Description:
Creating a user will insert a row into the users table and return a token for resource access.

## Generate Token

Route: /api/auth/getToken

Method: Post

Example Body:
{
"email": "user_a@email.com",
"password": "thisisapassword"
}

Example Response:
{
"token": "5|H1TZhKONHHHyull2vyNyOe6gli9GqdtcNbulUoKE"
}

Description:
Providing existing user credentials will yeild a new token. These tokens do not currently expire. A token should be added to the Auth header of a resource request to allow access. There is currently only one type of token that allows full access to the user's resources.

## Post Document

Route: /api/documents

Method: Post

Content-Type: "multipart/form-data"

Required Form Fields:
Name:String
File:PDF

Description: Requires token. Currently only supports .pdf files. Upload a file to the form data of your client and include a name to distinguish the record,
as the file name in storage is hashed.

## Get All Documents

Route: /api/documents

Method: Get

Example Response:
[
{
"id": 10,
"user_id": 2,
"name": "my special doccc",
"path": "pdfs/xeGtPO8CvdBwlA7FSycbAcWCMBIxb752DuofssO5.pdf",
"created_at": "2022-09-22T00:26:34.000000Z",
"updated_at": "2022-09-22T00:26:34.000000Z"
},
{
"id": 3,
"user_id": 2,
"name": "BHache_Diploma.pdf",
"path": "pdfs/BHache_Diploma.pdf",
"created_at": "2022-09-22T00:10:16.000000Z",
"updated_at": "2022-09-22T00:10:16.000000Z"
},
{
"id": 4,
"user_id": 2,
"name": "lorem_2.pdf",
"path": "pdfs/lorem_2.pdf",
"created_at": "2022-09-22T00:10:16.000000Z",
"updated_at": "2022-09-22T00:10:16.000000Z"
}
]

Description: Requires token. Returns a list of all the documents linked to the authenticated user.

## Get Specific Document

Route: /api/documents/{id}

Method: Get

Example Response:
{
"id": 10,
"user_id": 2,
"name": "my special doccc",
"path": "pdfs/xeGtPO8CvdBwlA7FSycbAcWCMBIxb752DuofssO5.pdf",
"created_at": "2022-09-22T00:26:34.000000Z",
"updated_at": "2022-09-22T00:26:34.000000Z"
}

Description: Requires token. Returns a specific record by ID.

## Delete Document

Route: /api/documents/{id}

Method: Delete

Example Response:
{
"message": "document deleted."
}

Description: Requires token. Destroys a record belonging to the authenticated user.

## Thought Process

I know that Laravel has some very powerful automated tools that can help me whip this project up fast, so I'll be following the documentation and setting up a Docker project with Sail.

I only need two tables, one for users and another for documents. I've named the former in a more general way in case we want to support more than just PDFs in the future for this theoretical exercise.

Two migrations to seed each table with some data.

user_a@email.com password: thisisapassword
user_b@email.com password: thisisalsoapassword

Each user has two documents linked to them from the seeding.

When looking for a specific document to show or destroy, it is tempting to throw a 403 if they ask for a record with an ID that does not belong to them.
This gives too much information, so I throw a 404 no matter what, unless they provide a valid id.
I would also prefer to create a user-facing random ID for each record for the same reason.

In the event that a request is sent to create a new user with an email that already exists, I am returning a 409 and informing the client that the email already exists. This seems like I'm giving too much away after considering IDs above, but I'm not sure how else to handle the situation. Emails must be unique, and every other service I've used behaves this way. That makes me think it's not a big deal. I would be interested to hear the readers opinion.

## Authentication

I am currently creating a clunky authentication that validates the user credentials on every request. I realize that this is not production value and I need to learn how to implement middleware properly for this situation. I am reading documentation about it and will attempt to make things work properly after everything else is working.

I am now using the Laravel Sanctum tool to provide token authentication for the API. Request a token (see above) and place it in the authentication header as a bearer type to access resources.

## Roadblocks!

Developing with Sail using Docker inside of WSL2 posed an issue with uploading files from my local system - since the file paths were always from the windows environment point of view, and as I was testing file uploads the API could not find the file I was specifying. Luckily there is a work-around that involves adding a setting in the vscode settings.json file.

Authentication for a pure API is something I had to do some reading up on. Laravel documentation suggests the Sanctum authorization plugin for token based access, which is ideal for an API like this. The user can request a token by providing credentials to the server, and then use that token within request headers to allow access to the API.

When writing the tests, I realized that I need a way to generate resources. The documentation says not to include more than one request in each test, so here I go learning again. -> It seems that I can use a factory to generate resources without making a request, however I am not able to get it to work and will have to hack something together...-> I am able to pass the test by making a request to create a user before each resource request, but this goes against the documentation. Added in Todos.

## Testing

**More Notes**
Testing is incomplete. Since the tests are using a temporary database, I need to generate users, tokens and documents on the fly. Factories are the way to accomplish this from the documentation.

I am eager to learn the right way to test API features in Laravel, unfortunately I have run out of time for now and will be submitting what I have. If this were a work project, I would be able to ask team members for advice and opinions to help work through the problem.

-> To run the existing tests, enter the command "sail test" in the root of the project.

## Todo

Tasks that would follow if I were to continue building this project:

-> Search function
-> Update function
-> Perhaps a timeout of the API token depending on requirements [edit 'expiration' => null in sanctum.php].
If so, comprehensive messaging informing the user of stale token.
-> Attempt to collapse the routes in api.php to a single resource. Not sure if this is desireable. See comment on line 34 of api.php.
-> Create random user-facing IDs for each record to hide the number of records in the table.
-> Add some sweet markdown to this README.
-> Implement remember_token properly to increase security.
-> Figure out why the factory generated user was not useable in tests, and implement.
-> Figure out how to generate form data for uploading files in the test functions.
-> Ask for a team member to proof read this documentation and make changes where necessary.
