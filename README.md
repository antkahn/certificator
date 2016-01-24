Symfony Certificator
=====================

#Installation

Clone the repository
```
git clone git@github.com:antkahn/certificator.git
```
Build the vagrant
```
vagrant up
```

Connect to the vagrant
```
vagrant ssh
```

Update the database
```
cd /var/www/certificator
app/console d:m:m -n
```

Load the fixtures
```
app/console d:f:l -n
```

Change the vagrant's nginx configuration to
```
server {
    server_name certificator.vb;
    root /var/www/certificator/web;

    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /app.php$is_args$args;
    }
    # DEV
    # This rule should only be placed on your development environment
    # In production, don't include this and don't deploy app_dev.php or config.php
    location ~ ^/(app_dev|config)\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        # When you are using symlinks to link the document root to the
        # current version of your application, you should pass the real
        # application path instead of the path to the symlink to PHP
        # FPM.
        # Otherwise, PHP's OPcache may not properly detect changes to
        # your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        # for more information).
        fastcgi_param  SCRIPT_FILENAME  $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
    }
    # PROD
    location ~ ^/app\.php(/|$) {
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        # When you are using symlinks to link the document root to the
        # current version of your application, you should pass the real
        # application path instead of the path to the symlink to PHP
        # FPM.
        # Otherwise, PHP's OPcache may not properly detect changes to
        # your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
        # for more information).
        fastcgi_param  SCRIPT_FILENAME  $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        # Prevents URIs that include the front controller. This will 404:
        # http://domain.tld/app.php/some-path
        # Remove the internal directive to allow URIs like this
        internal;
    }

    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;
}
```

Restart the nginx service
```
sudo service nginx restart
```

Access the website at http://192.168.33.99/
