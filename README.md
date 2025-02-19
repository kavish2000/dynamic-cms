# Setup the Dynamic CMS Project


Requirements
------------
- **Composer ( Minimum required version >=2.x )**
- **PHP ( Minimum required version 8.2 )** 
- **Laravel ( version >=11.x )**
- **NodeJS ( Minimum required version >=18.x )**
- **NPM ( Minimum required version >=9.x )**

Running the Project
------------
1. Clone Repositery
```bash
git clone https://github.com/kavish2000/dynamic-cms.git
cd dynamic-cms
```

2. Take a pull from **CMS Branch**
```bash
git pull origin cms
```

3. Update the Composer
```bash
composer update
```

4. Copy **.env.example** to **.env** with the follwing command
```bash
cp .env.example .env
```

5. Generate the Application Key with the following command
```bash
php artisan key:generate 
```

6. Update Following details in **.env** file
```bash
DB_CONNECTION=mysql
DB_HOST=your_host_name or 127.0.0.1
DB_PORT=your_port_name or 3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```
7. Install the NPM
```bash
npm install
```

8. Run migration command
```bash
php artisan migrate
```

9. Run seeder and optimize command
 ```bash
 php artisan db:seed
 php artisan o:c
 ```
 
 10. Running the project in local
 ```bash
 php artisan serve
 npm run dev
  ```

  11. Running the Test (TDD)
 ```bash
 php artisan test
  ```

# Logic  

- Deleting a parent page will automatically remove all its child pages.  

- The page structure will be displayed in a tree view. Clicking on a page will dynamically load its content along with its child pages.  

- If a child page has subpages, clicking on it again will expand its content and reveal its subpages.  
 