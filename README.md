
# My light gallery
Preview at gallery.lajtkep.dev
Readme version 0.8
## How to deploy
###  1) Database
```
Run "LightGallery_mysql_create.sql" in phpMyAdmin //note id doesnt contain roles or users yet
```

### 2) VUE
```
open project root
tune .env.production
npm run build
copy files from "dist" directory into your server root folder
copy .htaccess to your server root
```

### 3) Api
```
copy folders "api, php, resources" into your server root folder
add and configure appConfig.ini
```

