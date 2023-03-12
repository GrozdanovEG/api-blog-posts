![logo](http://uri.site/image)
# Blog  Posts Handling API

## Description

## Getting started - Installation and initial configuration

```shell
$ git clone https://gitlab.com/GrozdanovEG/php4-blog-api.git
$ cd php4-blog-api/
$ composer install
```

## Features

## Application Routes - brief description 
GET: `/v1` - ;  
POST: `/v1/new/category'` -  ;  
GET: `/v1/read/category/{id}` - ;  
PUT: `/v1/update/category/{id}` - ;  
GET: `/v1/list/categories` - ;  
DELETE: `/v1/delete/category/{id}` - ;  
POST: `/v1/new/post'` - ;  
GET: `/v1/read/post/{id}` -  ;  
PUT: `/v1/update/post/{id}` - ;  
DELETE: `/v1/delete/post/{id}` - ;  
GET: `/v1/posts/slug/{slug}` - ;  
POST: `/v1/post/{pid}/addto/{cid}` - ;  
GET: `/v1/apidocs` - ;

### API Documentation

## Configuration

Using environment variables for application configuration inside `.env` file

Example:  
```ENV
DB_TYPE=mysql
DB_HOST=localhost
DB_NAME=blogpostsapi
DB_USER=modprouser
DB_PASS=*****
DB_PORT=3306

HOST_ROOT_URI=http://devserv:8112
HOST_THUMBNAILS_PATH=/thumbnails
```
* if `HOST_ROOT_URI` is not specified in the `.env` configuration the value of the superglobal `$_SERVER['HTTP_HOST']` will be used;  

## Links / Where to Find
Project homepage:  N/A  
Repository: <https://gitlab.com/GrozdanovEG/php4-blog-api>  

## Licensing



