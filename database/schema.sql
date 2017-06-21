DROP TABLE IF EXISTS comment_scores;
DROP TABLE IF EXISTS allowed_scores;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS users;

CREATE TABLE users
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL
);
CREATE UNIQUE INDEX uk_users_name ON users (name);

CREATE TABLE articles
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  body TEXT NOT NULL,
  created_at DATETIME DEFAULT now() NOT NULL,
  CONSTRAINT fk_articles_user_id FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE comments
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  article_id INT NOT NULL,
  parent_comment_id INT,
  title VARCHAR(100) NOT NULL,
  body TEXT NOT NULL,
  created_at DATETIME DEFAULT now() NOT NULL
);

ALTER TABLE comments ADD CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE comments ADD CONSTRAINT fk_comments_article FOREIGN KEY (article_id) REFERENCES articles (id);
ALTER TABLE comments ADD CONSTRAINT fk_comments_parent FOREIGN KEY (parent_comment_id) REFERENCES comments (id);

CREATE TABLE comment_scores
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  comment_id INT NOT NULL,
  score TINYINT DEFAULT 0 NOT NULL,
  CONSTRAINT fk_comment_scores_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_comment_scores_comment FOREIGN KEY (comment_id) REFERENCES comments (id)
);

# restrict allowed scores on database level

CREATE TABLE allowed_scores
(
  score TINYINT PRIMARY KEY NOT NULL
);

INSERT INTO allowed_scores( score ) VALUES (-1),(0),(1),(2),(3);

ALTER TABLE comment_scores ADD FOREIGN KEY (score) REFERENCES allowed_scores (score);
