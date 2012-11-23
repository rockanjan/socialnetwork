--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: album; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE album (
    albumid integer NOT NULL,
    userid integer,
    thumbnailphotoid integer,
    albumname character varying(50),
    description text,
    visibility character(10)
);


ALTER TABLE public.album OWNER TO postgres;

--
-- Name: album_albumid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE album_albumid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.album_albumid_seq OWNER TO postgres;

--
-- Name: album_albumid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE album_albumid_seq OWNED BY album.albumid;


--
-- Name: comment; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comment (
    commentid integer NOT NULL,
    commenterid integer,
    photoid integer,
    text text,
    datetime date,
    isnotified boolean
);


ALTER TABLE public.comment OWNER TO postgres;

--
-- Name: comment_commentid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comment_commentid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comment_commentid_seq OWNER TO postgres;

--
-- Name: comment_commentid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comment_commentid_seq OWNED BY comment.commentid;


--
-- Name: friendrequest; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE friendrequest (
    senderid integer,
    receiverid integer,
    datetime date,
    isnotified boolean
);


ALTER TABLE public.friendrequest OWNER TO postgres;

--
-- Name: friendship; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE friendship (
    userid1 integer,
    userid2 integer,
    startdatetime date,
    isnotified boolean
);


ALTER TABLE public.friendship OWNER TO postgres;

--
-- Name: person; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE person (
    personid integer NOT NULL,
    username character varying(32) NOT NULL,
    password character varying(32) NOT NULL,
    firstname character varying(32) NOT NULL,
    lastname character varying(32) NOT NULL,
    address character varying(200),
    birthday date,
    gender boolean,
    isactive boolean,
    imagepath character varying(200)
);


ALTER TABLE public.person OWNER TO postgres;

--
-- Name: person_personid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE person_personid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.person_personid_seq OWNER TO postgres;

--
-- Name: person_personid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE person_personid_seq OWNED BY person.personid;


--
-- Name: photo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE photo (
    photoid integer NOT NULL,
    albumid integer,
    caption text,
    locationpath character(100),
    thumbnailpath character(100),
    isrgb boolean,
    uploadtime date,
    feature double precision[]
);


ALTER TABLE public.photo OWNER TO postgres;

--
-- Name: photoLike; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE "photoLike" (
    userid integer,
    photoid integer,
    datetime date,
    isnotified boolean
);


ALTER TABLE public."photoLike" OWNER TO postgres;

--
-- Name: photo_photoid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE photo_photoid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.photo_photoid_seq OWNER TO postgres;

--
-- Name: photo_photoid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE photo_photoid_seq OWNED BY photo.photoid;


--
-- Name: albumid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY album ALTER COLUMN albumid SET DEFAULT nextval('album_albumid_seq'::regclass);


--
-- Name: commentid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comment ALTER COLUMN commentid SET DEFAULT nextval('comment_commentid_seq'::regclass);


--
-- Name: personid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY person ALTER COLUMN personid SET DEFAULT nextval('person_personid_seq'::regclass);


--
-- Name: photoid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY photo ALTER COLUMN photoid SET DEFAULT nextval('photo_photoid_seq'::regclass);


--
-- Name: album_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY album
    ADD CONSTRAINT album_pkey PRIMARY KEY (albumid);


--
-- Name: comment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT comment_pkey PRIMARY KEY (commentid);


--
-- Name: person_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY person
    ADD CONSTRAINT person_pkey PRIMARY KEY (personid);


--
-- Name: photo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY photo
    ADD CONSTRAINT photo_pkey PRIMARY KEY (photoid);


--
-- Name: album_userid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY album
    ADD CONSTRAINT album_userid_fkey FOREIGN KEY (userid) REFERENCES person(personid);


--
-- Name: comment_commenterid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT comment_commenterid_fkey FOREIGN KEY (commenterid) REFERENCES person(personid);


--
-- Name: comment_photoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT comment_photoid_fkey FOREIGN KEY (photoid) REFERENCES photo(photoid);


--
-- Name: friendrequest_receiverid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY friendrequest
    ADD CONSTRAINT friendrequest_receiverid_fkey FOREIGN KEY (receiverid) REFERENCES person(personid);


--
-- Name: friendrequest_senderid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY friendrequest
    ADD CONSTRAINT friendrequest_senderid_fkey FOREIGN KEY (senderid) REFERENCES person(personid);


--
-- Name: friendship_userid1_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY friendship
    ADD CONSTRAINT friendship_userid1_fkey FOREIGN KEY (userid1) REFERENCES person(personid);


--
-- Name: friendship_userid2_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY friendship
    ADD CONSTRAINT friendship_userid2_fkey FOREIGN KEY (userid2) REFERENCES person(personid);


--
-- Name: photoLike_photoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "photoLike"
    ADD CONSTRAINT "photoLike_photoid_fkey" FOREIGN KEY (photoid) REFERENCES photo(photoid);


--
-- Name: photoLike_userid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY "photoLike"
    ADD CONSTRAINT "photoLike_userid_fkey" FOREIGN KEY (userid) REFERENCES person(personid);


--
-- Name: photo_albumid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY photo
    ADD CONSTRAINT photo_albumid_fkey FOREIGN KEY (albumid) REFERENCES album(albumid);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

