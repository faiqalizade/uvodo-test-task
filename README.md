# uvodo-test-task
Start Server
- php -S localhost:8000
Have used MAMP for MySQL
- after start server you should create user (it will create new table in DB)
Url for create User /api/users (POST)
name, surname, email (unique)
All users url: /api/users (GET)
One user info by Id: /api/users/:userId (GET)
DELETE user: /api/users/:userId (DELETE)
Edit user: /api/users/:userID (PUT)
in config/app.php you can choose DataSource _data_source_ (db, json, csv(**not available yet**))
Also can change path for save JSON or CSV Files
