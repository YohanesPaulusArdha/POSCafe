@extends('layouts.app')

@section('title', 'Point of Sale (POS)')

@push('styles')
<style>
    
    body {
        background-color: #f4f6f9;
    }
    
    .main-container {
        height: calc(100vh - 56px); 
        margin-top: 1rem;
    }

    .product-card {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e3e6f0;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    .product-img {
        height: 140px;
        object-fit: cover;
    }

    .product-list-container {
        max-height: calc(100vh - 250px); 
        overflow-y: auto;
        padding-right: 15px; 
    }

    .cart-card {
        height: calc(100vh - 72px);
    }
    .cart-items {
        flex-grow: 1;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid main-container">
    <div class="row h-100">
        <div class="col-md-7 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <h4 class="card-title mb-0">Menu Kafe</h4>
                        <div class="d-flex align-items-center">
                            <input type="text" id="product-search" class="form-control" placeholder="Cari menu..." style="width: 250px;">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger ms-2 d-flex align-items-center">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div>
                    </div>

                    <ul class="nav nav-pills mb-3 flex-shrink-0">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-all">Semua</button>
                        </li>
                        @foreach($categories as $category)
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-cat-{{ $category->id }}">{{ $category->name }}</button>
                        </li>
                        @endforeach
                    </ul>

                    <div class="tab-content flex-grow-1 product-list-container">
                        <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                @forelse($products as $product)
                                <div class="col product-item" data-name="{{ strtolower($product->name) }}" data-category="cat-{{ $product->category_id }}">
                                    <div class="card product-card" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                        <img src="https://via.placeholder.com/150/556B2F/FFFFFF?text={{ urlencode($product->name) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="card-title small fw-bold mb-1">{{ $product->name }}</h6>
                                            <p class="card-text small fw-light">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center text-muted mt-5">
                                    <p>Belum ada produk yang ditambahkan.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @foreach($categories as $category)
                        <div class="tab-pane fade" id="pills-cat-{{ $category->id }}" role="tabpanel">
                            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                @php $categoryProducts = $products->where('category_id', $category->id); @endphp
                                @forelse($categoryProducts as $product)
                                <div class="col product-item" data-name="{{ strtolower($product->name) }}" data-category="cat-{{ $product->category_id }}">
                                     <div class="card product-card" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                        <img src="https://via.placeholder.com/150/556B2F/FFFFFF?text={{ urlencode($product->name) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="card-title small fw-bold mb-1">{{ $product->name }}</h6>
                                            <p class="card-text small fw-light">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center text-muted mt-5">
                                    <p>Tidak ada produk dalam kategori ini.</p>
                                </div>
                                @endforelse
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
                        <div class="h-100 d-flex justify-content-center align-items-center">
                            <span class="text-muted">Belum ada pesanan</span>
                        </div>
                    </div>
                    <div class="cart-summary mt-auto pt-3 border-top">
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                            <span>Total:</span>
                            <span id="cart-total">Rp 0</span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" id="checkout-button" data-bs-toggle="modal" data-bs-target="#checkoutModal" disabled>
                                <i class="fa-solid fa-cash-register me-2"></i> Bayar
                            </button>
                        </div>
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
                <h5 class="modal-title" id="checkoutModalLabel">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Total Tagihan</label>
                    <input type="text" id="modal-total" class="form-control form-control-lg fw-bold text-danger" readonly>
                </div>
                <div class="mb-3">
                    <label for="amount-paid" class="form-label">Jumlah Uang Diterima</label>
                    <input type="number" id="amount-paid" class="form-control form-control-lg" placeholder="Masukkan jumlah uang...">
                </div>
                <div class="mb-3">
                     <label class="form-label">Kembalian</label>
                    <input type="text" id="change" class="form-control form-control-lg fw-bold text-success" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="process-payment-button"><i class="fa-solid fa-print"></i> Selesaikan & Cetak Struk</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = {}; 

function formatRupiah(number) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
}

function addToCart(id, name, price) {
    if (cart[id]) {
        cart[id].quantity++;
    } else {
        cart[id] = { name: name, price: price, quantity: 1 };
    }
    updateCart();
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total');
    const checkoutButton = document.getElementById('checkout-button');
    let total = 0;

    if (Object.keys(cart).length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="h-100 d-flex justify-content-center align-items-center">
                <span class="text-muted">Belum ada pesanan</span>
            </div>`;
        checkoutButton.disabled = true;
    } else {
        cartItemsContainer.innerHTML = '';
        for (const id in cart) {
            const item = cart[id];
            total += item.price * item.quantity;

            const itemElement = document.createElement('div');
            itemElement.className = 'd-flex justify-content-between align-items-center mb-3 pb-2 border-bottom';
            itemElement.innerHTML = `
                <div>
                    <h6 class="mb-0 small">${item.name}</h6>
                    <small class="text-muted">${item.quantity} x ${formatRupiah(item.price)}</small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="decreaseQuantity(${id})">-</button>
                    <span class="mx-2 fw-bold">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="increaseQuantity(${id})">+</button>
                    <button class="btn btn-sm btn-link text-danger ms-2" onclick="removeFromCart(${id})"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            `;
            cartItemsContainer.appendChild(itemElement);
        }
        checkoutButton.disabled = false;
    }

    cartTotalElement.innerText = formatRupiah(total);

    document.getElementById('modal-total').value = formatRupiah(total);
    document.getElementById('amount-paid').value = '';
    document.getElementById('change').value = '';
}

function increaseQuantity(id) {
    if (cart[id]) {
        cart[id].quantity++;
        updateCart();
    }
}

function decreaseQuantity(id) {
    if (cart[id] && cart[id].quantity > 1) {
        cart[id].quantity--;
    } else {
        delete cart[id];
    }
    updateCart();
}

function removeFromCart(id) {
    if (cart[id]) {
        delete cart[id];
        updateCart();
    }
}

// Search functionality
document.getElementById('product-search').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        const productName = item.dataset.name;
        if (productName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Checkout modal logic
document.getElementById('amount-paid').addEventListener('keyup', function() {
    const totalText = document.getElementById('modal-total').value;
    const total = parseInt(totalText.replace(/[^0-9]/g, ''));
    const amountPaid = parseInt(this.value);
    const changeElement = document.getElementById('change');

    if (amountPaid >= total) {
        const change = amountPaid - total;
        changeElement.value = formatRupiah(change);
    } else {
        changeElement.value = '';
    }
});


document.getElementById('process-payment-button').addEventListener('click', function(){
    alert('Pembayaran berhasil! (Fungsi cetak struk belum diimplementasikan)');
 
    cart = {}; 
    updateCart();

    const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
    modal.hide();
});

</script>
@endpush