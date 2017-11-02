## HashUpload

Hashes the name of all files uploaded via the CMS or use of `Upload`. 

Perfect for large sites that allow file uploads from potentially thousands of members

## Plug & Play 
This module is plug and play which means you only have to install it and it will begin hashing filenames after a dev/build

**Note: Hashing for existing files is NOT supported**

## Requirements
* SilverStripe ^4.0+

## Install
```bash
composer require vulcandigital\silverstripe-hashupload
```

## Caveat

The default configuration for this module uses `Injector` to replace the standard `SilverStripe\Assets\Upload` class. Any other module doing the same may and most likely will cause a conflict. 