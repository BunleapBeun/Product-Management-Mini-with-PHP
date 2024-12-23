(() => {
    const frmProduct = document.getElementById("frmProduct");
    const productName = document.getElementById("name");
    const productBrand = document.getElementById("brand");
    const productPrice = document.getElementById("price");
    const productQuantity = document.getElementById("quantity");
    const productPhoto = document.getElementById("photo");
    const tblProducts = document.getElementById("tbl-products");
    const totalPrice = document.getElementById("total_price");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-btn");
    let productID = 0;

    const loadData = (searchQuery = "") => {
        const url = searchQuery ? `api/product/search.php?search=${searchQuery}` : "api/product/index.php";
        axios.get(url).then((res) => {
            console.log("Res = ", res);
            tblProducts.innerHTML = "";
            res.data.products.forEach((product) => {
                let stockStatus = "";

                if (product.quantity === 0) {
                    stockStatus = "Out of Stock";
                } else if (product.quantity <= 10) {
                    stockStatus = "Low Stock";
                } else {
                    stockStatus = "In Stock";
                }

                const productImage = product.photo ? `storage/img/${product.photo}` : "asset/img/default_img.jpg";

                tblProducts.innerHTML += `
                    <tr class="align-middle">
                        <td>${product.id}</td>
                        <td>
                            <img src="${productImage}" alt="product.jpg" style="height: 130px; width: 100%; object-fit: cover;">
                        </td>
                        <td>${product.name}</td>
                        <td>${product.brand}</td>
                        <td>$${product.price}</td>
                        <td>${product.quantity}</td>
                        <td>${stockStatus}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a role="button" class="btn btn-primary w-100 btn-edit" data-product='${JSON.stringify(product)}'>Edit</a>
                                <a role="button" class="btn btn-danger w-100 btn-delete" data-id="${product.id}">Delete</a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            totalPrice.innerHTML = res.data.total_price.toLocaleString();

            document.querySelectorAll(".btn-delete").forEach((btn) => {
                btn.onclick = (e) => {
                    const selectedID = btn.getAttribute("data-id");
                    axios.get(`api/product/destroy.php?id=${selectedID}`).then(() => {
                        loadData(searchQuery);
                    });
                };
            });

            document.querySelectorAll(".btn-edit").forEach((btn) => {
                btn.onclick = (e) => {
                    const productJSON = btn.getAttribute("data-product");
                    const productOBJ = JSON.parse(productJSON);
                    productID = productOBJ.id;
                    productName.value = productOBJ.name;
                    productBrand.value = productOBJ.brand;
                    productPrice.value = productOBJ.price;
                    productQuantity.value = productOBJ.quantity;
                };
            });
        });
    };

    loadData();

    frmProduct.onsubmit = (e) => {
        e.preventDefault();
        let frmData = new FormData();
        frmData.append("name", productName.value);
        frmData.append("brand", productBrand.value);
        frmData.append("price", productPrice.value);
        frmData.append("quantity", productQuantity.value);

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const photo = productPhoto.files[0];

        // Check if photo is uploaded and its type is allowed
        if (photo) {
            if (!allowedTypes.includes(photo.type)) {
                const fileErrorModal = new bootstrap.Modal(document.getElementById('fileErrorModal'));
                fileErrorModal.show();
                return;  // Stop form submission
            }
            frmData.append("photo", photo);
        }

        if (productID > 0) {
            frmData.append("id", productID);
        }

        if (productID == 0) {
            axios.post("api/product/store.php", frmData).then((res) => {
                if (!res.data.result) {
                    alert(res.data.message);
                    return;
                }
                productName.value = productBrand.value = productPrice.value = productQuantity.value = productPhoto.value = "";
                loadData();
            });
        } else {
            axios.post("api/product/update.php", frmData).then((res) => {
                if (!res.data.result) {
                    alert(res.data.message);
                    return;
                }
                productID = 0;
                productName.value = productBrand.value = productPrice.value = productQuantity.value = productPhoto.value = "";
                loadData();
            });
        }
    };

    // Search button click event
    searchButton.addEventListener("click", () => {
        const searchQuery = searchInput.value.trim();
        loadData(searchQuery);  // Reload data with the search query
    });

})();
