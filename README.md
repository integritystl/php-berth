# Berth


It gan be frustrating to collaborate on a project when the developers use a variety of architectures on thier local machine. Using a common Docker based development environment can help. Additionally, Gitpod is a cloud solution that runs the environment in the cloud. You can use the browser based code editor or connect to the repo with a local editor. No need for complicated dependency installation on your machine.

## Ideas

* instructions on how to create a build to go to a hosting platform like wp-engine
* "composer merge"

## Init 

MVP Start-up steps:

0. 
```
bin/berth
```

1. 
```
docker-compose up -d
```

2. 
run the wordpress cli install or use the UI
```
wp core install --url="localhost"  --title="Blog Title" --admin_user="admin" --admin_password="admin" --admin_email="mike.pisula@integrityxd.com"
```


## Structure 

* [Wordpress Composer Package](https://github.com/roots/wordpress-no-content)
* [Wordpress in its own directory](https://wordpress.org/support/article/giving-wordpress-its-own-directory/)
* [Wordpress + Docker Quickstart](https://github.com/docker/awesome-compose/tree/master/official-documentation-samples/wordpress/)

## Configure Wordpress Core

Do it with Patches! Leave core alone!


## Developing a new Wordpress Plugin or Theme

When developing a new Wordpress plugin or theme you may choose to commit it to your repository during development rather than having to maintain multiple git repositories. 

### Create a local package

1. Create a new package folder in the packages directory, for example `packages/my-package`.

2. Add a "Path" composer repository in the root composer.json file. 

    ```
    {
        "repositories": [
            {
                "type": "path",
                "url": "./packages/my-package"
            }
        ],
        "require": {
            "my/package": "*"
        }
    }
    ```

3. Create a composer.json file in your package at `. If it is a plugin specify "type" as "wordpress-plugin". If its is a theme specify "type" as "wordpress-theme".

    ```
    {
        "name": "my/package",
        "description": "My custom package.",
        "type": "wordpress-plugin",
        "require": {}
        ...
    }
    ```