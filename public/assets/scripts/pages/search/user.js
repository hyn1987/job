/**
 * job.js
 * so gwang
 */

define(['jquery', 'jqueryui', 'jqueryrating', 'jqueryratingpack','jquerymetadata'], function () {

  var fn = {
    $searchFrm: null,
    $searchBtn: null,
    $paginationWrapper: null,

    $currentPage : null,
    $lastPage : null,

    $objStarRatingControl: null,

    // define Search function.
    search: function ($page) {
      
      var data = {};
      //set the search-text
      data.search_title = $('#search_title').val();

      //set the page-number
      data.page = $page;
      
      // Make ajax call
      $.post('/search/user', data, function (json) { 

        //init page data
        fn.$currentPage = json.cur_page;
        fn.$lastPage = json.last_page;
        //pagination 
        $('#pagination_wrapper').html(json.pgn_rndr_html).find('.pagination li a').attr('href', '#');
        //show users search result
        $('#result').html(json.str_html); 
        $('#msg-wrap').html(json.msg);  

        //initialize star-rating control
        fn.initStarRatingControl();
      });      
    },

    //get Page Number when click...
    searchPageResult: function () {
      if ( $(this).attr('rel') == 'prev' ){
        if (fn.$currentPage == 1)  
          return;
        else 
          $page = fn.$currentPage - 1;
      } else if ( $(this).attr('rel') == 'next' ){
        if (fn.$currentPage == fn.$lastPage)  
          return;
        else 
          $page = fn.$currentPage + 1;
      } else {
        $page = $(this).html();
      }
      
      fn.search($page);

      return false;
    },

    //
    initStarRatingControl: function () {
      //initialize the star-rating control.
      $('p.rate-control').each(function () {
        $(this).find('input.rate').rating();
        $(this).find('input').rating('readOnly',true);
      }); 

      $('p.rate-control').removeClass('hide');
    },

    init: function () {      

       fn.$searchFrm = $('#search_form');
       fn.$searchBtn = $('#search_btn');
       fn.$paginationWrapper = $('#pagination_wrapper');

       //fn.$page = 1;
       fn.$currentPage = '{{ $jobs_page->currentPage() }}';
       fn.$lastPage = '{{ $jobs_page->lastPage() }}';

      //change the url-value to '#'
      $('#pagination_wrapper').find(".pagination li a").attr('href', '#');

      //define 'Enter'-key event-handler
      fn.$searchFrm.on('submit', function(event){
        fn.search(1);        
        return false;
      });

      //define search-button event-handler
      fn.$searchBtn.on('click', function () {
        fn.search(1);
        return false;
      });

      //define page-nav event-handler
      fn.$paginationWrapper.on('click', '.pagination li a', fn.searchPageResult);

      fn.initStarRatingControl();
    }
  };

  return fn;
});
