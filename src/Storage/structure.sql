CREATE DATABASE IF NOT EXISTS blogpostsapi;

USE blogpostsapi;

CREATE TABLE IF NOT EXISTS posts (
      id VARCHAR(64) NOT NULL UNIQUE,
      title VARCHAR(128) NOT NULL,
      slug VARCHAR(128) NOT NULL,
      content TEXT(8000) NOT NULL,
      thumbnail TEXT(20000),
      author VARCHAR(128) NOT NULL,
      posted_at Date NOT NULL,
      PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(48) NOT NULL UNIQUE,
    name VARCHAR(96) NOT NULL,
    description TEXT(2048),
    PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS posts_categories (
    id_post VARCHAR(64) NOT NULL,
    id_category VARCHAR(48) NOT NULL,
    FOREIGN KEY id_post REFERENCES posts.id,
    FOREIGN KEY id_category REFERENCES categories.id
);


