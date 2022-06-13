START TRANSACTION;

DROP TABLE IF EXISTS `post`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    id       CHAR(36)     NOT NULL PRIMARY KEY,
    username VARCHAR(20)  NOT NULL,
    email    VARCHAR(50)  NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE `post`
(
    id         CHAR(36)     NOT NULL PRIMARY KEY,
    title      VARCHAR(255) NOT NULL,
    filename   VARCHAR(255),
    content    TEXT         NOT NULL,
    excerpt    TEXT                  DEFAULT NULL,
    slug       VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME
#     user_id    CHAR(36),
#     FOREIGN KEY (user_id)
#         REFERENCES user (id)
#         ON UPDATE CASCADE
#         ON DELETE SET NULL
);

COMMIT;