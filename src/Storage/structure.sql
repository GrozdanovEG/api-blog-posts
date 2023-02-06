
CREATE DATABASE IF NOT EXISTS blogposthandling;

USE blogposthandling;

CREATE TABLE IF NOT EXISTS posts (
      id VARCHAR(64) NOT NULL UNIQUE,
      title VARCHAR(196) NOT NULL,
      slug ...
      content TEXT(8000) NOT NULL,
      thumbnail VARCHAR(128),
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


