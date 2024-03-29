create database socialnetwork;

set search_path to public;

CREATE TABLE public.person (
personid SERIAL PRIMARY KEY, 
username VARCHAR(32) NOT NULL, 
password VARCHAR(32) NOT NULL, 
firstname VARCHAR(32) NOT NULL, 
lastname VARCHAR(32) NOT NULL, 
address VARCHAR(200), 
birthday DATE, 
gender boolean, 
isactive boolean, 
imagepath VARCHAR(200)
);

INSERT INTO "public"."person" ("username","password","firstname","lastname","address","birthday","gender","isactive","imagepath")
					VALUES ('root','root','Admin','',NULL,NULL, TRUE, TRUE, NULL);

CREATE TABLE album (
    albumid SERIAL PRIMARY KEY,
    userid integer references person(personid),
    thumbnailphotoid integer,
    albumname character varying(50),
    description text,
    visibility character(10)
);

CREATE TABLE photo (
    photoid SERIAL PRIMARY KEY,
    albumid integer references album(albumid),
    caption text,
    locationpath character(100),
    thumbnailpath character(100),
    isrgb boolean,
    uploadtime date,
    feature double precision[]
);

CREATE TABLE comment (
    commentid SERIAL PRIMARY KEY,
    commenterid integer references person(personid),
    photoid integer references photo(photoid),
    text text,
    datetime date,
    isnotified boolean
);

CREATE TABLE friendrequest (
    senderid integer references person(personid),
    receiverid integer references person(personid),
    datetime date,
    isnotified boolean
);

CREATE TABLE friendship (
    userid1 integer references person(personid),
    userid2 integer references person(personid),
    startdatetime date,
    isnotified boolean
);

CREATE TABLE "photoLike" (
    userid integer references person(personid),
    photoid integer references photo(photoid),
    datetime date,
    isnotified boolean
);



