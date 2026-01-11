/**
 * 📚 مكتبة - JavaScript
 * بدون تأثيرات اختفاء
 */

// إدارة السلة
const CartManager = {
    init() {
        this.bindEvents();
    },

    bindEvents() {
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.addToCart(e);
            });
        });

        document.querySelectorAll('.remove-from-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.removeFromCart(e);
            });
        });

        document.querySelectorAll('.quantity-increase').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.updateQuantity(e, 1);
            });
        });

        document.querySelectorAll('.quantity-decrease').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.updateQuantity(e, -1);
            });
        });
    },

    addToCart(e) {
        const btn = e.currentTarget;
        const bookId = btn.dataset.bookId;
        
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'جاري الإضافة...';

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `book_id=${bookId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cartCount);
                this.showNotification('تمت الإضافة للسلة!', 'success');
                btn.innerHTML = '✓ تمت الإضافة';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            this.showNotification(error.message || 'حدث خطأ', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    },

    removeFromCart(e) {
        const btn = e.currentTarget;
        const cartId = btn.dataset.cartId;
        const cartItem = btn.closest('.cart-item');

        if (confirm('هل تريد حذف هذا العنصر؟')) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartItem.remove();
                    location.reload();
                }
            });
        }
    },

    updateQuantity(e, change) {
        const btn = e.currentTarget;
        const cartId = btn.dataset.cartId;
        const quantitySpan = btn.parentElement.querySelector('span');
        let newQty = parseInt(quantitySpan.textContent) + change;

        if (newQty < 1) newQty = 1;
        if (newQty > 99) newQty = 99;

        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cart_id=${cartId}&quantity=${newQty}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                quantitySpan.textContent = newQty;
                location.reload();
            }
        });
    },

    updateCartCount(count) {
        const badge = document.querySelector('.cart-badge');
        if (badge && count !== undefined) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-flex' : 'none';
        }
    },

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 16px 24px;
            background: ${type === 'success' ? '#2d5a27' : '#c41e3a'};
            color: white;
            z-index: 9999;
            font-family: var(--font-main);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
};

// التهيئة
document.addEventListener('DOMContentLoaded', () => {
    CartManager.init();
});
