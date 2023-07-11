@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Category</h1>
        <div id="category-list"></div>
    </div>

    <script>
        fetchCategorys();

        function fetchCategorys() {
            fetch('http://127.0.0.1:8000/api/categories')
                .then(response => response.json())
                .then(data => {
                    const categoryList = document.getElementById('category-list');
                    categoryList.innerHTML = '';
                    console.log(data.categories)

                    data.categories.forEach(category => {
                        const categoryCard = document.createElement('div');
                        categoryCard.className = 'card';
                        categoryCard.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${category.name}</h5>
                            </div>
                        `;
                        categoryList.appendChild(categoryCard);
                    });
                })
                .catch(error => console.error(error));
        }
    </script>
@endsection
