@extends('layouts.pos')
@push('css')
<style>
    .pos-wrapper { display: grid; grid-template-columns: 1fr 420px; height: 100vh; }
    .pos-left { padding: 1.5rem; overflow-y: auto; background: var(--bg-main); }
    .pos-right { background: var(--bg-sidebar); border-left: 1px solid var(--border); display: flex; flex-direction: column; }
    .pos-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); }
    .search-box { position: relative; margin-bottom: 1.5rem; }
    .search-box input { width: 100%; padding: 1rem 1rem 1rem 3rem; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 12px; color: white; font-size: 1rem; outline: none; }
    .search-box input:focus { border-color: var(--accent); }
    .search-box .icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
    .product-card { background: var(--bg-card); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1rem; cursor: pointer; transition: all 0.2s; text-align: center; }
    .product-card:hover { border-color: var(--accent); transform: translateY(-2px); }
    .product-card .price { color: var(--accent); font-weight: 700; font-size: 1rem; margin-top: 0.5rem; }
    .product-card .name { font-size: 0.875rem; margin-top: 0.25rem; }
    .product-card .stock { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .cart-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); }
    .cart-body { flex: 1; overflow-y: auto; padding: 1rem 1.5rem; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(255,255,255,0.03); border-radius: 8px; margin-bottom: 0.5rem; }
    .cart-item .info { flex: 1; }
    .cart-item .info .item-name { font-size: 0.875rem; font-weight: 500; }
    .cart-item .info .item-price { font-size: 0.75rem; color: var(--text-muted); }
    .cart-item .qty-controls { display: flex; align-items: center; gap: 0.5rem; }
    .cart-item .qty-controls button { width: 28px; height: 28px; border-radius: 6px; border: 1px solid var(--border); background: transparent; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .cart-item .qty-controls button:hover { background: var(--accent); border-color: var(--accent); }
    .cart-item .item-subtotal { font-weight: 600; min-width: 90px; text-align: right; }
    .cart-item .remove-btn { color: var(--danger); cursor: pointer; margin-left: 0.5rem; }
    .cart-footer { padding: 1.5rem; border-top: 1px solid var(--border); }
    .cart-total { display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; }
    .pay-btn { width: 100%; padding: 1rem; background: linear-gradient(135deg, var(--accent), #059669); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s; }
    .pay-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(16,185,129,0.3); }
    .pay-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
    /* Payment Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: none; align-items: center; justify-content: center; z-index: 100; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: var(--bg-sidebar); border: 1px solid var(--glass-border); border-radius: 16px; padding: 2rem; width: 100%; max-width: 460px; }
    .payment-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
    .payment-method { padding: 0.75rem; border: 2px solid var(--border); border-radius: 10px; text-align: center; cursor: pointer; transition: all 0.2s; }
    .payment-method.active { border-color: var(--accent); background: rgba(16,185,129,0.1); }
    .payment-method:hover { border-color: var(--accent); }
    .quick-cash { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem; }
    .quick-cash button { padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 8px; color: white; cursor: pointer; font-size: 0.875rem; }
    .quick-cash button:hover { background: var(--accent); border-color: var(--accent); }
    /* Success Modal */
    .success-modal .checkmark { width: 60px; height: 60px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); text-decoration: none; margin-bottom: 1rem; font-size: 0.875rem; }
    .back-link:hover { color: white; }
</style>
@endpush

@section('content')
<div class="pos-wrapper">
    {{-- LEFT: Products --}}
    <div class="pos-left">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div>
                <a href="/dashboard" class="back-link"><i data-lucide="arrow-left" style="width:16px;"></i> Dashboard</a>
                <h2 style="font-size: 1.25rem; font-weight: 700;">Kasir POS</h2>
                <p style="color: var(--text-muted); font-size: 0.8rem;">Shift #{{ $shift->id }} | {{ Auth::user()->name }}</p>
            </div>
            <a href="/shift/close" class="btn btn-danger" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                <i data-lucide="x-circle" style="width:16px;"></i> Tutup Shift
            </a>
        </div>

        <div class="search-box">
            <i data-lucide="search" class="icon" style="width: 20px;"></i>
            <input type="text" id="searchInput" placeholder="Scan barcode atau ketik nama produk..." autofocus>
        </div>

        <div class="product-grid" id="productGrid">
            <p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">Ketik untuk mencari produk...</p>
        </div>
    </div>

    {{-- RIGHT: Cart --}}
    <div class="pos-right">
        <div class="cart-header">
            <h3 style="font-size: 1rem;"><i data-lucide="shopping-cart" style="width: 18px; display: inline; vertical-align: middle;"></i> Keranjang</h3>
        </div>
        <div class="cart-body" id="cartBody">
            <p id="cartEmpty" style="color: var(--text-muted); text-align: center; padding: 2rem; font-size: 0.875rem;">Keranjang kosong</p>
        </div>
        <div class="cart-footer">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                <span>Subtotal</span><span id="subtotalDisplay">Rp 0</span>
            </div>
            <div class="cart-total">
                <span>Total</span><span id="totalDisplay">Rp 0</span>
            </div>
            <button class="pay-btn" id="payBtn" disabled onclick="openPaymentModal()">
                <i data-lucide="credit-card" style="width: 20px; display: inline; vertical-align: middle;"></i> Bayar
            </button>
        </div>
    </div>
</div>

{{-- Payment Modal --}}
<div class="modal-overlay" id="paymentModal">
    <div class="modal-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3>Pembayaran</h3>
            <button onclick="closePaymentModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i data-lucide="x" style="width: 20px;"></i></button>
        </div>
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total yang harus dibayar</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--accent);" id="modalTotal">Rp 0</p>
        </div>
        <div class="payment-methods">
            <div class="payment-method active" data-method="cash" onclick="selectPaymentMethod('cash')">💵 Tunai</div>
            <div class="payment-method" data-method="qris" onclick="selectPaymentMethod('qris')">📱 QRIS</div>
            <div class="payment-method" data-method="edc" onclick="selectPaymentMethod('edc')">💳 EDC</div>
        </div>
        <div id="cashSection">
            <div class="form-group">
                <label class="form-label">Nominal Pembayaran</label>
                <input type="number" id="paymentAmountInput" class="form-input" style="font-size: 1.25rem; text-align: center;" placeholder="0">
            </div>
            <div class="quick-cash" id="quickCash"></div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: rgba(16,185,129,0.1); border-radius: 8px; margin-bottom: 1rem;">
                <span>Kembalian</span><span id="changeDisplay" style="font-weight: 700; color: var(--accent);">Rp 0</span>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">No. HP Member (opsional)</label>
            <input type="text" id="memberPhoneInput" class="form-input" placeholder="08xxxxxxxxxx">
        </div>
        <button class="pay-btn" id="confirmPayBtn" onclick="processPayment()">Proses Pembayaran</button>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal-overlay" id="successModal">
    <div class="modal-box success-modal" style="text-align: center;">
        <div class="checkmark"><i data-lucide="check" style="color: white; width: 30px; height: 30px;"></i></div>
        <h3 style="margin-bottom: 0.5rem;">Transaksi Berhasil!</h3>
        <p id="successInvoice" style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;"></p>
        <p id="successChange" style="font-size: 1.25rem; font-weight: 700; color: var(--accent); margin-bottom: 1.5rem;"></p>
        <button class="pay-btn" onclick="newTransaction()">Transaksi Baru</button>
    </div>
</div>
@endsection

@push('js')
<script>
let cart = [];
let selectedPaymentMethod = 'cash';

const searchInput = document.getElementById('searchInput');
const productGrid = document.getElementById('productGrid');
const cartBody = document.getElementById('cartBody');
let searchTimeout;

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => searchProducts(this.value), 300);
});

searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchProducts(this.value);
    }
});

function searchProducts(query) {
    if (query.length < 1) {
        productGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">Ketik untuk mencari produk...</p>';
        return;
    }
    fetch('/pos/search', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ q: query })
    })
    .then(r => r.json())
    .then(products => {
        if (products.length === 0) {
            productGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">Produk tidak ditemukan</p>';
            return;
        }
        // If barcode exact match and only one result, add to cart directly
        if (products.length === 1 && products[0].barcode === query) {
            addToCart(products[0]);
            searchInput.value = '';
            searchInput.focus();
            productGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">✅ Produk ditambahkan ke keranjang</p>';
            return;
        }
        productGrid.innerHTML = products.map(p => `
            <div class="product-card" onclick='addToCart(${JSON.stringify(p)})'>
                <div class="price">Rp ${Number(p.selling_price).toLocaleString('id-ID')}</div>
                <div class="name">${p.name}</div>
                <div class="stock">Stok: ${p.stock} ${p.unit}</div>
            </div>
        `).join('');
    });
}

function addToCart(product) {
    const existing = cart.find(i => i.product_id === product.id);
    if (existing) {
        if (existing.quantity >= product.stock) { alert('Stok tidak mencukupi!'); return; }
        existing.quantity++;
        existing.subtotal = existing.quantity * existing.price;
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            price: parseFloat(product.selling_price),
            quantity: 1,
            stock: product.stock,
            subtotal: parseFloat(product.selling_price),
        });
    }
    renderCart();
}

function renderCart() {
    if (cart.length === 0) {
        cartBody.innerHTML = '<p id="cartEmpty" style="color: var(--text-muted); text-align: center; padding: 2rem; font-size: 0.875rem;">Keranjang kosong</p>';
        document.getElementById('payBtn').disabled = true;
    } else {
        cartBody.innerHTML = cart.map((item, i) => `
            <div class="cart-item">
                <div class="info">
                    <div class="item-name">${item.name}</div>
                    <div class="item-price">Rp ${item.price.toLocaleString('id-ID')} × ${item.quantity}</div>
                </div>
                <div class="qty-controls">
                    <button onclick="changeQty(${i}, -1)">−</button>
                    <span style="min-width: 20px; text-align: center;">${item.quantity}</span>
                    <button onclick="changeQty(${i}, 1)">+</button>
                </div>
                <div class="item-subtotal">Rp ${item.subtotal.toLocaleString('id-ID')}</div>
                <span class="remove-btn" onclick="removeItem(${i})"><i data-lucide="trash-2" style="width:16px;"></i></span>
            </div>
        `).join('');
        document.getElementById('payBtn').disabled = false;
        lucide.createIcons();
    }
    const total = cart.reduce((sum, i) => sum + i.subtotal, 0);
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function changeQty(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) { cart.splice(index, 1); }
    else if (cart[index].quantity > cart[index].stock) { cart[index].quantity = cart[index].stock; alert('Stok maks!'); }
    else { cart[index].subtotal = cart[index].quantity * cart[index].price; }
    renderCart();
}

function removeItem(index) { cart.splice(index, 1); renderCart(); }

function getTotal() { return cart.reduce((sum, i) => sum + i.subtotal, 0); }

function openPaymentModal() {
    const total = getTotal();
    document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('paymentAmountInput').value = '';
    document.getElementById('changeDisplay').textContent = 'Rp 0';
    // Quick cash options
    const quickValues = [total, Math.ceil(total/10000)*10000, Math.ceil(total/50000)*50000, Math.ceil(total/100000)*100000];
    const unique = [...new Set(quickValues)].filter(v => v >= total).slice(0, 6);
    document.getElementById('quickCash').innerHTML = unique.map(v => `<button onclick="document.getElementById('paymentAmountInput').value=${v};updateChange()">${'Rp '+v.toLocaleString('id-ID')}</button>`).join('');
    document.getElementById('paymentModal').classList.add('active');
    lucide.createIcons();
    setTimeout(() => document.getElementById('paymentAmountInput').focus(), 100);
}

function closePaymentModal() { document.getElementById('paymentModal').classList.remove('active'); }

document.getElementById('paymentAmountInput').addEventListener('input', updateChange);

function updateChange() {
    const paid = parseFloat(document.getElementById('paymentAmountInput').value) || 0;
    const change = Math.max(0, paid - getTotal());
    document.getElementById('changeDisplay').textContent = 'Rp ' + change.toLocaleString('id-ID');
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
    document.querySelector(`[data-method="${method}"]`).classList.add('active');
    document.getElementById('cashSection').style.display = method === 'cash' ? 'block' : 'none';
    if (method !== 'cash') { document.getElementById('paymentAmountInput').value = getTotal(); updateChange(); }
}

function processPayment() {
    const total = getTotal();
    let paymentAmount = parseFloat(document.getElementById('paymentAmountInput').value) || 0;
    if (selectedPaymentMethod !== 'cash') paymentAmount = total;
    if (paymentAmount < total && selectedPaymentMethod === 'cash') { alert('Nominal pembayaran kurang!'); return; }

    document.getElementById('confirmPayBtn').disabled = true;
    document.getElementById('confirmPayBtn').textContent = 'Memproses...';

    fetch('/pos/store', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({
            items: cart.map(i => ({ product_id: i.product_id, quantity: i.quantity })),
            payment_method: selectedPaymentMethod,
            payment_amount: paymentAmount,
            member_phone: document.getElementById('memberPhoneInput').value || null,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); document.getElementById('confirmPayBtn').disabled = false; document.getElementById('confirmPayBtn').textContent = 'Proses Pembayaran'; return; }
        closePaymentModal();
        document.getElementById('successInvoice').textContent = data.invoice_number;
        document.getElementById('successChange').textContent = selectedPaymentMethod === 'cash' ? 'Kembalian: Rp ' + Number(data.change_amount).toLocaleString('id-ID') : 'Pembayaran ' + selectedPaymentMethod.toUpperCase();
        document.getElementById('successModal').classList.add('active');
        lucide.createIcons();
    })
    .catch(() => { alert('Terjadi kesalahan!'); })
    .finally(() => { document.getElementById('confirmPayBtn').disabled = false; document.getElementById('confirmPayBtn').textContent = 'Proses Pembayaran'; });
}

function newTransaction() {
    cart = [];
    renderCart();
    document.getElementById('successModal').classList.remove('active');
    searchInput.value = '';
    searchInput.focus();
    productGrid.innerHTML = '<p style="color: var(--text-muted); grid-column: 1/-1; text-align: center; padding: 2rem;">Ketik untuk mencari produk...</p>';
}
</script>
@endpush
