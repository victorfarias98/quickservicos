

<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Seller Profile')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Banner Inner area Starts -->
    <?php if(!empty($seller)): ?>
    <div class="banner-inner-area section-bg-2 padding-top-40 padding-bottom-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 margin-top-30">
                    <div class="profile-author-contents">
                        <div class="profile-flex-content">
                            <div class="thumb">
                                <?php echo render_image_markup_by_attachment_id($seller->image); ?>

                            </div>
                            <div class="profile-contents">
                                <h4 class="title"> <a href="<?php echo e(route('about.seller.profile',$seller->id)); ?>"> <?php echo e($seller->name); ?> </a> </h4>
                                <?php if($service_rating >=1): ?>
                                <div class="profiles-review">
                                    <span class="icon"><?php echo e(__('Rating:')); ?> </span>
                                    <span class="reviews"> 
                                        <b>(<?php echo e(round($service_rating,1)); ?>) </b>
                                        (<?php echo e($service_reviews->count()); ?>)
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 margin-top-30">
                    <div class="profile-author-contents">
                        <ul class="profile-about">
                            <li> <?php echo e(__('From:')); ?> <span> <?php echo e(optional($seller->country)->country); ?> </span> </li>
                            <li> <?php echo e(__('Seller Since:')); ?> <span> <?php echo e(Carbon\Carbon::parse($seller_since->created_at)->year); ?>  </span> </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-5 margin-top-30">
                    <div class="profile-author-contents">
                        <div class="profile-single-achieve">
                            <div class="single-achieve">
                                <div class="achieve-inner">
                                    <div class="icon">
                                        <i class="las la-check"></i>
                                    </div>
                                    <div class="contents margin-top-10">
                                        <h3 class="title"><?php if(!empty($completed_order)): ?><?php echo e($completed_order); ?> <?php endif; ?></h3>
                                        <span class="ratings-span"> <?php echo e(__('Order Completed ')); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="single-achieve">
                                <div class="achieve-inner">
                                    <div class="icon">
                                        <i class="las la-star"></i>
                                    </div>
                                    <div class="contents margin-top-10">
                                        <h3 class="title"><?php if(!empty($seller_rating_percentage_value)): ?> <?php echo e($seller_rating_percentage_value); ?>%<?php endif; ?></h3>
                                        <span class="ratings-span"><?php echo e(__('Seller Rating')); ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Banner Inner area end -->

    <!-- Featured Service area starts -->
    <?php if(!empty($services)): ?>
    <section class="services-area padding-top-100 padding-bottom-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-two">
                        <h3 class="title"><?php echo e(__('Services of this Seller')); ?> </h3>
                    </div>
                </div>
            </div>
            <div class="row margin-top-50">
                <div class="col-lg-12">
                    <div class="services-slider dot-style-one">
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="single-services-item">
                            <div class="single-service">
                                <a href="<?php echo e(route('service.list.details',$service->slug)); ?>" class="service-thumb">
                                    <?php echo render_image_markup_by_attachment_id($service->image); ?>

                                    <?php if($service->featured == 1): ?>
                                    <div class="award-icons">
                                        <i class="las la-award"></i>
                                    </div>
                                    <?php endif; ?>
                                </a>
                                <div class="services-contents">
                                    <ul class="author-tag">
                                        <li class="tag-list">
                                            <a href="<?php echo e(route('about.seller.profile',$service->seller_id)); ?>">
                                                <div class="authors">
                                                    <div class="thumb">
                                                        <?php echo render_image_markup_by_attachment_id(optional($service->seller)->image); ?>

                                                        <span class="notification-dot"></span>
                                                    </div>
                                                    <span class="author-title"><?php echo e(optional($service->seller)->name); ?> </span>
                                                </div>
                                            </a>
                                        </li>
                                        <?php if($service->reviews->count() >= 1): ?>
                                        <li class="tag-list">
                                            <a href="javascript:void(0)">
                                                <span class="icon"> <?php echo e(__('Rating:')); ?> </span>
                                                <span class="reviews"> 
                                                    <?php echo e(round(optional($service->reviews)->avg('rating'),1)); ?>

                                                    (<?php echo e(optional($service->reviews)->count()); ?>)
                                                </span>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                    <h5 class="common-title"> <a href="<?php echo e(route('service.list.details',$service->slug)); ?>"><?php echo e($service->title); ?> </a> </h5>
                                    <p class="common-para"> <?php echo e(Str::limit($service->description,100)); ?> </p>
                                    <div class="service-price">
                                        <span class="starting"><?php echo e(__('Starting at')); ?> </span>
                                        <span class="prices"> <?php echo e(amount_with_currency_symbol( $service->price)); ?> </span>
                                    </div>
                                    <div class="btn-wrapper">
                                        <a href="<?php echo e(route('service.list.book',$service->slug)); ?>" class="cmn-btn btn-appoinment btn-bg-1"><?php echo e(__('Book Appoinment')); ?> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Featured Service area ends -->
    
    <!-- Review seller area Starts -->
    <?php if($service_reviews-> count() >= 1): ?>
    <div class="review-seller-area padding-bottom-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-two">
                        <h3 class="title"><?php echo e(get_static_option('service_reviews_title') ?? __('Reviews as Seller')); ?></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="review-seller-wrapper">
                        <div class="about-review-tab">
                            <?php $__currentLoopData = $service_reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="about-seller-flex-content style-02">
                                <div class="about-seller-thumb">
                                    <?php echo render_image_markup_by_attachment_id(optional($review->buyer)->image); ?>

                                </div>
                                <div class="about-seller-content">
                                    <h5 class="title"> <?php echo e($review->name); ?> </h5>
                                    <div class="about-seller-list">
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                        <span class="icon">  <i class="las la-star"></i>  </span>
                                    </div>
                                    <p class="about-review-para"><?php echo e($review->message); ?></p>
                                    <span class="review-date"> <?php echo e(optional($review->created_at)->toFormattedDateString()); ?> </span>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Review seller area ends -->
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bytesed/public_html/laravel/qixer/@core/resources/views/frontend/pages/seller/profile.blade.php ENDPATH**/ ?>