<?php $__env->startSection('title', '404 - Page Not Found'); ?>

<?php $__env->startSection('content'); ?>
<section class="section error-page">
    <div class="error-page-code">404</div>
    <h1>Page Not Found</h1>
    <p class="meta error-page-message">Sorry, the page you're looking for doesn't exist or has been moved.</p>
    <div class="error-page-actions">
        <a class="cta-btn" href="<?php echo e(route('store.home')); ?>">Go Home</a>
        <a class="btn-soft" href="<?php echo e(route('store.products')); ?>">Shop Products</a>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('store.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\numnam-api\resources\views/errors/404.blade.php ENDPATH**/ ?>