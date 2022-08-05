START TRANSACTION;

DROP TABLE IF EXISTS `post`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    id             CHAR(36)     NOT NULL PRIMARY KEY,
    username       VARCHAR(20)  NOT NULL,
    email          VARCHAR(50)  NOT NULL,
    password       VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255),
    is_admin       INT          NOT NULL DEFAULT 0,
    enabled        INT          NOT NULL DEFAULT 0,
    reset_token    VARCHAR(255)
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
    updated_at DATETIME,
    user_id    CHAR(36),
    FOREIGN KEY (user_id)
        REFERENCES user (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE `comment`
(
    id         CHAR(36) NOT NULL PRIMARY KEY,
    content    TEXT     NOT NULL,
    created_at DATETIME NOT NULL DEFAULT NOW(),
    user_id    CHAR(36),
    post_id    CHAR(36) NOT NULL,
    enabled    INT      NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id)
        REFERENCES user (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    FOREIGN KEY (post_id)
        REFERENCES post (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

COMMIT;
