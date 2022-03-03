
<script>
  (function($){
    "use strict";

    $(document).ready(function(){

        $(document).on('change','#search_by_category',function(e){
            e.preventDefault();
            let category_id = $(this).val();
            $.ajax({
                url:"<?php echo e(route('all.service.search.category')); ?>",
                method:"get",
                data:{
                    category_id:category_id,
                },
                success:function(res){
                    if (res.status == 'success') {
                        $('#all_search_result').html(res.result);
                    }
                }
            });
        })

        $(document).on('change','#search_by_subcategory',function(e){
            e.preventDefault();
            let subcategory_id = $(this).val();

            $.ajax({
                url:"<?php echo e(route('all.service.search.subcategory')); ?>",
                method:"get",
                data:{
                    subcategory_id:subcategory_id,
                },
                success:function(res){
                    if (res.status == 'success') {
                        $('#all_search_result').html(res.result);
                    }
                }
            });
        })

        $(document).on('change','#search_by_rating',function(e){
            e.preventDefault();
            let rating = $(this).val();

            $.ajax({
                url:"<?php echo e(route('all.service.search.rating')); ?>",
                method:"get",
                data:{
                    rating:rating,
                },
                success:function(res){
                    if (res.status == 'success') {
                        $('#all_search_result').html(res.result);
                    }
                }
            });
        })

        $(document).on('change','#search_by_sorting',function(e){
            e.preventDefault();
            let sorting = $(this).val();

            $.ajax({
                url:"<?php echo e(route('all.service.search.sorting')); ?>",
                method:"get",
                data:{
                    sorting:sorting,
                },
                success:function(res){
                    if (res.status == 'success') {
                        $('#all_search_result').html(res.result);
                    }
                }
            });
        })

    });
})(jQuery);
</script>

<?php /**PATH /home/nazmul/public_html/qixer/@core/resources/views/frontend/pages/services/partials/service-search.blade.php ENDPATH**/ ?>