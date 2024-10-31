/**
 * This file is being loaded by hook prefix and not always available even to this plugin please check code
 *
 *
 */

jQuery(document).ready(function($) {
	/*
	* global var within this function
	*/
    var ed, editor;

    /**
    * event handlers init
    */
    function init_plugin_scripts() {
    	/*
    	* Show company info toggle handler
    	*/
        $('#category_id').bind('change', function(e) {

            var selected = $(e.target).find(':selected')[0];
            if (selected.value > 1) {
                if ($('#show_company_info').attr('checked')) {
                    $('#show_company_info').trigger('click');
                }
            } else {
                if (!$('#show_company_info').attr('checked')) {
                    $('#show_company_info').trigger('click');
                }
            }
        });

        /**
        * Company dropdown handler
        */
        $('#company_id').bind('change', function() {
            // update company fields
            var $spinner = $('<span class="spinner" style="display: inline-table !Important; float: none"></span>');

            $(this).after($spinner);

            $.when($.getJSON(ajaxurl + '?action=newswire_get_company_profile&page_id=' + this.value))

            .done(function(data) {
                var page = data;
                /*
						$company_website.val(site.company_website);
						$company_email.val(site.company_email);
						$company_telephone.val(site.company_telephone);
						$company_zip.val(site.company_zip);
						$company_state.val(site.company_state);
						$company_country.val(site.company_country);
						$company_city.val(site.company_city);
						$company_address.val(site.company_address);
						$company_tickers.val(site.company_tickers);
						$company_name.val(site.company_name);
	*/
                //$('save_bn').show();
                $('#schema_id').val(page.schema_id);
                $('#schema_id').trigger('change');
                $('#company_name').val(page.title);
                $('#company_name').trigger('keyup');

                $('#company_tickers').val(page.tickers);
                $('#company_tickers').trigger('keyup');

                $('#company_address').val(page.address);
                $('#company_address').trigger('keyup');

                $('#company_city').val(page.city);
                $('#company_city').trigger('keyup');

                $('#company_state').val(page.state);
                $('#company_state').trigger('keyup');


                $('#company_country').val(page.country);
                $('#company_country').trigger('change');

                $('#company_zip').val(page.postal_code);
                $('#company_zip').trigger('keyup');

                $('#company_telephone').val(page.phone);
                $('#company_telephone').trigger('keyup');

                $('#company_email').val(page.email);
                $('#company_email').trigger('keyup');

                $('#company_website').val(page.website);
                $('#company_website').trigger('keyup');

                $('#company_desc').val(page.company_desc);
                $('#company_desc').fireEvent('keyup');

                //$('save_bn').innerHTML = '&#10004; Save Description to Company Listing Page';
                //$('save_bn').addClass('saved');*/

                //console.log(data);
                $spinner.remove();

            });
        });

		/**
		* Remember tabs
		*/
        $(function() {
            function getUrlVars() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars;
            }

            //  Define friendly index name
            var index = 'key';
            //  Define friendly data store name
            var dataStore = window.sessionStorage;

            //  Start magic!
            try {
                // getter: Fetch previous value
                var oldIndex = dataStore.getItem(index);
            } catch (e) {
                // getter: Always default to first tab in error state
                var oldIndex = 0;
            }

            if (getUrlVars()['tab'] == 'api') {
                var oldIndex = 0;
            }
            $('#newswire-settings').tabs({
                // The zero-based index of the panel that is active (open)
                active: oldIndex,
                // Triggered after a tab has been activated
                activate: function(event, ui) {
                    //  Get future value
                    var newIndex = ui.newTab.parent().children().index(ui.newTab);
                    //  Set future value
                    dataStore.setItem(index, newIndex)
                }
            });
        });

        // setup tabs
        $('#newswire-article-meta-tab').tabs();
        //$('#newswire-settings').tabs();

        //date picker
        $("#newswire_publish_date").datepicker();
        $(".date-picker").datepicker();

        /** 
         * Initialize tooltip to newswire post meta box only
         */
        $('#newswire-post-meta-box', contextEl).tooltip({
            
            hide: 1500
        });
        
        /**
         * Medi mods for file upload in newswire publisher meta box
         */
        function newswire_media() {

            var file_frame;
            jQuery('.newswire_update_image').live('click', function(event) {
                event.preventDefault();

                // If the media frame already exists, reopen it.
                /* if ( file_frame ) {
			      file_frame.open();
			      return;
			    }*/

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: jQuery(this).data('uploader_title'),
                    button: {
                        text: jQuery(this).data('uploader_button_text'),
                    },
                    multiple: false // Set to true to allow multiple files to be selected
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    if (console)
                        console.log(attachment);

                    //set image preview
                    if (typeof attachment.url) {
                        $('.newswire-image-preview').html('<img src=' + attachment.url + '>');
                        $('#newswire-add-image input[name="newswire_data[img_url]"]').val(attachment.url);
                        //http://local.wordpress.dev/wp-admin/post.php?post=192&action=edit&message=10$('#newswire-add-image input[name="newswire_data[_set_as_featured]"]'.val(attachment.id))


                    } else if (attachment.sizes && attachment.sizes.url) {
                        $('.newswire-image-preview').html('<img src=' + attachment.sizes.url + '>');
                        $('#newswire-add-image input[name="newswire_data[img_url]"]').val(attachment.sizes.url);

                    }

                    $('#newswire-add-image input[name="newswire_data[img_caption]"]').val(attachment.caption || attachment.title || '');
                    $('#newswire-add-image input[name="newswire_data[img_alt_tag]"]').val(attachment.alt || attachment.title || attachment.caption || '');
                    $('#newswire-add-image input[name="newswire_data[img_caption_link]"]').val(attachment.link || '');
                    $('#newswire-add-image input[name="newswire_data[img_alt_tag_link]"]').val(attachment.link || '');
                    $('#newswire-add-image input[name="newswire_data[_set_as_featured]"]').val(attachment.id);

                    // Do something with attachment.id and/or attachment.url here

                });

                // Finally, open the modal
                file_frame.open();

            });
        }

        /*
         * tooggle fields hanlder
         */
        $('.toggle').find(':checkbox').live('click', function(event) {
            if ($(this).attr('checked')) {
                $(this).siblings().show();
            } else {
                $(this).siblings().hide();
            }
        });
        newswire_media();

    }//end function
    init_plugin_scripts();

    /** instantiate newswire object for admin used */
    //var newswireUrl = 'http:/www.newswire.net';
    var newswireUrl = 'http:/www.newswire.net';
    //var newswireUrl = 'http:/local.newswire.net';	


    var month = new Array();
	    month[0] = "January";
	    month[1] = "February";
	    month[2] = "March";
	    month[3] = "April";
	    month[4] = "May";
	    month[5] = "June";
	    month[6] = "July";
	    month[7] = "August";
	    month[8] = "September";
	    month[9] = "October";
	    month[10] = "November";
	    month[11] = "December";
    var placholder = {
    	header: '',
        //header: '<p id="article_header">(<a href="'+ siteinfo.site_url +'" data-mce-href="'+siteinfo.site_url+'">'+ siteinfo.name +'</a> -- ' + nowString + ') <span id="header_city_state"><span itemprop="addressLocality"></span> <span itemprop="addressRegion"></span> </span>--&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>',
        footer_old: '<div class="mceNonEditable" id="article_footer" ><div id="company_nap" itemscope itemtype="http://schema.org/LocalBusiness">' + '<h3><span id="article_footer_name" itemprop="name">{CompanyName}</span></h3>' + '<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">' + '<span id="article_footer_address" itemprop="streetAddress">{Address}</span><br />' + '<span id="article_footer_city" itemprop="addressLocality">{City}</span>,' + '<span id="article_footer_state" itemprop="addressRegion">{State}</span> ' + '<span id="article_footer_country" itemprop="addressCountry">{Country}</span>' + '<span id="article_footer_zip" itemprop="postalCode">{PostalCode}</span>' + '</address>' + 'Phone: <span id="article_footer_telephone" itemprop="telephone">{Phone}</span><br />' + 'Email: <span id="article_footer_email" itemprop="email" >{Email}</span><br />' + 'Website: <span id="article_footer_website" itemprop="url">{Website}</span>' + '</div>' + '</div>',
        footer: '<div style="display:none" class="mceNonEditable" id="company_desc_wrapper">' + '<h3>About <span id="company_title">&nbsp;</span></h3>' + '<p id="company_desc">&nbsp;</p>' + '</div>' + '<div id="company_nap" class="mceNonEditable" itemscope="" itemtype="http://schema.org/LocalBusiness"><h3><span id="article_footer_name" itemprop="name">&nbsp;</span></h3><address itemscope="" itemtype="http://schema.org/PostalAddress" itemprop="address"><span id="article_footer_address" itemprop="streetAddress">&nbsp;</span><br><span id="footer_city_state"></span><span id="article_footer_country" itemprop="addressCountry">&nbsp;</span><span id="article_footer_zip" itemprop="postalCode">&nbsp;</span></address><span id="article_footer_telephone" itemprop="telephone">&nbsp;</span><br><span id="article_footer_email" itemprop="email">&nbsp;</span><br><span id="article_footer_website" itemprop="url">&nbsp;</span></div>'
    }

    //THis not being displayed -no need just a placholder
    var cont = $(document.createElement('div'), {
        style: 'display:none',
        id: 'article_body_cont'
    });
    cont.html('');

    $company_website = $('#company_website');
    $company_email = $('#company_email');
    $company_telephone = $('#company_telephone');
    $company_zip = $('#company_zip');
    $company_state = $('#company_state');
    $company_country = $('#company_country');
    $company_city = $('#company_city');
    $company_address = $('#company_address');
    $company_tickers = $('#company_tickers');
    $company_name = $('#company_name');
    $company_desc = $('#nwexpress_company_desc');

    var $show_footer = $('#show_company_info');



    /**
    * GLOBAL OBJECT
    */
    var nw = window.newswire = {
        /**
         * Run when tinymce is initialized
         */
        tinymce_init: function(ed) {
            $show_footer.change(function(e) {

                cont.html(ed.getContent());
                $footer = $('#company_nap', cont);
                if (this.checked) {
                    $('#company_id').parents('tr').show();
                    $footer.show();
                    $('.toggle_company_info').show();
                } else {
                    $footer.hide();
                    $('.toggle_company_info').hide();
                    $('#company_id').parents('tr').hide();
                }
                ed.setContent(cont.html());
            });
            //Dirty hack
            $show_footer.trigger('click');
            $show_footer.trigger('click');
            /**
            *
            */
            $('#content-html').click(function(e) {
                //ed.getContent();
                //remove header and footer
                //this._removeHeaderFooter();
                //alert("re,pve");
            });
        },
        /**
         * This is called from edit.php and when you switch from html/text view in wordpress
         */
        tinymce_onloadcontent: function(ed) {
            var editor = ed;

            function update_date_header() {
                //return;
                function get_dateline() {
                    var now = new Date(jQuery('#aa').val() + '-' + jQuery('#mm').val() + '-' + jQuery('#jj').val());

                    var nowString = month[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear();

                    //console.log(jQuery('#aa').val() +'-'+jQuery('#mm').val()+'-'+ jQuery('#jj').val());
                    // var date = new Date(jQuery('#aa').val() +'-'+jQuery('#mm').val()+'-'+ jQuery('#jj').val())  ;
                    var city = $('#company_city').val();
                    var state = $('#company_state').val();
                    var dateline = '(<a href="' + siteinfo.site_url + '" rel="dofollow">' + siteinfo.name + '</a> -- ' + nowString + ') ';
                    if (city) {
                        dateline += city;
                        if (state) dateline += ', ';
                    }
                    if (state) dateline += state;
                    dateline += ' --';
                    return dateline;
                }
                /*
				function update_dateline()
				{
				  var contents = get_editor_contents();
				  var dateline = get_dateline();
				  var p = contents.getElement('>p');
				  if (p && p.innerHTML.match(/^.*--/)) {
				    p.innerHTML = p.innerHTML.replace(/^.*--/, dateline);
				  } else {
				    new Element('p', { html: dateline }).inject(contents, 'top');
				  }
				  tinyMCE.get('body').setContent(contents.innerHTML);
				  updateCount();
				}
	*/
                //check if header or footer is already there
                //var ed_html = ;
                var div = jQuery('<div>');
                var ed_html = jQuery(ed.getContent());
                div.append(ed_html);
                //console.log(ed_html);
                var p = ed_html.filter('p:first');
                //console.log(ed_html);
                //console.log(p);
                var dateline = get_dateline();
                var stringP = p.html();


                if (p.length && stringP.match(/^.*--/) && jQuery('#aa').length) {
                    //alert('test');
                    //var pEl = p.innerHTML.match(/^.*--/);
                    p.html(stringP.replace(/^.*--/, dateline));
                    //consol
                    //console.log(p);
                    // ed_html.filter('p:first').replaceWith(p);
                    //console.log(ed_html);
                    //	ed_html = jQuery(ed.getContent()).find('p:first').replaceWith(p)[0].outerHTML;
                    //console.log( jQuery(ed.getContent()).find('p:first') );
                    //	ed_html = ed_html.html();
                    //console.log(jQuery('p:first', ed_html.parent()));
                    //	alert('test');
                    //p.relaceWidt
                    //ed_html = ed.getContent();
                    //ed_html.replace(/^.*--/, dateline);
                    //ed_html[0] = p;
                    //ed_html =
                    ed_html = div.html();
                } else {
                    //new Element('p', { html: dateline }).inject(contents, 'top');
                    //var p = jQuery('p#artcile_header').html(dateline);
                    var p = jQuery('<p id="article_header">').html(dateline)[0];
                    //console.log());
                    ed_html = p.outerHTML + ed.getContent();
                }

                if (!jQuery('#aa').length || !jQuery('#mm').length) {
                    ed_html = jQuery(ed.getContent());
                }


                /*
				if ( $(ed_html).filter('#article_header').length )  {
					
					

				} else {

					//insert
					ed_html  = placholder.header + ed_html;

				} */


                //find footer
                if ($(ed_html).filter('#company_nap').length) {

                } else {

                    //insert
                    ed_html = ed_html + placholder.footer;
                }

                ed.setContent(ed_html);
            }

            jQuery('#publish').click(function() {
                //alert('esdf');
                update_date_header(editor);
            })
            //call 
            update_date_header(ed);



            function update_footer() {
                cont.html(ed.getContent());
                var html = '';

                if ($company_city.val() || $company_state.val()) {

                    if ($company_city.val() && $company_state.val()) {
                        html = '<span itemprop="addressLocality">' + $company_city.val() + '</span>, <span itemprop="addressRegion">' + $company_state.val() + '</span> ';
                    } else if ($company_city.val()) {
                        html = '<span itemprop="addressLocality">' + $company_city.val() + '</span> ';
                    } else if ($company_state.val()) {
                        html = '<span itemprop="addressRegion">' + $company_state.val() + '</span> ';
                    }
                }

                $('#footer_city_state', cont).html(html);

                ed.setContent(cont.html());
            }

            // updates the article header
            function update_header() {
                cont.html(ed.getContent());

                var html = '';

                if ($('#company_city').val() || $('#company_state').va()) {
                    if ($('#company_city').val() && $('#company_state').val()) {
                        html = '<span itemprop="addressLocality">' + $('#company_city').val() + '</span>, <span itemprop="addressRegion">' + $('#company_state').val() + '</span> ';
                    } else if ($('#company_city').val()) {
                        html = '<span itemprop="addressLocality">' + $('#company_city').val() + '</span> ';
                    } else if ($('#company_state').val()) {
                        html = '<span itemprop="addressRegion">' + $('#company_state').val() + '</span> ';
                    }
                }
                $('#header_city_state', cont).html(html);
                ed.setContent(cont.html());
            }

            function update_country() {
                cont.html(ed.getContent());
                $('#article_footer_country', cont).html($('#company_country').children(':selected').text() ? $('#company_country').children(':selected').text() : '&nbsp;');
                ed.setContent(cont.html());
            }
            $company_country.bind({
                keyup: update_country,
                blur: update_country
            });
            $company_country.trigger('blur');

            function update_companyname() {
                cont.html(ed.getContent());
                $('#article_footer_name', cont).html($('#company_name').val() ? $('#company_name').val() : '&nbsp;');
                $('#company_title', cont).html($('#company_name').val() ? $('#company_name').val() : '&nbsp;');
                ed.setContent(cont.html());
            }

            function update_companydesc() {

                cont.html(ed.getContent());
                $('#company_title', cont).html($('#company_name').val() ? $('#company_name').val() : '&nbsp;');
                $('#company_desc', cont).html($('#nwexpress_company_desc').val() ? $('#nwexpress_company_desc').val() : '&nbsp;');
                $('#company_desc_wrapper', cont).show();

                ed.setContent(cont.html());
                if ($company_desc.val() != '')
                    $('#company_desc_wrapper', cont).show();
                else {
                    $('#company_desc_wrapper', cont).hide();
                }
            }

            $company_name.bind({
                keyup: update_companyname,
                blur: update_companyname

            });
            $company_desc.bind({
                keyup: update_companydesc,
                blur: update_companydesc
            });

            function update_address() {
                cont.html(ed.getContent());
                $('#article_footer_address', cont).html($('#company_address').val() ? $('#company_address').val() : '&nbsp;');
                ed.setContent(cont.html());
            }

            $company_address.bind({
                keyup: update_address,
                blur: update_address
            });

            $company_city.bind({
                keyup: update_footer,
                blur: update_footer
            });
            $company_city.bind({
                keyup: update_header,
                blur: update_header
            });

            $company_state.bind({
                keyup: update_footer,
                blur: update_footer
            });
            $company_state.bind({
                keyup: update_header,
                blur: update_header
            });
            /**
             * Postal/Zip
             */
            function update_zip() {
                cont.html(ed.getContent());
                $('#article_footer_zip', cont).html($('#company_zip').val() ? $('#company_zip').val() : '&nbsp;');
                ed.setContent(cont.html());
            };
            $company_zip.bind({
                keyup: update_zip,
                blur: update_zip
            });



            /**
             * Phone
             */
            function update_telephone() {
                cont.html(ed.getContent());
                $('#article_footer_telephone', cont).html($('#company_telephone').val() ? $('#company_telephone').val() : '&nbsp;');
                ed.setContent(cont.html());
            }

            $company_telephone.bind({
                keyup: update_telephone,
                blur: update_telephone
            });

            /*
             * COmpany Email
             */
            function update_email() {

                //set content
                cont.html(ed.getContent());

                if ($company_email.val()) {
                    $('#article_footer_email', cont).replaceWith('<a id="article_footer_email" itemprop="email" href="mailto:' + this.value + '">' + this.value + '</a>');
                } else {
                    $('#article_footer_email', cont).replaceWith('<span id="article_footer_email" itemprop="email" >{Email}</span>');
                }

                ed.setContent(cont.html());

            } //end callback

            $company_email.bind({
                keyup: update_email,
                blur: update_email
            });


            /**
             * Company website
             */
            function update_website() {
                //set content
                cont.html(ed.getContent());

                if ($company_website.val()) {
                    var url = this.value.match(/https?:\/\//i) ? this.value : 'http://' + this.value;
                    $('#article_footer_website', cont).replaceWith('<a id="article_footer_website" itemprop="url" href="' + url + '">' + this.value + '</a>');
                } else {
                    $('#article_footer_website', cont).replaceWith('<span id="article_footer_website" itemprop="url">{Website}</span>');
                }

                ed.setContent(cont.html());
            }
            $company_website.bind({
                keyup: update_website,
                blur: update_website
            });
            /**
             * Schema
             */
            $('#schema_id').change(function() {
                cont.html(ed.getContent());
                var label = this.options[this.selectedIndex].label.replace(/^(-+\|)/, '')
                $('#company_nap', cont).attr('itemtype', 'http://schema.org/' + label);
                ed.setContent(cont.html());
            });
        }
    };



    var contextEl = $('.newswire');
    /*
	$('company_website').addEvent('keyup', function(){
    cont.innerHTML = tinyMCE.get('body').getContent();

    if ($('company_website').value) {
      new Element('a', {
        id: 'article_footer_website',
        itemprop: 'url',
        text: this.value,
        href: this.value.match(/https?:\/\//i) ? this.value : 'http://' + this.value
      }).replaces(cont.getElement('#article_footer_website'));
    } else {
      new Element('span', {
        id: 'article_footer_website',
        itemprop: 'url',
        html: '&nbsp;'
      }).replaces(cont.getElement('#article_footer_website'));
    }
    tinyMCE.get('body').setContent(cont.innerHTML);
  });


*/
    /* Nano Templates (Tomasz Mazur, Jacek Becela) */
    function nano(template, data) {
        return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
            var keys = key.split("."),
                v = data[keys.shift()];
            for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
            return (typeof v !== "undefined" && v !== null) ? v : "";
        });
    }

    //setup max counter
    //	$('#titlewrap').append('<div class="maxcounter">0 of 70 Max characters</div>');
    //	$('#excerpt').after('<div class="maxcounter">0 of 256 Max characters</div>');
    //	$('#post-status-info').after('<div class="maxcounter">0 of 1500 Minimum 6000 Maximum</div>');
    //	var edit = {};

});//end jquery