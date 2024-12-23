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
    const productToast = new bootstrap.Toast(document.getElementById('productToast'));
    let productID = 0;

    // Function to show toast notification (all with 'Notification' as the title)
    const showToast = (message) => {
        const toastTitle = document.getElementById('toast-title');
        const toastBody = document.getElementById('toast-body');
        const toastTime = document.getElementById('toast-time');

        // Set the title as 'Notification' for all toasts
        toastTitle.textContent = 'Notification';
        toastBody.textContent = message;
        toastTime.textContent = new Date().toLocaleTimeString('en-US');

        productToast.show();
    };

    const loadData = (searchQuery = "") => {
        const url = searchQuery ? `api/product/search.php?search=${searchQuery}` : "api/product/index.php";
        axios.get(url).then((res) => {
            console.log("Res = ", res);
            tblProducts.innerHTML = "";
            let total = 0;  // Initialize total price

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

                // Calculate total price for this product
                total += product.price * product.quantity;

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

            // Update the total price in the UI
            totalPrice.innerHTML = total.toLocaleString();

            // Handle delete button click
            document.querySelectorAll(".btn-delete").forEach((btn) => {
                btn.onclick = (e) => {
                    const selectedID = btn.getAttribute("data-id");
                    axios.get(`api/product/destroy.php?id=${selectedID}`).then(() => {
                        loadData(searchQuery);  // Reload data after deletion
                        showToast('Product deleted successfully!');
                    });
                };
            });

            // Handle edit button click
            document.querySelectorAll(".btn-edit").forEach((btn) => {
                btn.onclick = (e) => {
                    const productJSON = btn.getAttribute("data-product");
                    const productOBJ = JSON.parse(productJSON);
                    productID = productOBJ.id;
                    productName.value = productOBJ.name;
                    productBrand.value = productOBJ.brand;
                    productPrice.value = productOBJ.price;
                    productQuantity.value = productOBJ.quantity;
                    showToast('Product loaded for editing');
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
                showToast('Product saved successfully!');
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
                showToast('Product updated successfully!');
            });
        }
    };

    // Search button click event
    searchButton.addEventListener("click", () => {
        const searchQuery = searchInput.value.trim();
        loadData(searchQuery);  // Reload data with the search query
    });

})();
