# Sweet Analytics Tracker

This extension implements Sweet Analytics Tracker for Magento 2.

## Installation

Installation can be done via uploading this extension to `app/code` directory or via composer:

### 1. Install
```bash
composer require sweet-analytics/sweet-tracker
```

### 2. Upgrade Magento
```bash
php bin/magento module:enable --clear-static-content YelHex_SweetTracker
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### 3. Production Environment
Please follow actual Magento Dev Docs to see how to deploy to production.

## Setup
Module settings are located under Stores -> Configuration -> Services -> Sweet Tracker.

You have to enable module and set given tracking ID to get plugin working. 

## Magento & PHP Version Support

* Magento 2.2+
  * Open Source (Community)
  * Commerce (Enterprise)
* PHP 7.1+
