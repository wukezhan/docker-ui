# php-docker-ui

a web interface for docker powered by the [air framework](https://github.com/wukezhan/air)

## install

* include the conf/nginx-default.conf in your nginx.conf

```
server {
	listen   80;
	server_name your-server-name; # your server name
	root /your/real/docker-ui-path/web;
	index index.php index.html index.htm;

	access_log /your/real/docker-ui-path/logs/docker.ui.a.log;
	error_log /your/real/docker-ui-path/logs/docker.ui.e.log;

	location ~ ^/static/ {
		rewrite ^/static/(.*)$ /static/$1 break;
		expires 1d;
	}
	location ~ /favicon.ico {
		rewrite ^/(.*)$ /static/$1 break;
		expires 1d;
	}

	location / {
		rewrite (.*) /index.php?__=$1;
	}

	#PHP
    location ~ \.php$ {
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		fastcgi_pass 127.0.0.1:9000;
		fastcgi_index index.php;
		fastcgi_split_path_info ^(.+\.php)(.*)$;
		include fastcgi_params;
		try_files $uri =404;
    }

}

```

* make sure your pool user and group in your php-fpm.conf to be root

```
user = root
group = root
```

* start your nginx and php-fpm

* visit http://you-server-name/ (or your domain)



