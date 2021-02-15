======================================
 PARACHUTE BOILERPLATE: JAVASCRIPT
======================================
Hey there!

In order to tidy things up we've created this boilerplate theme for Wordpress although the general structure of this should be used across all our sites.

With respect to JavaScript I've tried to keep things as self-contained and ordered as possible so that code is easy to find and understand.

You should compartmentalise things depending on need and function as much as you can. However, if you have good reason to stray from this ordering system then feel free.

This eight-fold path of code is but a guide, only you can know the way to your code Shangri-La!

# Directory structure guide
js/
    - lib
        # Put all your third-party plugins and so on here
    - parachute
        # If it's something we've developed ourselves it goes here
        # For instance, all code for template blocks such as banners and content-listings goes here in their own namespaced file-name relevant to its block/usage
    - scripts-comp.js
        # Where you tell Pre-Pros what you want to compile into scripts.min.js and in what order
    - scripts.min.js
        # The primary JavaScript file across the site containing all first and third-party code

# Namespacing
All namespacing for our code will be under the "Parachute" namespace as defined in "js/parachute/app.js", like so:

var Parachute = {};

Parachute.UI = {};

Parachute.UI.Nav = {};

http://mikecavaliere.com/your-js-is-a-mess-javascript-namespacing/
http://crockford.com/javascript/code.html
https://google.github.io/styleguide/jsguide.html#naming-rules-common-to-all-identifiers