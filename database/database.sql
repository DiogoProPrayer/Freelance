PRAGMA foreign_keys = ON;

-- Drop tables in correct order (children → parents) --
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS ServiceOrder;
DROP TABLE IF EXISTS ServiceTags;
DROP TABLE IF EXISTS ServiceImages;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Tags;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS User;

-- User table --
CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    birthDay DATE,
    email TEXT UNIQUE NOT NULL,
    passwordHash TEXT NOT NULL,
    profileImage TEXT,
    country TEXT,
    phoneNumber TEXT,
    userStatus TEXT CHECK (userStatus IN ('client', 'seller', 'admin')) NOT NULL DEFAULT 'client',
    isAdmin INTEGER CHECK (isAdmin IN (0, 1)) DEFAULT 0,
    ratings REAL DEFAULT 0,
    currency TEXT
);

-- Categories table
CREATE TABLE Categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL
);

-- Tags table
CREATE TABLE Tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category INTEGER,
    FOREIGN KEY (category) REFERENCES Categories(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Services table
CREATE TABLE Service (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    seller INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    price REAL NOT NULL,
    category INTEGER,
    deliverTime INTEGER,
    FOREIGN KEY (seller) REFERENCES User(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (category) REFERENCES Categories(id)
        ON DELETE SET NULL ON UPDATE CASCADE
    rating REAL DEFAULT 0,
);

-- ServiceImages (one-to-many)
CREATE TABLE ServiceImages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    service INTEGER NOT NULL,
    image TEXT NOT NULL,
    FOREIGN KEY (service) REFERENCES Service(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);


-- ServiceTags (many-to-many)
CREATE TABLE ServiceTags (
    service INTEGER NOT NULL,
    tag INTEGER NOT NULL,
    PRIMARY KEY (service, tag),
    FOREIGN KEY (service) REFERENCES Service(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tag) REFERENCES Tags(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Orders
CREATE TABLE ServiceOrder (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    service INTEGER NOT NULL,
    buyer INTEGER NOT NULL,
    orderStatus TEXT NOT NULL,
    rating INTEGER CHECK (rating BETWEEN 1 AND 5),
    original_days INTEGER NOT NULL DEFAULT 0,
    remaining_days INTEGER NOT NULL DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    review TEXT,
    FOREIGN KEY (service) REFERENCES Service(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (buyer) REFERENCES User(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Messages
CREATE TABLE Messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender     INTEGER NOT NULL,
    receiver   INTEGER NOT NULL,
    text       TEXT NOT NULL,
    timeStamp  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender)   REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (receiver) REFERENCES User(id) ON DELETE CASCADE ON UPDATE CASCADE
);



--Triggers--

CREATE TRIGGER initialize_delivery_days
AFTER INSERT ON ServiceOrder
BEGIN
    UPDATE ServiceOrder 
    SET 
        original_days = (SELECT deliverTime FROM Service WHERE id = NEW.service),
        remaining_days = (SELECT deliverTime FROM Service WHERE id = NEW.service)
    WHERE id = NEW.id;
END;
-- Triggers

CREATE TRIGGER IF NOT EXISTS service_rating
AFTER Update ON ServiceOrder
when New.rating IS  NOT NULL
BEGIN
    UPDATE Service
    SET rating = (SELECT AVG(rating) FROM ServiceOrder WHERE service = New.service)
    WHERE id = New.service;
END;

CREATE TRIGGER IF NOT EXISTS USER_RATING
AFTER UPDATE ON service
WHEN New.rating IS NOT NULL
BEGIN
    UPDATE User
    SET rating = (SELECT AVG(rating) FROM Service WHERE seller = New.seller)
    WHERE id = New.seller;
END;


CREATE TRIGGER IF NOT EXISTS service_updates
AFTER DELETE ON Service
BEGIN
    DELETE FROM ServiceOrder WHERE service = Old.id;
    DELETE FROM ServiceTags WHERE service = Old.id;
    DELETE FROM ServiceImages WHERE service = Old.id;
END;