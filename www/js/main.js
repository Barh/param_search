$(function() {

    /**
     * Main
     * @constructor
     */
    ps_Main = function ()
    {
        var self = this;

        /**
         * Selectors
         * @type {Object}
         */
        this.selectors =
        {
            'point_submit'  : '.ps-point input[type=submit]',
            'filter_checkboxes' : '.ps-filters-main input[type=checkbox]',
            'filter_labels' : '.ps-filters-main label',
            'search_submit' : '.ps-filters-main input[type=submit]',
            'search_reset'  : '.ps-filters-main input[type=reset]'
        };

        /**
         * Values
         * @type {Object}
         */
        this.values =
        {
            'no_available_class' : 'no-available'
        };

        /**
         * Requests
         * @type {Object}
         */
        this.requests = {};

        /**
         * Init
         */
        this.init = function()
        {
            // init points and search
            self.init_points();
            self.init_search();
        };

        /**
         * Init points
         */
        this.init_points = function()
        {
            // click on submit
            $(self.selectors.point_submit).click(function() {

                // get form
                var form = $(this).parents('form').eq(0);

                // get parameters post
                var params_post = form.serializeArray();

                // Get parameters of address bar
                var params_get = document.ps_Variables.getParametersOfAddressBar();
                params_get['type'] = this.getAttribute('name');
                params_get['format'] = 'json';

                // available request
                if ( self.requests.update === undefined || self.requests.update.readyState === 4 )
                {
                    // send request
                    self.requests.update = $.ajax({
                        url       : document.ps_Variables.getStringParametersFromArray(params_get),
                        type      : 'POST',
                        dataType  : 'json',
                        traditional : true,
                        data      : params_post,
                        success   : function(data)
                        {
                            // message
                            alert(data.message);

                            // success
                            if (data.result)
                                // type
                                switch (params_get['type'])
                                {
                                    // insert
                                    case 'insert':
                                        delete(params_get['format']);
                                        delete(params_get['type']);
                                        params_get['id'] = data.id;
                                        window.location.href = document.ps_Variables.getStringParametersFromArray(params_get);
                                        break;
                                    // delete
                                    case 'delete':
                                        window.location.href = '.';
                                        break;
                                }
                        }
                    });
                }

                return false;
            });
        };

        /**
         * Init search
         */
        this.init_search = function()
        {
            // click on reset
            $(self.selectors.filter_checkboxes).change(function() {
                // get form
                var form = $(this).parents('form').eq(0);

                // get parameters form
                var params_get = form.serializeArray();
                params_get.push({'name' : 'format', 'value' : 'json'});

                // no properties
                if (params_get.length < 3 )
                    // remove `no available`
                    form.find('label').removeClass(self.values['no_available_class']);
                // is properties and available request
                else if ( self.requests.search === undefined || self.requests.search.readyState === 4 )
                {
                    // send request
                    self.requests.update = $.ajax({
                        url       : document.ps_Variables.getStringParametersFromArray(params_get),
                        type      : 'GET',
                        dataType  : 'json',
                        traditional : true,
                        success   : function(data)
                        {
                            // error message
                            if (!data.result)
                                alert(data.message);
                            // success
                            else
                            {
                                // add `no available`
                                form.find('label').addClass(self.values['no_available_class']);

                                // is available properties
                                if (typeof data.properties !== 'undefined')
                                {
                                    // remove `no available`
                                    for (var p_id in data.properties)
                                        for (var pv_id in data.properties[p_id])
                                            form.find('input[name="properties_id[' + p_id + '][' + pv_id  + ']"]').each(function() {
                                                $(this).parents('label').eq(0).removeClass(self.values['no_available_class']);
                                            });
                                }
                            }
                        }
                    });
                }


                console.log($(this).prop('checked'));
            });

            $(self.selectors.search_reset).click(function() {
                window.location.href = '.';
                return false;
            });

            // click on submit
            $(self.selectors.search_submit).click(function() {

                // get form
                /*var form = $(this).parents('form').eq(0);

                // get parameters form
                var params_get = form.serializeArray();
                params_get['format'] = 'json';

                // available request
                if ( self.requests.search === undefined || self.requests.search.readyState === 4 )
                {
                    // send request
                    self.requests.update = $.ajax({
                        url       : document.ps_Variables.getStringParametersFromArray(params_get),
                        type      : 'POST',
                        dataType  : 'json',
                        traditional : true,
                        data      : params_post,
                        success   : function(data)
                        {
                            // message
                            alert(data.message);

                            // success
                            if (data.result)
                            // type
                                switch (params_get['type'])
                                {
                                    // insert
                                    case 'insert':
                                        delete(params_get['format']);
                                        delete(params_get['type']);
                                        params_get['id'] = data.id;
                                        window.location.href = document.ps_Variables.getStringParametersFromArray(params_get);
                                        break;
                                    // delete
                                    case 'delete':
                                        window.location.href = '.';
                                        break;
                                }
                        }
                    });
                }
                // handle

                return false;*/
            });
        };
    };

    /**
     * Main
     * @constructor
     */
    ps_Variables = function ()
    {
        this.getParametersOfAddressBar = function()
        {
            /*
             * queryParameters -> handles the query string parameters
             * queryString -> the query string without the fist '?' character
             * re -> the regular expression
             * m -> holds the string matching the regular expression
             */
            var queryParameters = {}, queryString = location.search.substring(1),
                re = /([^&=]+)=([^&]*)/g, m;

            // Creates a map with the query string parameters
            while (m = re.exec(queryString)) {
                queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
            }

            return queryParameters;
        };

        this.getStringParametersFromArray = function( queryParameters )
        {
            /*
             * Replace the query portion of the URL.
             * jQuery.param() -> create a serialized representation of an array or
             *     object, suitable for use in a URL query string or Ajax request.
             */
            return window.location.pathname.toString() + '?' + $.param(queryParameters);
        };
    };

    // init
    document.ps_Main = new ps_Main(); document.ps_Main.init();
    document.ps_Variables = new ps_Variables();
});