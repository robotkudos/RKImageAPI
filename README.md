# RKImageAPI

RK Image API handles the backend for [rk-image-uploader](https://github.com/robotkudos/rk-image-uploader). It receives
api requests from **rk-image-uploader** and response securely.

## Install

`composer require robotkudos/rk-image-api`

## Usage

By default, **RKImageAPI** respond to `/api/image-api` with `auth:api` middleware. It will create a table named `images`
and will be ready as soon as it's been installed. All you need to do it run following command to create the images 
table:

`php artisan migrate`

## Config

If you need extra security (by adding more middlewares) or different api endpoint as well as changing the table name,
you can publish vendor config file with the following command:

`php artisan vendor:publish --provider='RobotKudos/RKImageAPI/RKImageAPIServiceProvider'`

This will copy config file into `config/rkimageapi.php`. You can change the default values there.

## APIs

#### POST /api/image-api
Receives the following 

    - image (FormData Image)
    - key (random stringto group images, e.g. by user, or listing, ...)
    - name
    - position (the position for sorting purpose)
    - comment (optional)
 
Response sample
    
    {
        err: false, 
        id: 123
    }
    

#### GET /api/image/api
Receives the following:

    - key

Response sample

    [
        {
            id: 123,
            name: sample_name,
            image_url: /img/sample_name.jpg,
            image_2x_url: /img/sample_name_2x.jpg,
            thumb_url: /img/sample_name_thumb.jpg,
            thumb_2x_url: /img/sample_name_thumb_2x.jpg,
            position: 1,
            comments: ...
         }
    ]
        
### DELETE /api/image-api
It deletes all images with the key.  

Receives the following:

    - key

Response sample

    {
        err: false
    }
    
### PUT /api/image-api/{id}

Edit the name of image.

Receives the following:

    - action: rename // currenty only support rename
    - name: the new name

Response sample

    {
        err: false,
        name: new name
    }

### PUT /api/image-api/reorder

Swap the position of two images.

Receives

    - id1
    - pos1
    - id2
    - pos2

Response sample

    {
        err: false
    }
    
### DELETE /api/image-api/{id}

Deletes the image

Receives no content

Response sample:

    {
        err: false
    }


## Dig deeper

**RKImageAPI** needs to receive a **key** to group images. It creates four images for each image it receives. One normal
size (by default 1500px, you can change it via config file), one double that size as Retina version, one thumb size (by 
default 180px, configurable via config file) and one double the thumb size for retina.

Under the hood, it uses **RKImage** package for resizing and saving the image.


