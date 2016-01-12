# daily.rabbit.co.th

## Setup for local environment
Development url : http://dev.daily.rabbit.co.th

- Create your own wp-config.php file by using wp-config.example.php
- Use rabbit_world.sql for importing Rabbit Daily data.

Installing gulp js.
```
$ gulp install
```

__You might need to use images from staging server__
add .htaccess file in wp-content/upload folder
```
<IfModule mod_rewrite.c>
  RewriteEngine On

  RewriteBase /wp-content/uploads/
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*) http://staging-daily.rabbit.co.th/wp-content/uploads/$1 [L,P]

</IfModule>
```

## Customize a WordPress Theme
> As Outsourcing team use theme Wordpress name "Newspaper" for implemented this project.
We need to create child theme for update.
__wp-content/themes/Newspaper-child__

Otherwise as soon as you update the core, plugin, or theme, your changes will be wiped out.

## Customize a WordPress Plugin
https://iandunn.name/the-right-way-to-customize-a-wordpress-plugin/

- We should not break updating process of plugin.
- We should add patch to each plugin need change.
