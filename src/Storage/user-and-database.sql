/* creating a new database */
CREATE DATABASE IF NOT EXISTS blogpostsapi;

USE m3webshopping;

/* creating a new user */
CREATE USER IF NOT EXISTS modprouser@localhost
    IDENTIFIED BY 'mpru456';

/* granting the necessary privileges */
GRANT ALL PRIVILEGES ON blogpostsapi.*
    TO 'modprouser'@'localhost'
    IDENTIFIED BY 'mpru456';