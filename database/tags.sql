id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category INTEGER,
    FOREIGN KEY (category) REFERENCES Categories(id)
        ON DELETE CASCADE ON UPDATE CASCADE
Insert into Tags (id, name, category) values
(1, 'Web Development', 1),
(2,'Full Stack Development', 1),
(3, 'UI/UX Design', 1),
(4, 'Graphic Design', 2),
(5, 'Digital Marketing', 3),
(6,'Bug Fixing',1),
(7,'Logo Design',2),
(8,'Banner Design',2),
(9,'Figma Design',2),
(10,'Business Consulting',3),
(11,'Business Strategy',3);