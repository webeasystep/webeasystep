<!DOCTYPE html>
<html lang="ar" dir="rtl">
<?= $this->include('site_layout/header'); ?>
<!-- render Head here -->

<body class="<?= lang("Site.dir") ?>" >

    <?= $this->include('site_layout/navbar'); ?>

    <main role="main" id="main-content">
        <?= $this->renderSection('content'); ?>
    </main>

    <?= $this->include('site_layout/footer'); ?>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

    <?= $this->include('site_layout/js'); ?>
    <!-- render Javascript here -->
    <?= $this->renderSection('js'); ?>
    <style>
        @keyframes shakeCart {
            0% {
                transform: rotate(0);
            }

            25% {
                transform: rotate(15deg);
            }

            50% {
                transform: rotate(0);
            }

            75% {
                transform: rotate(-15deg);
            }

            100% {
                transform: rotate(0);
            }
        }

        .cart-shake i {
            animation: shakeCart 0.4s ease-in-out;
            display: inline-block;
        }
    </style>
    <script>
        let currentCsrfToken = '<?= csrf_hash() ?>';

        function addToCart(itemType, itemId) {
    <?php if (!auth()->loggedIn()): ?>
                    const checkoutUrl = new URL('<?= site_url("cart/checkout") ?>', window.location.origin);
                    checkoutUrl.searchParams.set('item_type', itemType);
                    checkoutUrl.searchParams.set('item_id', itemId);
                    window.location.href = checkoutUrl.toString();
                return;
    <?php endif; ?>

                let data = {
                    item_type: itemType,
                    item_id: itemId
                };
            data['<?= csrf_token() ?>'] = currentCsrfToken;

            $.ajax({
                url: '<?= site_url("cart/add") ?>',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.csrf_token) {
                        currentCsrfToken = response.csrf_token;
                    }

                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        let badges = document.querySelectorAll('.cart-badge');
                        if (response.cart_count !== undefined) {
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
                        }

                        let cartLinks = document.querySelectorAll('.cart-link');
                        cartLinks.forEach(function(cartLink) {
                            cartLink.classList.remove('cart-shake');
                            // Trigger reflow
                            void cartLink.offsetWidth;
                            cartLink.classList.add('cart-shake');
                        });
                    } else {
                        if (response.cart_count !== undefined) {
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
                        }
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function (xhr) {
                    // CSRF might be refreshed even on error in some cases, so reload on 403
                    if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'عذراً، انتهت صلاحية الجلسة، سيتم تحديث الصفحة.',
                            confirmButtonText: 'حسناً'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'حدث خطأ أثناء الاتصال بالسيرفر.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });
        }
    </script>
    <!-- Javascript -->
</body>

</html>
