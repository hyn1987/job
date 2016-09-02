/**
 * job.js
 * so gwang
 */

define(['jquery', 'jqueryui'], function () {

  var fn = {
    $searchFrm: null,
    $searchBtn: null,
    $categorySel: null,
    $paginationWrapper: null,
    $subCategoryList: null,

    $currentPage : null,
    $lastPage : null,

    $rssBtn: null,
    $copyBtn: null,
    
    //Open RSS Dialog
    openRSSDlg: function () {
      var data = {};
      //set the search-text.
      data.search_title = $('#search_title').val();
      
      //category
      $parentCatId = $('#category').val();
      $objSubcategoryGrp = $('#category' +  $parentCatId + ' input:checked');
      
      $arry = [];
      if($objSubcategoryGrp.length > 0) {          
          $objSubcategoryGrp.each ( function () {
              $arry.push($(this).val());
          });          
      }

      if ($arry.length  > 0)  data.subCategory = $arry;

      // set the job-type value
      if ($('#type_hr:checked').length) {
        data.type_hr = $('#type_hr').val();
      }

      if ($('#type_fx:checked').length) {
        data.type_fx = $('#type_fx').val();
        data.bgt_amt_min = $('#bgt_amt_min').val();
        data.bgt_amt_max = $('#bgt_amt_max').val();
      }
      
      //set the duration value
      
      $objDurGrp = $('#qry_dur_grp input:checked');
      
      $arry = [];
      if($objDurGrp.length > 0) {          
          $objDurGrp.each ( function () {
              $arry.push($(this).val());
          });          
      }
      
      if ($arry.length  > 0)  data.duration = $arry;

      //set the workload value
      $objWorkloadGrp = $('#qry_workload_grp input:checked');
      
      $arry = [];
      if($objWorkloadGrp.length > 0) {          
          $objWorkloadGrp.each ( function () {
              $arry.push($(this).val());
          });          
      }
      
      if ($arry.length  > 0)   data.workload = $arry;

      //set the sort-by value
      data.sort_by = $('#sort_sel').val();
      $(".rss-url").val(siteUrl + '/search/rssjob?param=' + encodeURI(JSON.stringify(data)));
      $(".rss-box").toggle();
    },
    // Copy
    copyRSSLink: function () {
      //$(".rss-url").val()
      alert("Please add the copy function.");
      return true;
    },

    // Search
    search: function ($page, $sel_change) {
      var data = {};
      //set the search-text.
      data.search_title = $('#search_title').val();
      
      //category
      $parentCatId = $('#category').val();
      $arry = [];
      
      if ($sel_change == true) {
        $objSubcategoryGrp = $('#category' +  $parentCatId + ' input:checkbox');
        $objSubcategoryGrp.each ( function () {
          $arry.push($(this).val());
        }); 
      
      } else {
        $objSubcategoryGrp = $('#category' +  $parentCatId + ' input:checked');
        if($objSubcategoryGrp.length > 0) {          
          $objSubcategoryGrp.each ( function () {
              $arry.push($(this).val());
          });          
        }
      }

      if ($arry.length  > 0)  data.subCategory = $arry;

      // set the job-type value
      if ($('#type_hr:checked').length) {
        data.type_hr = $('#type_hr').val();
      }

      data.bgt_amt_min = $('#bgt_amt_min').val();
      data.bgt_amt_max = $('#bgt_amt_max').val();

      if ($('#type_fx:checked').length) {
        data.type_fx = $('#type_fx').val();        
      }
      
      //set the duration value
      
      $objDurGrp = $('#qry_dur_grp input:checked');
      
      $arry = [];
      if($objDurGrp.length > 0) {          
          $objDurGrp.each ( function () {
              $arry.push($(this).val());
          });          
      }
      
      if ($arry.length  > 0)  data.duration = $arry;

      //set the workload value
      $objWorkloadGrp = $('#qry_workload_grp input:checked');
      
      $arry = [];
      if($objWorkloadGrp.length > 0) {          
          $objWorkloadGrp.each ( function () {
              $arry.push($(this).val());
          });          
      }
      
      if ($arry.length  > 0)   data.workload = $arry;

      //set the sort-by value
      data.sort_by = $('#sort_sel').val();
      data.category = $('#category').val();

      //set the page-number
      data.page = $page;
      
      // Make ajax call
      $.post('/search/job', data, function (json) { 
          
          //init page data
          fn.$currentPage = json.cur_page;
          fn.$lastPage = json.last_page;
          
          //pagination 
          $('#pagination_wrapper').html(json.pgn_rndr_html).find(".pagination li a").attr("href", "#");
          
          //the found jobs count      
          if(data.page == 1) {
              $('span.job-count').html(json.job_count);   
          } 
          
          //show job result
          $('#results').html(json.str_html);
          
          $('#hr_cnt').html(json.array_cnt['hr_cnt']); 
          $('#fx_cnt').html(json.array_cnt['fx_cnt']); 

          $('#dur_mt6m_cnt').html(json.array_cnt['dur_mt6m_cnt']);
          $('#dur_3t6m_cnt').html(json.array_cnt['dur_3t6m_cnt']);
          $('#dur_1t3m_cnt').html(json.array_cnt['dur_1t3m_cnt']);
          $('#dur_lt1m_cnt').html(json.array_cnt['dur_lt1m_cnt']);
          $('#dur_lt1w_cnt').html(json.array_cnt['dur_lt1w_cnt']);

          $('#pt_cnt').html(json.array_cnt['pt_cnt']);
          $('#ft_cnt').html(json.array_cnt['ft_cnt']);
          $('#as_cnt').html(json.array_cnt['as_cnt']);
          
          for(var jobCnt in json.arrayJobCntByCat) {
            $('#category_' + jobCnt).html(json.arrayJobCntByCat[jobCnt]);
          }

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

    //get sub-category list...
    showSubCategoryList: function ($val) {
      //init subcategory list
       $subCategoryArray = fn.$subCategoryList.find('.checkbox-list');
       
       $subCategoryArray.each( function () {
          $(this).find('input[type=checkbox]').prop('checked', false);

           if ( $(this).attr('id') != 'category' + $val) {
            $(this).hide();
           } else {
            $(this).show();
           }
       });
    },

    init: function () {
       fn.$searchFrm = $('#search_form');
       fn.$searchBtn = $('#search_btn');
       fn.$sortSel = $('#sort_sel');
       fn.$categorySel = $('#category');
       fn.$paginationWrapper = $('#pagination_wrapper');
       fn.$subCategoryList = $('#sub_category_list');

       //show sub-category list
       fn.showSubCategoryList($('#category').val());

       //fn.$page = 1;
       fn.$currentPage = '{{ $jobs_page->currentPage() }}';
       fn.$lastPage = '{{ $jobs_page->lastPage() }}';

       fn.$rssBtn = $(".rss-link");
       fn.$copyBtn = $("#copy-btn");

      //change page-url to '#'. 
      $('#pagination_wrapper').find('.pagination li a').attr('href', '#');

      //define event handler
      fn.$searchBtn.on('click', function () {
        fn.search(1, false);
      });
      
      //define the category-combo event-handler
      fn.$categorySel.on('change', function () {
        $('#all_check').prop('checked',false);
        fn.showSubCategoryList($(this).val());
        fn.search(1, true);
      });

      //define the sort-combo event-handler
      fn.$sortSel.on('change', function () {
        fn.search(1, false);
      });
      
      //define the page-navigation event-handler
      fn.$paginationWrapper.on("click", ".pagination li a", fn.searchPageResult);

      //define 'Enter' key-handler
      fn.$searchFrm.on('submit', function(event){
        fn.search(1, false);        
        return false;
      });

      //Bind the event for rss service
      fn.$rssBtn.on('click', function() {
        fn.openRSSDlg();
      });
      fn.$copyBtn.on('click', function() {
        fn.copyRSSLink();
      });

      //All-subcategory checkbox event handler
      $('#all_check').on('change', function (event) {

          $parentCatId = $('#category').val();
          $objSubcategoryGrp = $('#category' +  $parentCatId + ' input:checkbox');

          if($objSubcategoryGrp.length > 0) { 
              if ($(this).prop('checked') == true) {         
                  $objSubcategoryGrp.each ( function () {
                      $(this).prop('checked', true);
                  });      
                    
              } else {
                  $objSubcategoryGrp.each ( function () {
                          $(this).prop('checked', false);
                  });  
              }
          }   

          fn.search(1,false);    
          return false;
      });

      //
      $('#conditionBox input:checkbox').on('change', function (event) {
        fn.search(1, false);
        return false;
      });

      //define Slider control      
      $("#budget").slider({
        range: true,
        min: 0,
        max: 5000,
        values: [0, 5000],
        stop: function(event, ui) {
          $("#bgt_amt_min").val(ui.values[0]);
          $("#bgt_amt_max").val(ui.values[1]);
          $("#budget-value-var").html('$' + ui.values[0] + ' - $' + ui.values[1]);
          fn.search(1,false);
        },
      });

      $("#bgt_amt_min").val($("#budget").slider("values", 0));
      $("#bgt_amt_max").val($("#budget").slider("values", 1)); 
    }
  };

  return fn;
});
