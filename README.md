# Wordpress Workbench

## Why 

It gan be frustrating to collaborate on a project when the developers use a variety of architectures on thier local machine. Gitpod is a cloud solution that runs the environment in the cloud. You can use the browser based code editor or connect to the repo with a local editor. No need for complicated dependency installation on your machine.

## Ideas

* Patch file for adding autoloader into wp-config.php
* instructions on how to create a build to go to a hosting platform like wp-engine


## Structure 

* [Wordpress Composer Package](https://github.com/roots/wordpress-no-content)
* [Wordpress in its own directory](https://wordpress.org/support/article/giving-wordpress-its-own-directory/)

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