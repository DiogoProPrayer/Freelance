# ltw14g01

## Features

**User:**
- [x] Register a new account.
- [X] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Freelancers:**
- [X] List new services, providing details such as category, pricing, delivery time, and service description, along with images or videos.
- [x] Track and manage their offered services.
- [x] Respond to inquiries from clients regarding their services and provide custom offers if needed.
- [x] Mark services as completed once delivered.

**Clients:**
- [X] Browse services using filters like category, price, and rating.
- [X] Engage with freelancers to ask questions or request custom orders.
- [X] Hire freelancers and proceed to checkout (simulate payment process).
- [X] Leave ratings and reviews for completed services.

**Admins:**
- [X] Elevate a user to admin status.
- [X] Introduce new service categories and other pertinent entities.
- [X] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [X] Real Time messaging (chat).
- [X] Feed to suggest services to the user.

## Running

    sqlite3 database/database.db < database/database.sql
    php -S localhost:9000

## Credentials

- admin/admin