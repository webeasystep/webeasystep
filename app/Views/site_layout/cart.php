<?= $this->extend('site_layout/template') ?>

<?= $this->section('content') ?>
<div class="untree_co-hero inner-page overlay" style="background-image: url('<?= base_url('site/images/hero_bg_1.jpg') ?>');">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center text-white">
                        <h1 class="mb-4 heading" data-aos="fade-up" data-aos-delay="100"><?= esc($title) ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="untree_co-section">
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5">
                <i class="icon-shopping-cart mb-3" style="font-size: 4rem; color: #ccc;"></i>
                <h3 class="mb-4">السلة فارغة حالياً</h3>
                <a href="<?= site_url('/') ?>" class="btn btn-primary">تصفح المقررات</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                            <h4 class="mb-0">محتويات السلة</h4>
                        </div>
                        <div class="card-body">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="row align-items-center mb-4 pb-4 border-bottom" id="cart-item-<?= $item->cart_id ?>">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <?php if ($item->image && $item->image !== '[]'): ?>
                                            <?php if ($item->item_type === 'course'): ?>
                                                <img src="<?= base_url('uploads/courses/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="<?= base_url('uploads/bundles/' . $item->image) ?>" alt="<?= esc($item->title) ?>" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;" onerror="this.src='<?= base_url('uploads/courses/' . $item->image) ?>'">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($item->item_type === 'bundle'): ?>
                                                <i class="fas fa-layer-group" style="font-size: 3rem; color: #136ad5; display: block; text-align: center; margin-top: 10px;"></i>
                                            <?php else: ?>
                                                <div class="text-center" style="border-radius: 8px; padding: 20px;">
                                                    <i class="fas fa-book-open" style="font-size: 3rem; color: #136ad5;"></i>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="mb-1"><?= esc($item->title) ?></h5>
                                        <?php if ($item->item_type === 'bundle'): ?>
                                            <span class="badge badge-primary mb-2">باقة خاصة</span>
                                            <ul class="list-unstyled mb-0 text-muted" style="font-size: 0.9rem;">
                                                <?php foreach ($item->courses as $c): ?>
                                                    <li><i class="icon-check text-success mr-1"></i> <?= esc($c['course_title']) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-3 text-md-right mt-3 mt-md-0">
                                        <div class="font-weight-bold text-primary mb-2" style="font-size: 1.2rem;">
                                            <?= number_format($item->price, 2) ?> <img src="<?= base_url('site/images/saudi_riyal_symbol.svg') ?>" alt="SAR" style="height: 20px; margin-right: 2px; vertical-align: text-bottom;">
                                        </div>
                                        <?php if ($item->item_type === 'bundle' && isset($item->original_price)): ?>
                                            <div class="text-muted mb-2" style="text-decoration: line-through; font-size: 0.9rem;">
                                                <?= number_format($item->original_price, 2) ?> <img src="<?= base_url('site/images/saudi_riyal_symbol.svg') ?>" alt="SAR" style="height: 14px; margin-right: 2px; vertical-align: text-bottom; opacity: 0.7;">
                                            </div>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(<?= $item->cart_id ?>)">
                                            <i class="icon-trash"></i> إزالة
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h4 class="mb-4">ملخص الطلب</h4>
                            <div class="d-flex justify-content-between mb-3">
                                <span>الإجمالي:</span>
                                <strong class="text-primary" style="font-size: 1.2rem;"><span id="cart-total"><?= number_format($cart_total, 2) ?></span> <img src="<?= base_url('site/images/saudi_riyal_symbol.svg') ?>" alt="SAR" style="height: 20px; margin-right: 2px; vertical-align: text-bottom;"></strong>
                            </div>
                            <hr>
                            <a href="<?= site_url('cart/checkout') ?>" class="btn btn-primary btn-block btn-lg" style="border-radius: var(--radius-md); font-weight: bold;">
                                إتمام الشراء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function removeFromCart(cartId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'هل تريد إزالة هذا العنصر من السلة؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، إزالة',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url('cart/remove') ?>',
                type: 'POST',
                data: (function() {
                    let d = { cart_id: cartId };
                    d['<?= csrf_token() ?>'] = currentCsrfToken;
                    return d;
                })(),
                success: function(response) {
                    if (response.csrf_token) {
                        currentCsrfToken = response.csrf_token;
                    }
                    if(response.success) {
                        if (response.cart_count > 0) {
                            // Fade out the item
                            $('#cart-item-' + cartId).fadeOut(400, function() {
                                $(this).remove();
                            });
                            
                            // Update total
                            $('#cart-total').text(response.cart_total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            
                            // Update badge
                            let badges = document.querySelectorAll('.cart-badge');
                            badges.forEach(function(badge) {
                                if (response.cart_count > 0) {
                                    badge.innerText = response.cart_count;
                                    badge.classList.remove('d-none-important');
                                    badge.classList.add('d-inline-important');
                                } else {
                                    badge.innerText = '';
                                    badge.classList.remove('d-inline-important');
                                    badge.classList.add('d-none-important');
                                }
                            });
                            
                            // Shake icon
                            let cartLinks = document.querySelectorAll('.cart-link');
                            cartLinks.forEach(function(cartLink) {
                                cartLink.classList.remove('cart-shake');
                                void cartLink.offsetWidth; // trigger reflow
                                cartLink.classList.add('cart-shake');
                            });
                            
                            // Toast success
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'تمت إزالة العنصر من السلة',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            location.reload();
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء الإزالة'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ في الاتصال بالخادم'
                    });
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
