
<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Seller Dashboard')); ?>

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
                        <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                            <div class="single-orders">
                                <div class="orders-shapes">
                                    <img src="<?php echo e(asset('assets/frontend/img/static/orders-shapes.png')); ?>" alt="">
                                </div>
                                <div class="orders-flex-content">
                                    <div class="icon">
                                        <i class="las la-tasks"></i>
                                    </div>
                                    <div class="contents">
                                        <h2 class="order-titles"> <?php echo e($pending_order); ?> </h2>
                                        <span class="order-para"><?php echo e(__('Order Pending')); ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                            <div class="single-orders">
                                <div class="orders-shapes">
                                    <img src="<?php echo e(asset('assets/frontend/img/static/orders-shapes2.png')); ?>" alt="">
                                </div>
                                <div class="orders-flex-content">
                                    <div class="icon">
                                        <i class="las la-handshake"></i>
                                    </div>
                                    <div class="contents">
                                        <h2 class="order-titles"> <?php echo e($complete_order); ?> </h2>
                                        <span class="order-para"> <?php echo e(__('Order Completed ')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                            <div class="single-orders">
                                <div class="orders-shapes">
                                    <img src="<?php echo e(asset('assets/frontend/img/static/orders-shapes3.png')); ?>" alt="">
                                </div>
                                <div class="orders-flex-content">
                                    <div class="icon">
                                        <i class="las la-dollar-sign"></i>
                                    </div>
                                    <div class="contents">
                                        <h2 class="order-titles"> <?php echo e(float_amount_with_currency_symbol($total_earnings)); ?> </h2>
                                        <span class="order-para"><?php echo e(__('Total Withdraw')); ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 margin-top-30 orders-child">
                            <div class="single-orders">
                                <div class="orders-shapes">
                                    <img src="<?php echo e(asset('assets/frontend/img/static/orders-shapes4.png')); ?>" alt="">
                                </div>
                                <div class="orders-flex-content">
                                    <div class="icon">
                                        <i class="las la-file-invoice-dollar"></i>
                                    </div>
                                    <div class="contents">
                                        <h2 class="order-titles"><?php echo e(float_amount_with_currency_symbol($remaning_balance - $total_earnings)); ?> </h2>
                                        <span class="order-para"> <?php echo e(__('Remaining Balance')); ?> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-middle-flex">
                        <div class="single-flex-middle margin-top-40">
                            <div class="line-charts-wrapper">
                                <div class="line-top-contents">
                                    <h5 class="earning-title"><?php echo e(__('Total Order Overview')); ?></h5>
                                </div>
                                <div class="line-charts">
                                    <canvas id="line-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="single-flex-middle">
                            <div class="single-flex-middle-inner">
                                <div class="line-charts-wrapper margin-top-40">
                                    <div class="line-top-contents">
                                        <h5 class="earning-title"><?php echo e(__('To do List')); ?></h5>
                                        <div class="line-chart-select style-02">
                                            <a href="<?php echo e(route('seller.todolist')); ?>"><span class="text-success btn"><?php echo e(__('See All')); ?></span></a>
                                        </div>
                                    </div>
                                    <?php $__currentLoopData = $to_do_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="single-checbox">
                                        <div class="checkbox-inlines">
                                            <input class="check-input" type="checkbox" id="check15">
                                            <label class="checkbox-label" for="check15"><?php echo e($todo->description); ?> </label>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="line-charts-wrapper margin-top-40">
                                    <div class="line-top-contents">
                                        <h5 class="earning-title"><?php echo e(__('This Month Summery')); ?> </h5>
                                    </div>
                                    <div class="chart-summery-inner">
                                        <div class="single-chart-summery">
                                            <div class="icon">
                                                <i class="las la-tasks"></i>
                                            </div>
                                            <div class="contents">
                                                <h4 class="title"><?php echo e($this_month_order_count); ?> </h4>
                                                <span class="title-para"><?php echo e(__('Order')); ?> </span>
                                            </div>
                                        </div>
                                        <div class="single-chart-summery">
                                            <div class="icon">
                                                <i class="las la-dollar-sign"></i>
                                            </div>
                                            <div class="contents">
                                                <h4 class="title"> <?php echo e(float_amount_with_currency_symbol($this_month_earnings)); ?> </h4>
                                                <span class="title-para"><?php echo e(__('Earning')); ?> </span>
                                            </div>
                                        </div>
                                        <div class="single-chart-summery">
                                            <div class="icon">
                                                <i class="las la-file-invoice-dollar"></i>
                                            </div>
                                            <div class="contents">
                                                <h4 class="title"> <?php echo e(float_amount_with_currency_symbol($this_month_balance_without_tax_and_admin_commission)); ?> </h4>
                                                <span class="title-para"> <?php echo e(__('Balance')); ?> </span>
                                            </div>
                                        </div>
                                        <div class="single-chart-summery">
                                            <div class="icon">
                                                <i class="las la-male"></i>
                                            </div>
                                            <div class="contents">
                                                <h4 class="title"> <?php echo e($buyer_count); ?> </h4>
                                                <span class="title-para"><?php echo e(__('Total Buyer')); ?> </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-middle-flex style-02">
                        <?php if($last_five_order->count() >= 1): ?>
                            <div class="single-flex-middle margin-top-40">
                                <div class="line-charts-wrapper">
                                    <div class="table-responsive table-responsive--md">
                                        <table class="custom--table">
                                            <thead>
                                                <tr>
                                                    <th> <?php echo e(__('Client Name')); ?> </th>
                                                    <th><?php echo e(__('Status')); ?></th>
                                                    <th> <?php echo e(__('Location')); ?> </th>
                                                    <th><?php echo e(__('Price')); ?> </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $last_five_order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td data-label="Client Name"><?php echo e($order->name); ?> </td>
                                                    <?php if($order->status == 0): ?> <td data-label="Status" class="pending"><span><?php echo e(__('Pending')); ?></span></td><?php endif; ?>
                                                    <?php if($order->status == 1): ?> <td data-label="Status" class="order-active"><span><?php echo e(__('Active')); ?></span></td><?php endif; ?>
                                                    <?php if($order->status == 2): ?> <td data-label="Status" class="completed"><span><?php echo e(__('Completed')); ?></span></td><?php endif; ?>
                                                    <?php if($order->status == 3): ?> <td data-label="Status" class="order-deliver"><span><?php echo e(__('Delivered')); ?></span></td><?php endif; ?>
                                                    <?php if($order->status == 4): ?> <td data-label="Status" class="canceled"><span><?php echo e(__('Cancelled')); ?></span></td><?php endif; ?>
                                                    <td data-label="Location"><?php echo e($order->email); ?></td>
                                                    <td data-label="Price"> <?php echo e(float_amount_with_currency_symbol($order->total)); ?> </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="single-flex-middle margin-top-40">
                            <div class="line-charts-wrapper">
                                <div class="line-top-contents">
                                    <h5 class="earning-title"><?php echo e(__('Weekly Work Summery')); ?> </h5>
                                </div>
                                <div class="group-bar-charts">
                                    <canvas id="bar-chart-grouped"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard area end -->
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
        <script>
            "use strict";
            $(document).ready(function () {

                 /* Line Charts */
                new Chart(document.getElementById("line-chart"), {
                    type: 'line',
                    data: {
                        labels: [<?php $__currentLoopData = $month_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                        datasets: [{
                            data: [<?php $__currentLoopData = $monthly_order_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                            label: "Order",
                            borderColor: "#1DBF73",
                            borderWidth: 3,
                            fill: false,
                            pointBorderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointRadius: 5,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "#1DBF73",
                        }]
                    },
                });

                 /* Group Bar Charts */
                new Chart(document.getElementById("bar-chart-grouped"), {
                    type: 'bar',
                    data: {
                        labels: [<?php $__currentLoopData = $days_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                        datasets: [
                            {
                                label: "Pending",
                                backgroundColor: "#2F98DC",
                                data: [<?php $__currentLoopData = $pending_order_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                                barThickness: 10,
                                hoverBackgroundColor: '#fff',
                                hoverBorderColor: '#2F98DC',
                                borderColor: '#fff',
                                borderWidth: 2,
                            },
                            {
                                label: "Active",
                                backgroundColor: "#FFB307",
                                data: [<?php $__currentLoopData = $active_order_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                                barThickness: 10,
                                hoverBackgroundColor: '#fff',
                                hoverBorderColor: '#FFB307',
                                borderColor: '#fff',
                                borderWidth: 2,
                             },
                            {
                                label: "Complete",
                                backgroundColor: "#6560FF",
                                data: [<?php $__currentLoopData = $complete_order_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                                barThickness: 10,
                                hoverBackgroundColor: '#fff',
                                hoverBorderColor: '#6560FF',
                                borderColor: '#fff',
                                borderWidth: 2,
                            }
                        ],
                    },
                });
               
            });
        </script>
    <?php $__env->stopSection(); ?>    
<?php echo $__env->make('frontend.user.seller.seller-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nazmul/public_html/qixer/@core/resources/views/frontend/user/seller/dashboard/dashboard.blade.php ENDPATH**/ ?>