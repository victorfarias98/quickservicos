<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Verify Account')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="signup-area padding-top-70 padding-bottom-100">
    <div class="container">
        <div class="signup-wrapper">
            <div class="signup-contents">
                <h3 class="signup-title"> <?php echo e(__('Verify Your Account')); ?> </h3>
                <h6 class="text-center"><?php echo e(__('Check email for verification code.')); ?></h6>
                
                <?php if(Session::has('msg')): ?>
                <p class="alert alert-danger"><?php echo e(Session::get('msg')); ?></p>
                <?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.msg.error','data' => []]); ?>
<?php $component->withName('msg.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

                <form class="signup-forms" action="<?php echo e(route('email.verify')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="single-signup margin-top-30">
                        <label class="signup-label"> <?php echo e('Enter code*'); ?> </label>
                        <input class="form--control" type="text" name="email_verify_token" placeholder="Enter code">
                    </div>
                    <button type="submit"><?php echo e(__('Verify Account')); ?></button>
                </form>
                <a class="mt-5 text-center" href="<?php echo e(route('resend.verify.code')); ?>"><?php echo e(__('Resend Code')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bytesed/public_html/laravel/qixer/@core/resources/views/frontend/user/email-verify.blade.php ENDPATH**/ ?>