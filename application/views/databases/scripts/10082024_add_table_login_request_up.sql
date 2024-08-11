CREATE TABLE visa_profile (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    valid_until DATETIME NULL,
    code CHAR(8) NULL,
);
