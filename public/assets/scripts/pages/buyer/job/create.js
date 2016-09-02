/**
 * job/create.js
 */

define(['jqueryui', 'select2'], function () {

  var fn = {
    init: function () {
      $('.job-category').select2();

      $("#job_skills").select2({
          placeholder: "Search for skills",
          minimumInputLength: 0,
          ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
              url: "/job/search_skills",
              dataType: 'jsonp',
              data: function (term, page) {
                  return {
                      q: term, // search term
                  };
              },
              results: function (data, page) { // parse the results into the format expected by Select2.
                  // since we are using custom formatting functions we do not need to alter remote JSON data
                  return {
                      results: data.skills
                  };
              }
          },
          
          formatResult: fn.skillFormatResult, // omitted for brevity, see the source of this page
          formatSelection: fn.skillFormatSelection, // omitted for brevity, see the source of this page
          dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
          escapeMarkup: function (m) {
              return m;
          } // we do not want to escape markup since we are displaying html in results
      });

      this.$form = $("#form_job_post");
      this.validator = this.$form.validate();
      

      $(".job-type-section #hourly_job").click(function() {
        $('#form_job_post').removeClass('fixed-job').addClass('hourly-job');
      });
      $(".job-type-section #fixed_job").click(function() {
        $('#form_job_post').addClass('fixed-job').removeClass('hourly-job');
      });
    },

    skillFormatResult: function(skill) {
        var markup = "<table class='skill-result'><tr>";
        markup += "<td valign='top'><h5>" + skills + "</h5>";
        markup += "</td></tr></table>";
        return markup;
    }, 

    skillFormatSelection: function(skill) {
        return skill.title;
    }
  };

  return fn;
});