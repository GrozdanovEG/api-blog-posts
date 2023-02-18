CREATE DATABASE IF NOT EXISTS blogpostsapi;

USE blogpostsapi;

CREATE TABLE IF NOT EXISTS posts (
      id VARCHAR(96) NOT NULL UNIQUE,
      title VARCHAR(128) NOT NULL,
      slug VARCHAR(128) NOT NULL,
      content TEXT(20000) NOT NULL,
      thumbnail TEXT(20000),
      author VARCHAR(128) NOT NULL,
      posted_at DateTime NOT NULL,
      PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR(128) NOT NULL,
    description TEXT(2048),
    PRIMARY KEY (id)
    );

CREATE TABLE IF NOT EXISTS posts_categories (
    id_post VARCHAR(96) NOT NULL,
    id_category VARCHAR(64) NOT NULL,
    PRIMARY KEY (id_post, id_category),
    FOREIGN KEY (id_post) REFERENCES posts(id),
    FOREIGN KEY (id_category) REFERENCES categories(id)
);
