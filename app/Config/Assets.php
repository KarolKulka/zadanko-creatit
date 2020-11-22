<?php namespace Config;

/***
 *
 * This file contains example values to override or augment default library behavior.
 * Recommended usage:
 *	1. Copy the file to app/Config/Assets.php
 *	2. Set any override variables
 *	3. Add additional route-specific assets to $routes
 *	4. Remove any lines to fallback to defaults
 *
 ***/

class Assets extends \Tatter\Assets\Config\Assets
{
    // Whether to continue instead of throwing exceptions
    public $silent = true;

    // Extensions to use when auto-detecting assets
    public $extensions = ['css', 'js'];

    // Location of asset files in the filesystem
    public $fileBase = FCPATH . 'assets/';

    // Location of asset URLs
    public $webBase = 'https://example.com/assets/';

    // Starting directory for manifest publication
    public $publishBase = ROOTPATH . 'vendor/';

    // Additional paths to load per route
    // Relative to fileBase, no leading/trailing slashes
    public $routes = [
        '' => [
            'css/bootstrap.min.css',
            'js/jquery.min.js',
            'js/bootstrap.bundle.min.js',
            'js/own.js',
        ],

    ];

    public function __construct()
    {

        $this->setWebBase();

    }

    public function setWebBase(){
        $this->webBase = site_url('assets');
    }

    public function addBaseAsset($pathToAsset){

        $this->routes[''][] = $pathToAsset;

    }

    public function getWebBase(){
        return $this->webBase;
    }

}
