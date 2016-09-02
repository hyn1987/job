/**
 * list.js - Admin / User/list
 */

define(['plugin', 'easypiechart', 'sparkline', 'bootstrap-hover-dropdown', 'vmap', 'vmap-world'], function (plugin) {

	var fn = {

        rp: { // recentPosts

            $wrapper: null,
            stats_data: [],

            handlers: function() {
                fn.rp.$wrapper.on('click', 'a.reload', function (e) {
                    e.preventDefault();
                    fn.rp.load();
                });

                fn.rp.$wrapper.on('click', '.checkAll', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.pr-cats-list .pr_cat', fn.rp.$wrapper).each(function(i, o) {
                        $(o).prop('checked', true).parent().addClass('checked');
                    });
                });

                fn.rp.$wrapper.on('click', '.checkNone', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.pr-cats-list .pr_cat', fn.rp.$wrapper).each(function(i, o) {
                        $(o).prop('checked', false).parent().removeClass('checked');
                    });
                });

                fn.rp.$wrapper.on('change', '.check-all', function (e) {
                    if ($(this).is(':checked')) {
                        $('.pr-cats-list .pr_cat', fn.rp.$wrapper).each(function(i, o) {
                            $(o).prop('checked', true).parent().addClass('checked');
                        });
                    } else {
                       $('.pr-cats-list .pr_cat', fn.rp.$wrapper).each(function(i, o) {
                            $(o).prop('checked', false).parent().removeClass('checked');
                        }); 
                    }
                });

            },
            
            getSelectedCats: function() {
                var cats = [];
                $('.pr-cats-list .pr_cat', fn.rp.$wrapper).each(function(i, o) {
                    if ($(o).is(':checked')) cats.push($(o).attr('ref'));
                });
                return cats;
            },

            load: function() {

                plugin.blockOn({target: $('.portlet-body', fn.rp.$wrapper), iconOnly: true});

                $.ajax({
                    type: "post",
                    cache: false,
                    url: '/admin/dashboard/recent_posts',
                    data: {'cats': fn.rp.getSelectedCats()},
                    dataType: "json",
                    success: function(res) 
                    {                        
                        plugin.blockOff($('.portlet-body', fn.rp.$wrapper));
                        
                        fn.rp.stats_data = $.extend([], res.data);
                        fn.rp.fill();
                    },
                    error: function(xhr, ajaxOptions, thrownError)
                    {
                        plugin.blockOff($('.portlet-body', fn.rp.$wrapper));
                        // show error message here
                    }
                });
            },

            fill: function() {
                // fill the list
                $('.feeds', fn.rp.$wrapper).html('');
                for (var i=0; i<fn.rp.stats_data.length; i++) {
                    var row = fn.rp.stats_data[i];
                    var $li = $('.__model', fn.rp.$wrapper).clone();
                    $li.removeClass('__model').attr('ref', row['id']);
                    $li.find('.desc').html(row['title']);
                    $li.find('img.avatar').attr('src', row['buyer']['avatar']);
                    $li.find('.date').html(row['created_at']);
                    $li.appendTo($('.feeds', fn.rp.$wrapper));
                }
                
            },

            init: function() {
                fn.rp.$wrapper = $('#stat_rp');
                fn.rp.handlers();
                fn.rp.load();
            }

        },

        rt: { // recentTransactions

        },

        cs: { // complete stats
            $wrapper: null,
            data: null,
            
            handlers: function() {

                fn.cs.$wrapper.on('click', 'a.reload', function (e) {
                    e.preventDefault();
                    fn.cs.load();
                });

                $('.easy-pie-chart .number.client_profile', fn.cs.$wrapper).easyPieChart({
                    animate: 1000,
                    size: 75,
                    lineWidth: 3,
                    barColor: '#F8CB00'
                });

                $('.easy-pie-chart .number.buyer_profile', fn.cs.$wrapper).easyPieChart({
                    animate: 1000,
                    size: 75,
                    lineWidth: 3,
                    barColor: '#1bbc9b'
                });

                $('.easy-pie-chart .number.transactions', fn.cs.$wrapper).easyPieChart({
                    animate: 1000,
                    size: 75,
                    lineWidth: 3,
                    barColor: '#F3565D'
                });

            },

            load: function() {

                plugin.blockOn({target: $('.portlet-body', fn.cs.$wrapper), iconOnly: true});

                $.ajax({
                    type: "post",
                    cache: false,
                    url: '/admin/dashboard/complete_stats',
                    data: {},
                    dataType: "json",
                    success: function(res) 
                    {                        
                        plugin.blockOff($('.portlet-body', fn.cs.$wrapper));
                        fn.cs.data = $.extend({}, res.data);
                        fn.cs.fill();

                    },
                    error: function(xhr, ajaxOptions, thrownError)
                    {
                        plugin.blockOff($('.portlet-body', fn.cs.$wrapper));
                        // show error message here
                    }
                });

            },

            fill: function() {
                $('.easy-pie-chart', fn.cs.$wrapper).each(function (i, x) {
                    var o = $('.number', $(x));
                    var keyv = $(o).attr('ref');
                    $(o).data('easyPieChart').update(fn.cs.data[keyv]['value']);
                    $('span', $(o)).text(fn.cs.data[keyv]['value']);
                    $('.title span', $(x)).text(fn.cs.data[keyv]['link_label'])
                    $('.title', $(x)).attr('href', fn.cs.data[keyv]['link']).css('visibility', 'visible');
                });
            },

            init: function() {

                fn.cs.$wrapper = $('#complete_stats');

                fn.cs.handlers();
                fn.cs.load();
            }
        },

        srvs: {
            
            $wrapper: null,
            data: null,

            handlers: function() {

                fn.srvs.$wrapper.on('click', 'a.reload', function (e) {
                    e.preventDefault();
                    fn.srvs.load();
                });

            },

            load: function() {

                plugin.blockOn({target: $('.portlet-body', fn.srvs.$wrapper), iconOnly: true});

                $.ajax({
                    type: "post",
                    cache: false,
                    url: '/admin/dashboard/server_stats',
                    data: {},
                    dataType: "json",
                    success: function(res) 
                    {                        
                        plugin.blockOff($('.portlet-body', fn.srvs.$wrapper));
                        fn.srvs.data = $.extend([], res.data);
                        fn.srvs.fill();

                    },
                    error: function(xhr, ajaxOptions, thrownError)
                    {
                        plugin.blockOff($('.portlet-body', fn.srvs.$wrapper));
                        // show error message here
                    }
                });

            },

            fill: function() {

                $("#sparkline_bar", fn.srvs.$wrapper).sparkline(fn.srvs.data[0], {
                    type: 'bar',
                    width: '100',
                    barWidth: 5,
                    height: '55',
                    barColor: '#35aa47',
                    negBarColor: '#e02222'
                });

                $("#sparkline_bar2", fn.srvs.$wrapper).sparkline(fn.srvs.data[1], {
                    type: 'bar',
                    width: '100',
                    barWidth: 5,
                    height: '55',
                    barColor: '#ffb848',
                    negBarColor: '#e02222'
                });

                $("#sparkline_line", fn.srvs.$wrapper).sparkline(fn.srvs.data[2], {
                    type: 'line',
                    width: '100',
                    height: '55',
                    lineColor: '#ffb848'
                });

                $('.sparkline-chart .title', fn.srvs.$wrapper).css('visibility', 'visible');
            },

            init: function() {
                fn.srvs.$wrapper = $('#server_stats');
                fn.srvs.handlers();
                fn.srvs.load();
            }
        },

		jqvmap_users: {

			$wrapper: null,
			
			stats_data: {},

			current_map: 'world',
			
			resizeMap: function() {
				$('.vmaps .map-inner', fn.jqvmap_users.$wrapper).each(function (i, o) {
                    $(o).width($(o).parent().width());
                });
			},

			showMap: function (name) {
                $('.vmaps .map-inner', fn.jqvmap_users.$wrapper).each(function(i, o){
                	$(o).hide();
                });
                $('.vmap_' + name + ' .map-inner', fn.jqvmap_users.$wrapper).show();
                fn.jqvmap_users.current_map = name;
            },

            setMap: function (name) {

                var data = {
                    map: 'world_en',
                    backgroundColor: null,
                    borderColor: '#333333',
                    borderOpacity: 0.5,
                    borderWidth: 1,
                    color: '#bbbbbb',
                    enableZoom: true,
                    hoverColor: null,
                    hoverOpacity: 0.8,
                    values: fn.jqvmap_users.stats_data,
                   	// values: {},
                    normalizeFunction: 'linear',
                    scaleColors: ['#b6da93', '#226d6d'],
                    selectedColor: null,
                    selectedRegion: null,
                    showTooltip: true,
                    onLabelShow: function (event, label, code) {
                    	if (!fn.jqvmap_users.stats_data[code]) fn.jqvmap_users.stats_data[code] = 0;
                    	label.text(label.text() + ' : ' + fn.jqvmap_users.stats_data[code]);
                    },
                    onRegionOver: function (event, code) {},
                    onRegionClick: function (element, code, region) {}
                };

                data.map = name + '_en';

                var map_wrapper = $('.vmap_' + name, fn.jqvmap_users.$wrapper);
                var map = $('<div class="map-inner" />');

                if (!map_wrapper) {
                    return;
                }

                map_wrapper.find('div').remove();
                map.appendTo(map_wrapper);

                map.width(map.parent().width());
                map.show();
                map.vectorMap(data);
                map.hide();
            },

            updateMap: function(name) {
            	/*
            	var map = $('.vmap_' + name, fn.jqvmap_users.$wrapper);
				map.vectorMap('set', 'values', fn.jqvmap_users.stats_data);
				*/

				fn.jqvmap_users.setMap("world");
				fn.jqvmap_users.showMap("world");
            },


            load: function() {

            	plugin.blockOn({target: $('.portlet-body', fn.jqvmap_users.$wrapper), iconOnly: true});

            	var st = $('.btn-group-userstatus .active > a', fn.jqvmap_users.$wrap).attr('ref');
            	var tv = $('.btn-group-usertype .active > a', fn.jqvmap_users.$wrap).attr('ref');

            	$.ajax({
                    type: "post",
                    cache: false,
                    url: '/admin/dashboard/regional_user_stat',
                    data: {'st': st, 'tv': tv},
                    dataType: "json",
                    success: function(res) 
                    {                        
                        plugin.blockOff($('.portlet-body', fn.jqvmap_users.$wrapper));
                        
                        fn.jqvmap_users.stats_data = $.extend({}, res.data);
                        fn.jqvmap_users.updateMap("world");
                    },
                    error: function(xhr, ajaxOptions, thrownError)
                    {
                        plugin.blockOff($('.portlet-body', fn.jqvmap_users.$wrapper));
                        // show error message here
                    }
                });
            },

            handlers: function() {

            	$(window).resize(function(){
            		fn.jqvmap_users.resizeMap();
            	});

            	$(fn.jqvmap_users.$wrapper).on('click', '.stat_vmap_status', function(e){
            		e.preventDefault();
            		$(this).parent().addClass('active').siblings().removeClass('active');
            		fn.jqvmap_users.load();
            	});

            	$(fn.jqvmap_users.$wrapper).on('click', '.stat_vmap_type', function(e){
            		e.preventDefault();
            		$(this).parent().addClass('active').siblings().removeClass('active');
					fn.jqvmap_users.load();     		
            	});

            	fn.jqvmap_users.$wrapper.on('click', 'a.reload', function (e) {
		            e.preventDefault();
		            fn.jqvmap_users.load();
		        });

                /*
            	/////////////////////////////////////////////////////////////////////////////////
            	// open-on-hover handler
            	//
		        $('.open-on-hover .btn-group', fn.jqvmap_users.$wrapper).hover(function(e){
            		e.preventDefault();

            		var $me = $(this);
            		$('.open-on-hover .btn-group.open', fn.jqvmap_users.$wrapper).not($me).removeClass('open');
            		$(this).removeClass('leaving').addClass('open');
            	}, function(e){
            		e.preventDefault();

            		var $me = $(this);
            		$me.addClass('leaving');
            		setTimeout(function(){
            			if ($me.hasClass('leaving')) $me.removeClass('open');
            		}, 500);
            	});
                */

            },

            init: function() {
            	
            	fn.jqvmap_users.$wrapper = $('#region_stat_user');

            	fn.jqvmap_users.setMap("world");
				fn.jqvmap_users.showMap("world");

            	fn.jqvmap_users.handlers();
            	fn.jqvmap_users.load();
            }

        },

		init: function () {

			fn.jqvmap_users.init();
            fn.rp.init();
            fn.cs.init();
            fn.srvs.init();
            
            /*
			var getLastPostPos = function () {
                var height = 0;
                cont.find("li.out, li.in").each(function () {
                    height = height + $(this).outerHeight();
                });

                return height;
            }

            $('.scroller').slimScroll({
                scrollTo: getLastPostPos()
            });

			*/
		}

	};

	return fn;
});