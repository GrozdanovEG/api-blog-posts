# Blog  Posts Handling API

## Description
A blog posts management API. It allows posts grouped by categories with ability each post to use own thumbnail.  

## Getting started - Installation and initial configuration

- The application expects PHP 8.0+ and MySQL compatible DBMS;
- To run the application it is necessary to create the databases and tables,
  executing the queries inside the files `/src/Storage/user-and-database.sql` and `/src/Storage/structure.sql`;

The following commands can be used for quick set up:  
```shell
$ git clone https://gitlab.com/GrozdanovEG/php4-blog-api.git
$ cd php4-blog-api/
$ composer install
```

## Features 
- CRUD posts;  
- Each post can use its own thumbnail;
- CRUD categories;  
- Post can be accessed by a slug; 
- Posts may be grouped in categories;  
- The API want has basic documentation accessible via web browser;  


## Application Routes - brief description 
GET: `/v1` - Application home route;  
POST: `/v1/new/category'` - Adding a new category to the blog;  
GET: `/v1/read/category/{id}` - Fetching a category data by given id route;  
PUT: `/v1/update/category/{id}` - Updating a category data by given id route;  
GET: `/v1/list/categories` - List all existing categories route;  
DELETE: `/v1/delete/category/{id}` - Deleting a category from the blog by given id route;  
POST: `/v1/new/post'` - Adding a new post to the blog route;  
GET: `/v1/read/post/{id}` -  Fetching a post data by given id route;  
PUT: `/v1/update/post/{id}` - Updating a post data by given id route;  
DELETE: `/v1/delete/post/{id}` - Deleting a post from the blog by given id route;  
GET: `/v1/posts/slug/{slug}` - Fetching a post data by given slug route;  
POST: `/v1/post/{pid}/addto/{cid}` - Adding a category to a post route;  
GET: `/v1/apidocs` - Getting the API docs in JSON format;

### API Documentation
The documentation in HTML format is accessible on the address: `http://HOSTNAME/ApiDocsUI/`  

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
* if `HOST_ROOT_URI` is not specified in the `.env` configuration the value of the
 superglobal `$_SERVER['HTTP_HOST']` will be used;  

## Links / Where to Find
Project homepage:  N/A  
Repository: <https://gitlab.com/GrozdanovEG/php4-blog-api>  



