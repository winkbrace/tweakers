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

#####################################################
## restrict allowed scores on database level
#####################################################

CREATE TABLE allowed_scores
(
  score TINYINT PRIMARY KEY NOT NULL
);

INSERT INTO allowed_scores( score ) VALUES (-1),(0),(1),(2),(3);

ALTER TABLE comment_scores ADD FOREIGN KEY (score) REFERENCES allowed_scores (score);

#####################################################
## insert test data
#####################################################

insert into users values
  (1, 'Abe'),
  (2, 'Ben'),
  (3, 'Chris');

insert into articles values
  (1, 1, 'Once upon a time', 'lorem ipsum', now()),
  (2, 1, 'Shawshank redemption', 'lorem ipsum', now()),
  (3, 1, 'The Hulk', 'lorem ipsum', now());

insert into comments
  (id, user_id, article_id, parent_comment_id, title, body, created_at)
  values
  (1, 2, 1, null, 'boo', 'I disagree.', now()),
  (2, 2, 1, null, 'aw yeah', 'Good movie.', now()),
  (3, 3, 1, 1, 'wut?', 'I agree.', now()),
  (4, 3, 3, null, 'green', 'Bruce Banner is even more bad ass.', now());

insert into comment_scores
  (id, user_id, comment_id, score)
  values
  (1, 1, 1, -1),
  (2, 1, 2, 2),
  (3, 1, 4, 3),
  (4, 2, 3, -1),
  (5, 2, 4, 2),
  (6, 3, 1, -1),
  (7, 3, 2, 0);
