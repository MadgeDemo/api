<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SILVERSTONE API</title>

    <style>

    ::selection { background-color: #E13300; color: white; }
    ::-moz-selection { background-color: #E13300; color: white; }

    body {
        background-color: #FFF;
        margin: 40px;
        font: 16px/20px normal Helvetica, Arial, sans-serif;
        color: #4F5155;
        word-wrap: break-word;
    }

    a {
        color: #039;
        background-color: transparent;
        font-weight: normal;
    }

    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 24px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 16px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    #body {
        margin: 0 15px 0 15px;
    }

    p.footer {
        text-align: right;
        font-size: 16px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }

    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
    </style>
</head>
<body>

<div id="container">
    <h1>Routes</h1>

    <div id="body">

        <h2><a href="<?php echo site_url(); ?>">Home</a></h2>
        <p>
            Available Routes.
        </p>
        <ol>
            <li><?= @site_url('inventory/items'); ?> - Route for items</li>
            <li><?= @site_url('inventory/ledger'); ?> - Route for items ledger</li>
        </ol>
        <p>
            Available Formats.
        </p>
        <ol>
            <li>JSON (?format=json) - Default format</li>
            <li>XML (?format=xml) </li>
            <!-- <li>Array (?format=array) </li> -->
            <li>PHP (?format=php) </li>
            <li>HTML (?format=html) </li>
            <li>CSV (?format=csv) </li>
            <li>Serial (?format=serialized) </li>
        </ol>

        <p>
            Click on the links to check whether the REST server is working.
        </p>

        <ol>
            <li><a href="<?php echo site_url('inventory/items'); ?>">Inventory</a> - defaulting to JSON</li>
            <li><a href="<?php echo site_url('inventory/items/format/csv'); ?>">Inventory</a> - get it in CSV</li>
            <li><a href="<?php echo site_url('inventory/items/No/04850130000'); ?>">Inventory with No. Passed</a> - defaulting to JSON  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('inventory/items/No/04850130000/format/xml'); ?>">Inventory with No. Passed</a> - get in XML  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('inventory/items/No/04850130000?format=xml'); ?>">Inventory with No. Passed</a> - get in XML  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('inventory/ledger/No/04850130000'); ?>">Item Ledger</a> - defaulting to JSON  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('inventory/ledger/No/04850130000?format=xml'); ?>">Item Ledger</a> - get in XML  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('inventory/ledger/No/04850130000?format=array'); ?>">Item Ledger</a> - get in array  (items/No/04850130000)</li>
            <li><a href="<?php echo site_url('users/create'); ?>">User Registration</a> - post user details</li>
            <li><a href="<?php echo site_url('users/login'); ?>">User login</a> - post user credentials</li>
        </ol>

    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>'.CI_VERSION.'</strong>' : '' ?></p>
</div>

<script src="https://code.jquery.com/jquery-1.12.0.js"></script>

<script>
    // Create an 'App' namespace
    var App = App || {};

    // Basic rest module using an IIFE as a way of enclosing private variables
    App.rest = (function restModule(window) {
        // Fields
        var _alert = window.alert;
        var _JSON = window.JSON;

        // Cache the jQuery selector
        var _$ajax = null;

        // Cache the jQuery object
        var $ = null;

        // Methods (private)

        /**
         * Called on Ajax done
         *
         * @return {undefined}
         */
        function _ajaxDone(data) {
            // The 'data' parameter is an array of objects that can be iterated over
            _alert(_JSON.stringify(data, null, 2));
        }

        /**
         * Called on Ajax fail
         *
         * @return {undefined}
         */
        function _ajaxFail() {
            _alert('Oh no! A problem with the Ajax request!');
        }

        /**
         * On Ajax request
         *
         * @param {jQuery} $element Current element selected
         * @return {undefined}
         */
        function _ajaxEvent($element) {
            $.ajax({
                    // URL from the link that was 'clicked' on
                    url: $element.attr('href')
                })
                .done(_ajaxDone)
                .fail(_ajaxFail);
        }

        /**
         * Bind events
         *
         * @return {undefined}
         */
        function _bindEvents() {
            // Namespace the 'click' event
            _$ajax.on('click.app.rest.module', function (event) {
                event.preventDefault();

                // Pass this to the Ajax event function
                _ajaxEvent($(this));
            });
        }

        /**
         * Cache the DOM node(s)
         *
         * @return {undefined}
         */
        function _cacheDom() {
            _$ajax = $('#ajax');
        }

        // Public API
        return {
            /**
             * Initialise the following module
             *
             * @param {object} jQuery Reference to jQuery
             * @return {undefined}
             */
            init: function init(jQuery) {
                $ = jQuery;

                // Cache the DOM and bind event(s)
                _cacheDom();
                _bindEvents();
            }
        };
    }(window));

    // DOM ready event
    $(function domReady($) {
        // Initialise the App module
        App.rest.init($);
    });
</script>

</body>
</html>
