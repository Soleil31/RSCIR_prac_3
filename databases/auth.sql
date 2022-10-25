CREATE DATABASE IF NOT EXISTS auth;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT,UPDATE,INSERT ON auth.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE auth;
CREATE TABLE IF NOT EXISTS users_auth (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(20) NOT NULL,
  password VARCHAR(40) NOT NULL,
  PRIMARY KEY (ID)
);

INSERT INTO users_auth (username, password)
SELECT * FROM (SELECT 'Alex', 'ruA6grw1r8x.E') AS tmp
WHERE NOT EXISTS (
    SELECT username FROM users_auth WHERE username = 'Alex' AND password = 'ruA6grw1r8x.E'
) LIMIT 1;