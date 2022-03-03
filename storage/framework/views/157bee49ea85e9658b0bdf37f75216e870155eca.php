
<script>
    (function($){
      "use strict";
  
      $(document).ready(function(){

        $(document).on('keyup','#home_search',function(e){
              e.preventDefault();
              let search_text = $(this).val();
              let service_city_id = $('#service_city_id').val();
              $.ajax({
                  url:"<?php echo e(route('frontend.home.search')); ?>",
                  method:"get",
                  data:{
                    search_text:search_text,
                    service_city_id:service_city_id,
                  },
                  success:function(res){
                      if (res.status == 'success') {
                          $('#all_search_result').html(res.result);
                      }else{
                        $('#all_search_result').html(res.result);
                      }
                  }
              });
          })
          
  
      });
  })(jQuery);
  </script>
  
  <?php /**PATH /home/nazmul/public_html/qixer/@core/resources/views/frontend/partials/home-search.blade.php ENDPATH**/ ?>