@extends('layouts.app')

@section('title', 'Point of Sale (POS)')

@push('styles')
    <style>
        body {
            background-color: #f4f6f9;
        }

        .pos-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
            margin-bottom: 1rem;
        }

        .product-grid-container {
            max-height: calc(100vh - 300px);
            overflow-y: auto;
            padding: 5px;
        }

        .product-card-new {
            cursor: pointer;
            padding: 0.5rem;
            text-align: center;
            transition: transform 0.2s;
            border-radius: 0.5rem;
        }

        .product-card-new:hover {
            transform: scale(1.05);
        }

        .product-code-box {
            background-color: #e9ecef;
            border-radius: 0.375rem;
            width: 100%;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .product-code-box span {
            font-size: 2.5rem;
            font-weight: 700;
            color: #495057;
        }

        .product-name-label {
            font-weight: 600;
            color: #343a40;
            font-size: 0.9rem;
        }

        .cart-card {
            height: calc(100vh - 72px);
        }

        .cart-items {
            flex-grow: 1;
            overflow-y: auto;
        }

        .cart-summary .summary-detail span {
            font-size: 0.95rem;
            color: #495057;
        }
    </style>
@endpush

@section('content')
    <nav class="navbar navbar-expand-lg pos-navbar">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">POS CAFE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#posNavbar"
                aria-controls="posNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="posNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders') }}">Daftar Order</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('report.sales') }}">Riwayat Transaksi</a></li>
                </ul>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="btn btn-outline-danger d-flex align-items-center">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row h-100">
            <div class="col-md-7 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <h4 class="card-title mb-0">Pilih Menu</h4> <input type="text" id="product-search"
                                class="form-control" placeholder="Cari menu..." style="width: 250px;">
                        </div>
                        <ul class="nav nav-pills mb-3 flex-shrink-0">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                                    data-bs-target="#pills-all">Semua</button></li> @foreach($categories as $category)
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                                                data-bs-target="#pills-cat-{{ $category->id }}">{{ $category->name }}</button></li>
                                    @endforeach
                        </ul>
                        <div class="tab-content flex-grow-1 product-grid-container">
                            <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                                <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-2">
                                    @forelse($products as $product)
                                        <div class="col product-item" data-name="{{ strtolower($product->name) }}"
                                            data-category="cat-{{ $product->category_id }}">
                                            <div class="product-card-new"
                                                onclick='addToCart({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }})'>
                                                <div class="product-code-box">
                                                    <span>{{ strtoupper(substr($product->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="product-name-label">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center text-muted mt-5">
                                            <p>Belum ada produk yang ditambahkan.</p>
                                    </div> @endforelse
                                </div>
                            </div>
                            @foreach($categories as $category)
                                <div class="tab-pane fade" id="pills-cat-{{ $category->id }}" role="tabpanel">
                                    <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-2">
                                        @php $categoryProducts = $products->where('category_id', $category->id); @endphp
                                        @forelse($categoryProducts as $product)
                                            <div class="col product-item" data-name="{{ strtolower($product->name) }}"
                                                data-category="cat-{{ $product->category_id }}">
                                                <div class="product-card-new"
                                                    onclick='addToCart({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }})'>
                                                    <div class="product-code-box">
                                                        <span>{{ strtoupper(substr($product->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div class="product-name-label">{{ $product->name }}</div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center text-muted mt-5">
                                                <p>Tidak ada produk dalam kategori ini.</p>
                                        </div> @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm cart-card">
                    <div class="card-body d-flex flex-column p-3">
                        <h4 class="text-center mb-3 pb-3 border-bottom">Pesanan</h4>
                        <div class="cart-items" id="cart-items">
                            <div class="h-100 d-flex justify-content-center align-items-center"><span
                                    class="text-muted">Belum ada pesanan</span></div>
                        </div>
                        <div class="cart-summary mt-auto pt-3 border-top">
                            <div class="summary-detail d-flex justify-content-between mb-1"><span>Subtotal:</span><span
                                    id="cart-subtotal">Rp 0</span></div>
                            <div class="summary-detail d-flex justify-content-between mb-1"><span>Tax (10%):</span><span
                                    id="cart-tax">Rp 0</span></div>
                            <div class="summary-detail d-flex justify-content-between mb-2"><span>Service (5%):</span><span
                                    id="cart-service">Rp 0</span></div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total:</span><span
                                    id="cart-total">Rp 0</span></div>
                            <div class="d-grid"><button class="btn btn-primary btn-lg" id="checkout-button"
                                    data-bs-toggle="modal" data-bs-target="#checkoutModal" disabled><i
                                        class="fa-solid fa-cash-register me-2"></i> Bayar</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">PAYMENT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment-method-select" class="form-label">Metode Payment</label>
                        <select class="form-select form-select-lg" id="payment-method-select">
                            <option value="Pilih">Pilih Metode Pembayaran</option>
                            <option value="EDC BCA">EDC BCA</option>
                            <option value="EDC BRI">EDC BRI</option>
                            <option value="EDC BNI">EDC BNI</option>
                            <option value="EDC PANIN">EDC PANIN</option>
                            <option value="Qris BCA">Qris BCA</option>
                            <option value="Qris BRI">Qris BRI</option>
                            <option value="Qris BNI">Qris BNI</option>
                            <option value="Qris MANDIRI">Qris MANDIRI</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>

                    <div id="cash-fields-container" style="display: none;">
                        <hr>
                        <div class="mb-3">
                            <label for="cash-amount-paid" class="form-label">Jumlah Uang Diterima</label>
                            <input type="number" id="cash-amount-paid" class="form-control form-control-lg"
                                placeholder="Masukkan jumlah uang...">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Kembalian</label>
                            <input type="text" id="cash-change" class="form-control form-control-lg fw-bold text-success"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="process-payment-button">Konfirmasi Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let cart = {};
        let cartTotals = { subtotal: 0, tax: 0, service: 0, grandTotal: 0 };
        function formatRupiah(number) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(number)); }
        function addToCart(id, name, price) { if (cart[id]) { cart[id].quantity++; } else { cart[id] = { name: name, price: price, quantity: 1 }; } updateCart(); }
        function updateCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const checkoutButton = document.getElementById('checkout-button');
            const cartSubtotalElement = document.getElementById('cart-subtotal');
            const cartTaxElement = document.getElementById('cart-tax');
            const cartServiceElement = document.getElementById('cart-service');
            const cartTotalElement = document.getElementById('cart-total');
            let subtotal = 0;
            if (Object.keys(cart).length === 0) {
                cartItemsContainer.innerHTML = `<div class="h-100 d-flex justify-content-center align-items-center"><span class="text-muted">Belum ada pesanan</span></div>`;
                checkoutButton.disabled = true;
                cartSubtotalElement.innerText = 'Rp 0'; cartTaxElement.innerText = 'Rp 0'; cartServiceElement.innerText = 'Rp 0'; cartTotalElement.innerText = 'Rp 0';
            } else {
                cartItemsContainer.innerHTML = '';
                for (const id in cart) {
                    const item = cart[id];
                    subtotal += item.price * item.quantity;
                    const itemElement = document.createElement('div');
                    itemElement.className = 'd-flex justify-content-between align-items-center mb-3 pb-2 border-bottom';
                    itemElement.innerHTML = `<div><h6 class="mb-0 small">${item.name}</h6><small class="text-muted">${item.quantity} x ${formatRupiah(item.price)}</small></div><div class="d-flex align-items-center"><button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="decreaseQuantity(${id})">-</button><span class="mx-2 fw-bold">${item.quantity}</span><button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="increaseQuantity(${id})">+</button><button class="btn btn-sm btn-link text-danger ms-2" onclick="removeFromCart(${id})"><i class="fa-solid fa-trash-can"></i></button></div>`;
                    cartItemsContainer.appendChild(itemElement);
                }
                checkoutButton.disabled = false;
            }
            const tax = subtotal * 0.10;
            const service = subtotal * 0.05;
            const grandTotal = subtotal + tax + service;
            cartTotals = { subtotal, tax, service, grandTotal };
            cartSubtotalElement.innerText = formatRupiah(subtotal);
            cartTaxElement.innerText = formatRupiah(tax);
            cartServiceElement.innerText = formatRupiah(service);
            cartTotalElement.innerText = formatRupiah(grandTotal);
        }
        function increaseQuantity(id) { if (cart[id]) { cart[id].quantity++; updateCart(); } }
        function decreaseQuantity(id) { if (cart[id] && cart[id].quantity > 1) { cart[id].quantity--; } else { delete cart[id]; } updateCart(); }
        function removeFromCart(id) { if (cart[id]) { delete cart[id]; updateCart(); } }


        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethodSelect = document.getElementById('payment-method-select');
            const cashFieldsContainer = document.getElementById('cash-fields-container');
            const cashAmountInput = document.getElementById('cash-amount-paid');
            const cashChangeOutput = document.getElementById('cash-change');


            paymentMethodSelect.addEventListener('change', function () {
                if (this.value === 'Cash') {
                    cashFieldsContainer.style.display = 'block';
                } else {
                    cashFieldsContainer.style.display = 'none';
                }
            });


            cashAmountInput.addEventListener('keyup', function () {
                const amountPaid = parseInt(this.value) || 0;
                if (amountPaid >= cartTotals.grandTotal) {
                    const change = amountPaid - cartTotals.grandTotal;
                    cashChangeOutput.value = formatRupiah(change);
                } else {
                    cashChangeOutput.value = '';
                }
            });


            document.getElementById('process-payment-button').addEventListener('click', async function () {
                const selectedPaymentMethod = paymentMethodSelect.value;
                if (selectedPaymentMethod === 'Pilih') {
                    Swal.fire('Error!', 'Silakan pilih metode pembayaran terlebih dahulu.', 'error');
                    return;
                }

                let orderDataPayload = {
                    items: Object.keys(cart).map(id => ({ id: id, quantity: cart[id].quantity })),
                    subtotal: cartTotals.subtotal,
                    tax: cartTotals.tax,
                    service: cartTotals.service,
                    total: cartTotals.grandTotal,
                    payment_method: selectedPaymentMethod,
                    amount_paid: cartTotals.grandTotal,
                    change: 0,
                };


                if (selectedPaymentMethod === 'Cash') {
                    const amountPaid = parseInt(cashAmountInput.value) || 0;
                    if (amountPaid < cartTotals.grandTotal) {
                        Swal.fire('Pesanan Batal!', 'Jumlah uang yang dibayarkan kurang.', 'Pesanan Batal!');
                        return;
                    }
                    orderDataPayload.amount_paid = amountPaid;
                    orderDataPayload.change = amountPaid - cartTotals.grandTotal;
                }


                this.disabled = true;
                this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...`;
                try {
                    const response = await fetch("{{ route('orders.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(orderDataPayload)
                    });
                    const result = await response.json();
                    if (response.ok) {
                        Swal.fire('Berhasil!', result.message, 'success');
                        cart = {};
                        updateCart();
                        paymentMethodSelect.selectedIndex = 0;
                        cashFieldsContainer.style.display = 'none';
                        cashAmountInput.value = '';
                        cashChangeOutput.value = '';
                        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                    } else {
                        let errorMsg = result.message || 'Terjadi kesalahan.';
                        if (result.errors) { errorMsg = Object.values(result.errors).flat().join('<br>'); }
                        Swal.fire('Gagal!', errorMsg, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'Tidak dapat terhubung ke server.', 'error');
                } finally {
                    this.disabled = false;
                    this.innerHTML = `Konfirmasi Pembayaran`;
                }
            });

            document.getElementById('product-search').addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('.product-item').forEach(item => {
                    const productName = item.dataset.name;
                    if (productName.includes(searchTerm)) { item.style.display = 'block'; } else { item.style.display = 'none'; }
                });
            });
        });
    </script>
@endpush