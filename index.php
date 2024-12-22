<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product CRUD</title>
    <link rel="stylesheet" href="./asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="./asset/css/main.css">
</head>

<body>
    <div class="container pt-5">
        <h1 class="text-center pb-3">Product Management</h1>
        <div class="row g-5">
            <div class="col-3 card p-4">
                <form id="frmProduct" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" id="brand" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" id="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Product Image</label>
                        <input type="file" id="photo" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Save Product</button>
                </form>
            </div>
            <div class="col-9">
                <div class="card p-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Stock Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-products"></tbody>
                    </table>
                    <p class="m-0 fs-5 fw-medium">Total Price: $<span id="total_price"></span></p>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fileErrorModal" tabindex="-1" aria-labelledby="fileErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fileErrorModalLabel">Invalid File Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        The uploaded file type is not supported. Please upload a JPEG, PNG, or WebP image.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./asset/js/bootstrap.bundle.min.js"></script>
    <script src="./asset/js/axios.min.js"></script>
    <script src="./asset/js/script.js"></script>
</body>

</html>