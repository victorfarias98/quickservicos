<!-- Header area Starts -->
<?php
    $navClasses = request()->is('/') ? 'navbar navbar-area white nav-absolute navbar-expand-lg navbar-border' : 'navbar navbar-area white navbar-expand-lg navbar-border';
?>

<header class="header-style-01">
    <!-- Menu area Starts -->
    <nav class="<?php echo e($navClasses); ?> <?php echo e($page_post->page_class ?? ''); ?>">

        <div class="container container-two nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="<?php echo e(route('homepage')); ?>" class="logo">
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                    </a>
                </div>
                <a href="#0" class="show-nav-right-contents white-color">
                    <i class="las la-ellipsis-v"></i>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <ul class="navbar-nav">
                    <?php echo render_frontend_menu($primary_menu); ?>

                </ul>
            </div>
            <div class="nav-right-content">
                <div class="navbar-right-inner">
                    <div class="info-bar-item">
                        <?php if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type==0): ?>
                        <div class="notification-icon icon">
                            <?php if(Auth::guard('web')->check()): ?>
                                <span class="text-white"> <i class="las la-bell"></i> </span>
                                <span class="notification-number"> 
                                    <?php echo e(Auth::user()->unreadNotifications->count()); ?>

                                </span>
                            <?php endif; ?> 
                            <div class="notification-list-item mt-2">
                                <h5 class="notification-title"><?php echo e(__('Notifications')); ?></h5>
                                <div class="list">
                                    <?php if(Auth::guard('web')->check() && Auth::guard('web')->user()->unreadNotifications->count() >=1): ?>
                                    <span>
                                        <?php $__currentLoopData = Auth::guard('web')->user()->unreadNotifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a class="list-order" href="<?php echo e(route('seller.order.details',$notification->data['order_id'])); ?>">
                                                <span class="order-icon"> <i class="las la-check-circle"></i> </span>
                                                <?php echo e($notification->data['order_message']); ?> #<?php echo e($notification->data['order_id']); ?>

                                            </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </span>
                                    <a class="p-2 text-center d-block" href="<?php echo e(route('seller.notification.all')); ?>"><?php echo e(__('View All Notification')); ?></a>
                                    <?php else: ?>
                                        <p class="text-center text-white padding-3"><?php echo e(__('No New Notification')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.frontend.user-menu','data' => []]); ?>
<?php $component->withName('frontend.user-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- Menu area end -->
</header><?php /**PATH /home/nazmul/public_html/qixer/@core/resources/views/frontend/partials/pages-portion/navbars/navbar-01.blade.php ENDPATH**/ ?>