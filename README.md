
# Job Board Project

## User Story 1  

As a HR manager I would like to go to job submission page, fill out a form and publish a job offer.  

COS:  

• New job form should contain title, description and email field 
• When I hit submit button, if this is my first job posting I should receive email saying that my submission is in moderation, otherwise it should be public/published.  


## User Story 2  

As a job board moderator i would like to receive email every time someone posts a job for a first time.  

COS: 

• Every time someone posts a job for a first time (based on email address) I should receive email about it
• Email notification should contain title and description of submission, as well as links to approve (publish) or mark it as a spam.  


### INSTALL

Set moderator credentials in .env file:

```
MAIL_USERNAME=moderator@gmail.com
MAIL_PASSWORD=moderator_password
```

Set database name and username in .env file

```
DB_DATABASE=database_name
DB_USERNAME=root
```

```
Run: composer update
```

```
php artisan migrate
```

Set moderator credentials in UsersTableSeeder.php:

```
'email' => 'moderator@gmail.com',
'password' => bcrypt('moderator_password'),
```

then

```
composer dump-autoload
```

```
php artisan db:seed
```
