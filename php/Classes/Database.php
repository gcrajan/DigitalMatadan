<?php
$password = "root";
$user = "root";
$database_location = "localhost";
$hash = "sha512";

$conn = new mysqli($database_location,$user,$password);
if($conn->connect_error){die("Error connecting to database");}

if(!$conn->query("Create database ElectionDb")){die("Error creating db");}

$conn = new mysqli($database_location,$user,$password,"ElectionDb");
if($conn->connect_error){die("Error connecting to database");}

if(!$conn->query("Create Table Elections(
    ElectionName varchar(50) primary key,
    year Bigint not null, 
    State varchar(10) not null,
    hash_used varchar(50)
)")){die("Error creating table");}

if(!$conn->query("Create Table Citizen( 
    Voter_card Bigint primary key, 
    Wardno int not null,
    District varchar(20) not null,
    VEmail varchar(50),
    Vcontact varchar(20)
)")){die("Error creating table");}

if(!$conn->query("Create Table Electionareas( 
    Election_area varchar(50),
    Wardno int not null,
    District varchar(50) not null
)")){die("Error creating table");}

if(!$conn->query("Insert into Electionareas
    values( 'Area 1',1,'Kathmandu'),
    ( 'Area 1',2,'Kathmandu'),
    ( 'Area 1',3,'Kathmandu'),
    ( 'Area 2',4,'Kathmandu'),
    ( 'Area 2',5,'Kathmandu'),
    ( 'Area 2',6,'Kathmandu')
")){die("Error creating table");}

if(!$conn->query("Insert into Citizen
    values(1, 1,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (2, 2,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (3, 3,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (4, 4,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (5, 5,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (6, 6,'Kathmandu','manjulsharma760@gmail.com','9860654300'),
    (7, 1,'Kathmandu','shresthasamip38@gmail.com','9846521553'),
    (8, 2,'Kathmandu','shresthasamip38@gmail.com','9846521553'),
    (9, 2,'Kathmandu','amit.ghimire@newsummit.edu.np','9851055866'),
    (10,4,'Kathmandu','prawesh.dhungana@newsummit.edu.np','9869406901')
   
")){die("Error creating table");}

$conn->close();
?>