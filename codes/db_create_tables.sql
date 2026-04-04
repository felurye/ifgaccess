CREATE TABLE users (
    id         INTEGER      PRIMARY KEY AUTO_INCREMENT,
    tag        VARCHAR(8)   NOT NULL UNIQUE,
    enrollment VARCHAR(14)  NOT NULL,
    name       VARCHAR(80)  NOT NULL,
    email      VARCHAR(45),
    phone      VARCHAR(11),
    INDEX idx_tag (tag),
    INDEX idx_enrollment (enrollment)
);

CREATE TABLE access (
    id       INTEGER      PRIMARY KEY AUTO_INCREMENT,
    user_id  INTEGER      NOT NULL,
    room     VARCHAR(10)  NOT NULL,
    checkin  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    checkout TIMESTAMP    NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_room_checkout (room, checkout)
);
