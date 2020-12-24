  $(document).ajaxStart(function () {
    Pace.restart()
  })

  $(document).ready(function(){
      $(document).on('submit', '#data', function(){
          $('.save').attr("disabled", true);
          $('.save').addClass("buttonload");
          $('.loading').addClass("fa-spinner fa-spin");
      });

      $(document).on('submit', '#login', function(){
        $('.sign-in').attr("disabled", true);
        $('.save').addClass("buttonload");
        $('.loading').addClass("fa-spinner fa-spin");
    });
  });

  