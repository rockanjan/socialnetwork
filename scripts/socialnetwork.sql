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
gender VARCHAR(1), 
isactive VARCHAR(1), 
imagepath VARCHAR(200)
);

INSERT INTO "public"."person" ("username","password","firstname","lastname","address","birthday","gender","isactive","imagepath")
					VALUES ('root','root','Admin','',NULL,NULL,'M','Y',NULL);

