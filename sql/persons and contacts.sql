CREATE TABLE persons
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL UNIQUE,
	ownerId INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (ownerId) REFERENCES users(id)
);

CREATE TABLE contacts
(
	id INT NOT NULL AUTO_INCREMENT,
	personId INT NOT NULL,
	contactType VARCHAR(50) NOT NULL,
	contactData VARCHAR(255) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (personId) REFERENCES persons(id)
)
