# flat-text-database

`Not ready for release`

This is supposed to be a light-weight flat-text database
application which will allow grantable permissions for
individual users with the ability to explain to users
of the database, how to interact with the database
over an API.

Currently I have this working so it 

    1) Describes API integration
    2) Connects users for interaction
    3) Describes assigned tables
    
To do

    1) Make the database read the table-data section
    2) Ensure that adding/modifying existing table data
       moves all of the file pointers accordingly.
       
       
Usage example:

    use phpcodex\FTDB\Service\FTDBConnection;
     
    $ftdb = new FTDBConnection;
    $ftdb->connect('users.db', 'users', 'root', 'password');