
<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Edit Services')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.css','data' => []]); ?>
<?php $component->withName('media.css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/bootstrap-tagsinput.css')); ?>">
    <style>
        .meta-content .bootstrap-tagsinput .tag {
            margin-right: 2px !important;
            color: #444 !important;
            font-size: 14px!important;
            line-height: 24px !important;
            padding: 3px 10px !important;
            border-radius: 3px !important;
            border: 1px solid #e2e2e2 !important;
        }
        .meta-content .bootstrap-tagsinput {
            width: 100%;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
   
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.frontend.seller-buyer-preloader','data' => []]); ?>
<?php $component->withName('frontend.seller-buyer-preloader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <!-- Dashboard area Starts -->
    <div class="body-overlay"></div>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <?php echo $__env->make('frontend.user.seller.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="dashboard-right">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="dashboard-settings margin-top-40">
                                <h2 class="dashboards-title"> <?php echo e(__('Edit Service')); ?> </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 margin-top-50">
                            <div class="single-settings">
                                
                                <div class="mt-5"> <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
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
<?php endif; ?> </div>

                                <form action="<?php echo e(route('seller.edit.services',$service->id)); ?>" method="post" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label for="category" class="info-title"> <?php echo e(__('Select Parent Category*')); ?> </label>
                                            <select name="category" id="category">
                                                <option value=""><?php echo e(__('Select Category')); ?></option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cat->id); ?>" <?php if($cat->id==$service->category_id): ?> selected <?php endif; ?>><?php echo e($cat->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="single-info-input margin-top-30">
                                            <label for="subcategory" class="info-title"> <?php echo e(__('Select Sub Category*')); ?> </label>
                                            <select  name="subcategory" id="subcategory" class="subcategory">
                                                <option <?php if(!empty( $service->subcategory_id)): ?> value="<?php echo e($service->subcategory_id); ?>"  <?php else: ?> value="" <?php endif; ?>></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label for="title" class="info-title"> <?php echo e(__('Service Title*')); ?> </label>
                                            <input class="form--control" name="title" id="title" value="<?php echo e($service->title); ?>" type="text" placeholder="<?php echo e(__('Add tilte')); ?>">
                                        </div>
                                        <div class="single-info-input margin-top-30">
                                            <label for="tax" class="info-title"> <?php echo e(__('Service Tax (%)')); ?> </label>
                                            <input class="form--control" name="tax" id="tax" value="<?php echo e($service->tax); ?>" min="0" type="text" placeholder="<?php echo e(__('Add tax')); ?>">
                                        </div>
                                    </div>

                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30 permalink_label">
                                            <label for="title" class="info-title text-dark"> <?php echo e(__('Permalink*')); ?> </label>
                                                <span id="slug_show" class="display-inline"></span>
                                                <span id="slug_edit" class="display-inline">
                                                <button class="btn btn-warning btn-sm slug_edit_button"> <i class="las la-edit"></i> </button>

                                            <input class="form--control service_slug" name="slug" id="slug" style="display: none" type="text">
                                            <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none"><?php echo e(__('Update')); ?></button>
                                        </div>
                                    </div>

                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <label for="description" class="info-title"> <?php echo e(__('Service Description*')); ?> </label>
                                            <textarea class="form--control textarea--form" name="description" placeholder="<?php echo e(__('Type Description')); ?>"><?php echo e($service->description); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="single-dashboard-input">
                                        <div class="single-info-input margin-top-30">
                                            <div class="form-group">
                                                <div class="media-upload-btn-wrapper">
                                                    <div class="img-wrap">
                                                        <?php echo render_image_markup_by_attachment_id($service->image,'','thumb'); ?>

                                                    </div>
                                                    <input type="hidden" id="image" name="image"
                                                            value="<?php echo e($service->image); ?>">
                                                    <button type="button" class="btn btn-info media_upload_form_btn"
                                                            data-btntitle="<?php echo e(__('Select Image')); ?>"
                                                            data-modaltitle="<?php echo e(__('Upload Image')); ?>" data-toggle="modal"
                                                            data-target="#media_upload_modal">
                                                        <?php echo e('Upload Service Image'); ?>

                                                    </button>
                                                </div>
                                                <small class="form-text text-muted"><?php echo e(__('allowed image format: jpg,jpeg,png')); ?></small>
                                                <small class="text-danger"><?php echo e(__('recomended size 1394x315')); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body meta">
                                                    <h5 class="header-title"><?php echo e(__('Meta Section')); ?></h5>
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <div class="nav flex-column nav-pills" id="v-pills-tab"
                                                                 role="tablist" aria-orientation="vertical">
                                                                <a class="nav-link active" id="v-pills-home-tab"
                                                                   data-toggle="pill" href="#v-pills-home" role="tab"
                                                                   aria-controls="v-pills-home"
                                                                   aria-selected="true"><?php echo e(__('Blog Meta')); ?></a>
                                                                <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill"
                                                                   href="#v-pills-profile" role="tab"
                                                                   aria-controls="v-pills-profile"
                                                                   aria-selected="false"><?php echo e(__('Facebook Meta')); ?></a>
                                                                <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill"
                                                                   href="#v-pills-messages" role="tab"
                                                                   aria-controls="v-pills-messages"
                                                                   aria-selected="false"><?php echo e(__('Twitter Meta')); ?></a>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-9">
                                                            <div class="tab-content meta-content" id="v-pills-tabContent">
                    
                                                                <div class="tab-pane fade show active" id="v-pills-home"
                                                                     role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                                    <div class="form-group">
                                                                        <label for="title"><?php echo e(__('Meta Title')); ?></label>
                                                                        <input type="text" class="form-control" name="meta_title"
                                                                               value="<?php echo e($service->metaData->meta_title ?? ''); ?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="slug"><?php echo e(__('Meta Tags')); ?></label>
                                                                        <input type="text" class="form-control"  data-role="tagsinput" name="meta_tags"
                                                                               value="<?php echo e($service->metaData->meta_tags ?? ''); ?>">
                                                                    </div>
                    
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="title"><?php echo e(__('Meta Description')); ?></label>
                                                                            <textarea name="meta_description"
                                                                                      class="form-control max-height-140"
                                                                                      cols="20"
                                                                                      rows="4"><?php echo $service->metaData->meta_description ?? ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                    
                                                                </div>
                    
                                                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                                     aria-labelledby="v-pills-profile-tab">
                                                                    <div class="form-group">
                                                                        <label for="title"><?php echo e(__('Facebook Meta Tag')); ?></label>
                                                                        <input type="text" class="form-control" data-role="tagsinput"
                                                                               name="facebook_meta_tags" value="<?php echo e($service->metaData->facebook_meta_tags ?? ''); ?>">
                                                                    </div>
                    
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="title"><?php echo e(__('Facebook Meta Description')); ?></label>
                                                                            <textarea name="facebook_meta_description"
                                                                                      class="form-control max-height-140 meta-desc"
                                                                                      cols="20"
                                                                                      rows="4"><?php echo $service->metaData->facebook_meta_description ?? ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                    
                                                                    <div class="form-group ">
                                                                        <label for="og_meta_image"><?php echo e(__('Facebook Meta Image')); ?></label>
                                                                        <div class="media-upload-btn-wrapper">
                                                                            <div class="img-wrap">
                                                                                <?php echo render_attachment_preview_for_admin($service->metaData->facebook_meta_image ?? ''); ?>

                                                                            </div>
                                                                            <input type="hidden" id="facebook_meta_image" name="facebook_meta_image"
                                                                                   value="<?php echo e($service->metaData->facebook_meta_image ?? ''); ?>">
                                                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                                                    data-btntitle="<?php echo e(__('Select Image')); ?>"
                                                                                    data-modaltitle="<?php echo e(__('Upload Image')); ?>" data-toggle="modal"
                                                                                    data-target="#media_upload_modal">
                                                                                <?php echo e('Change Image'); ?>

                                                                            </button>
                                                                        </div>
                                                                        <small class="form-text text-muted"><?php echo e(__('allowed image format: jpg,jpeg,png')); ?></small>
                                                                    </div>
                                                                </div>
                    
                                                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                                     aria-labelledby="v-pills-messages-tab">
                                                                    <div class="form-group">
                                                                        <label for="title"><?php echo e(__('Twitter Meta Tag')); ?></label>
                                                                        <input type="text" class="form-control" data-role="tagsinput"
                                                                               name="twitter_meta_tags" value=" <?php echo e($service->metaData->twitter_meta_tags ?? ''); ?>">
                                                                    </div>
                    
                                                                    <div class="row">
                                                                        <div class="form-group col-md-12">
                                                                            <label for="title"><?php echo e(__('Twitter Meta Description')); ?></label>
                                                                            <textarea name="twitter_meta_description"
                                                                                      class="form-control max-height-140 meta-desc"
                                                                                      cols="20"
                                                                                      rows="4"><?php echo $service->metaData->twitter_meta_description ?? ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                    
                                                                    <div class="form-group">
                                                                        <label for="og_meta_image"><?php echo e(__('Twitter Meta Image')); ?></label>
                                                                        <div class="media-upload-btn-wrapper">
                                                                            <div class="img-wrap">
                                                                                <?php echo render_attachment_preview_for_admin($service->metaData->twitter_meta_image ?? ''); ?>

                                                                            </div>
                                                                            <input type="hidden" id="twitter_meta_image" name="twitter_meta_image"
                                                                                   value="<?php echo e($service->metaData->twitter_meta_image ?? ''); ?>">
                                                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                                                    data-btntitle="<?php echo e(__('Select Image')); ?>"
                                                                                    data-modaltitle="<?php echo e(__('Upload Image')); ?>" data-toggle="modal"
                                                                                    data-target="#media_upload_modal">
                                                                                <?php echo e('Change Image'); ?>

                                                                            </button>
                                                                        </div>
                                                                        <small class="form-text text-muted"><?php echo e(__('allowed image format: jpg,jpeg,png')); ?></small>
                                                                    </div>
                                                                </div>
                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="btn-wrapper margin-top-40">
                                        <input type="submit" class="btn btn-success btn-bg-1" value="<?php echo e(__('Update Service')); ?> ">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.markup','data' => ['type' => 'web']]); ?>
<?php $component->withName('media.markup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('web')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
    <!-- Dashboard area end -->
<?php $__env->stopSection(); ?>  

<?php $__env->startSection('scripts'); ?>

<?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.media.js','data' => ['type' => 'web']]); ?>
<?php $component->withName('media.js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('web')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<script src="<?php echo e(asset('assets/backend/js/bootstrap-tagsinput.js')); ?>"></script>

<script>
    $('.meta-content').show();
</script>

 <script>
    $(document).ready(function(){

        //Permalink Code
        $('.permalink_label').hide();
        $(document).on('keyup', '#title', function (e) {
            var slug = $(this).val().trim().toLowerCase().split(' ').join('-');
            var url = "<?php echo e(url('/service/')); ?>/" + slug;
            $('.permalink_label').show();
            var data = $('#slug_show').text(url).css('color', 'blue');
            $('.service_slug').val(slug);

        });

        //Slug Edit Code
        $(document).on('click', '.slug_edit_button', function (e) {
            e.preventDefault();
            $('.service_slug').show();
            $(this).hide();
            $('.slug_update_button').show();
        });

        //Slug Update Code
        $(document).on('click', '.slug_update_button', function (e) {
            e.preventDefault();
            $(this).hide();
            $('.slug_edit_button').show();
            var update_input = $('.service_slug').val();
            var slug = update_input.trim().toLowerCase().split(' ').join('-');
            var url = "<?php echo e(url('/service/')); ?>/" + slug;
            $('#slug_show').text(url);
            $('.service_slug').hide();
        });

        
        $('#category').on('change',function(){
            var category_id = $(this).val();
            $.ajax({
                method:'post',
                url:"<?php echo e(route('seller.subcategory')); ?>",
                data:{category_id:category_id},
                success:function(res){
                    if(res.status=='success'){
                        var alloptions = '';
                        var allSubCategory = res.sub_categories;
                        $.each(allSubCategory,function(index,value){
                            alloptions +="<option value='" + value.id + "'>" + value.name + "</option>";
                        });  
                        $(".subcategory").html(alloptions);
                        $('#subcategory').niceSelect('update');
                    }
                }
            })
        }) 

    })
 </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.user.seller.seller-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bytesed/public_html/laravel/qixer/@core/resources/views/frontend/user/seller/services/edit-service.blade.php ENDPATH**/ ?>