@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Products</h1>
        <div id="product-list"></div>
    </div>

    <script>
        fetchProducts();

        function fetchProducts() {
            fetch('http://127.0.0.1:8000/api/products')
                .then(response => response.json())
                .then(data => {
                    const productList = document.getElementById('product-list');
                    productList.innerHTML = '';

                    data.products.forEach(product => {
                        let names = product.categories.map(category => category.name);
                        let string = names.join(', ');


                        const productCard = document.createElement('div');
                        productCard.className = 'card';
                        productCard.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">Price: ${product.price}</p>
                              <p class="card-text">Categories: ${string}</p>
                            </div>
                        `;
                        productList.appendChild(productCard);
                    });
                })
                .catch(error => console.error(error));
        }
    </script>
@endsection
