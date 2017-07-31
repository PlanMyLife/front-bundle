# Plan My Life Front Bundle

## Introduction

This bundle allow you to **install a gulp compilator** for your assets and **add to your symfony's console** some functions to execute gulp tasks.

## Dependancies

*You can't build* your assets if you didn't install the following required programs on your server.
1. Bundler[http://bundler.io]
2. npm[https://www.npmjs.com/]


## Installation

### 1 - Get the bundle

To install this bundle run the following command in your symfony's project :

`composer require planmylife/front-bundle dev-master`

### 2 - Configuration

Add in your config.yml, the following config :

```
pml_front_generator:
    engine: 'gulp'
    path:
        - {src: 'src/Project/MainBundle/Resources/', name: 'main'}
        - {src: 'src/Project/AdminBundle/Resources/', name: 'admin'}
```

*   **engine** : *[String]* Define the engine you want use. At this moment only 'gulp' will work
*   **path**: *[Array]* This is the most important rule for the bundle. You need to add the link of your assets and the name
*   **src**: *[String]* The path where your assets will be installed
*   **name**: *[String]* The reference of your bundle, will be use for your generated assets. It will prevent override effect of same file name in your bundles

### 3 - Install the assets architecture

To install a first front architecture, execute this command :
 
```php bin/console front:generate```

*Warning* : You should execute this command only one time

### 4 - Build assets

Now you can build your asset. Run this command

```php bin/console front:install```

This command will check if your environment can build your assets then clean your older files and rebuild every bundle from your config.yml

### 5 - Watch

If you want work on one bundle, you can run watch task with this command :

```php bin/console front:watch name='main'```

*Warning* : The watcher can only watch one bundle at a time. That is why you have to precise to the command what bundle it should watch by passing the name of the path from your config.yml file.
