
<?php
$footer_variant = !is_null(get_footer_style()) ? get_footer_style() : '02';
?>
<?php echo $__env->make('frontend.partials.pages-portion.footers.footer-'.$footer_variant, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<!-- back to top area start -->
<div class="back-to-top <?php echo e($page_post->back_to_top ?? ''); ?>">
    <span class="back-top"><i class="las la-angle-up"></i></span>
</div>
<!-- back to top area end -->


<script src="<?php echo e(asset('assets/frontend/js/slick.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/wow.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/jquery.magnific-popup.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/jquery.nice-select.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/main.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/dynamic-script.js')); ?>"></script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
    }
});
</script>

<?php echo $__env->make('frontend.pages.services.partials.service-search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.partials.home-search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.partials.inline-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<script src="<?php echo e(asset('assets/common/js/toastr.min.js')); ?>"></script>
<?php echo Toastr::message(); ?>


<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\quick\@core\resources\views/frontend/partials/footer.blade.php ENDPATH**/ ?>