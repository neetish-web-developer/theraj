<?php
/*************************************************
 * Admin Home Content
 * Loaded inside dashboard.php
 *************************************************/

require_once 'config.php';

// ================= ADD PRODUCT =================
if (isset($_POST['add_product'])) {

    $code  = trim($_POST['product_code']);
    $name  = trim($_POST['name']);
    $price = $_POST['price'];
    $desc  = trim($_POST['description']);

    $stmt = $conn->prepare(
        "INSERT INTO products (product_code, name, price, description)
         VALUES (?,?,?,?)"
    );
    $stmt->bind_param("ssds", $code, $name, $price, $desc);
    $stmt->execute();

    $product_id = $stmt->insert_id;

    // Upload multiple images
    foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
        if ($tmp) {
            $fileName = time() . "_" . rand(1000,9999) . "_" . basename($_FILES['images']['name'][$i]);
            move_uploaded_file($tmp, "../uploads/" . $fileName);

            $path = "uploads/" . $fileName;
            $sort = $i;

            $stmtImg = $conn->prepare(
                "INSERT INTO product_images (product_id, image_path, sort_order)
                 VALUES (?,?,?)"
            );
            $stmtImg->bind_param("isi", $product_id, $path, $sort);
            $stmtImg->execute();
        }
    }

    echo "<script>location.href='dashboard.php?page=home';</script>";
    exit;
}

// ================= DELETE IMAGE =================
if (isset($_GET['delete_image'])) {
    $imgId = (int)$_GET['delete_image'];

    $img = $conn->query(
        "SELECT image_path FROM product_images WHERE id=$imgId"
    )->fetch_assoc();

    if ($img) {
        @unlink("../" . $img['image_path']);
        $conn->query("DELETE FROM product_images WHERE id=$imgId");
    }

    echo "<script>location.href='dashboard.php?page=home';</script>";
    exit;
}

// ================= DELETE PRODUCT =================
if (isset($_GET['delete_product'])) {
    $pid = (int)$_GET['delete_product'];
    $conn->query("DELETE FROM products WHERE id=$pid");

    echo "<script>location.href='dashboard.php?page=home';</script>";
    exit;
}

// ================= TOGGLE STATUS =================
if (isset($_GET['toggle'])) {
    $pid = (int)$_GET['toggle'];
    $conn->query(
        "UPDATE products
         SET status = IF(status='active','inactive','active')
         WHERE id=$pid"
    );

    echo "<script>location.href='dashboard.php?page=home';</script>";
    exit;
}

// ================= FETCH PRODUCTS =================
$products = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);
?>

<!-- ================= PAGE CONTENT ================= -->

<div class="mb-4">
    <h4 class="fw-bold">📦 Product Management</h4>
    <p class="text-muted mb-0">Add, manage and control product visibility</p>
</div>

<!-- ================= ADD PRODUCT FORM ================= -->
<div class="card mb-4">
    <div class="card-header fw-bold">Add New Product</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">

                <div class="col-md-3">
                    <input type="text" name="product_code"
                           class="form-control"
                           placeholder="Product Code" required>
                </div>

                <div class="col-md-3">
                    <input type="text" name="name"
                           class="form-control"
                           placeholder="Product Name" required>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01"
                           name="price"
                           class="form-control"
                           placeholder="Price" required>
                </div>

                <div class="col-md-4">
                    <input type="file"
                           name="images[]"
                           class="form-control"
                           multiple required>
                </div>

                <div class="col-md-12">
                    <textarea name="description"
                              class="form-control"
                              placeholder="Description (optional)"></textarea>
                </div>

            </div>

            <button name="add_product"
                    class="btn btn-success mt-3">
                Add Product
            </button>
        </form>
    </div>
</div>

<!-- ================= PRODUCT LIST ================= -->
<div class="card">
    <div class="card-header fw-bold">Products</div>
    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Images</th>
                <th>Name</th>
                <th>Price</th>
                <th>Status</th>
                <th width="190">Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php while ($p = $products->fetch_assoc()): ?>
            <tr>

                <td><?= $p['id'] ?></td>

                <td>
                <?php
                $imgs = $conn->query(
                    "SELECT * FROM product_images
                     WHERE product_id=".$p['id']."
                     ORDER BY sort_order"
                );
                while ($i = $imgs->fetch_assoc()):
                ?>
                    <div class="d-inline-block position-relative me-1 mb-1">
                        <img src="../<?= $i['image_path'] ?>"
                             style="width:70px;height:70px;object-fit:cover;border-radius:6px;">
                        <a href="dashboard.php?page=home&delete_image=<?= $i['id'] ?>"
                           onclick="return confirm('Delete image?')"
                           style="position:absolute;top:-6px;right:-6px;
                                  background:#dc3545;color:#fff;
                                  font-size:12px;padding:2px 6px;
                                  border-radius:50%;text-decoration:none;">
                            ×
                        </a>
                    </div>
                <?php endwhile; ?>
                </td>

                <td><?= htmlspecialchars($p['name']) ?></td>

                <td>₹<?= number_format($p['price'],2) ?></td>

                <td>
                    <span class="badge <?= $p['status']=='active'?'bg-success':'bg-secondary' ?>">
                        <?= ucfirst($p['status']) ?>
                    </span>
                </td>

                <td>
                    <a href="dashboard.php?page=home&toggle=<?= $p['id'] ?>"
                       class="btn btn-warning btn-sm mb-1">Toggle</a>

                    <a href="dashboard.php?page=home&delete_product=<?= $p['id'] ?>"
                       class="btn btn-danger btn-sm mb-1"
                       onclick="return confirm('Delete product permanently?')">
                       Delete
                    </a>
                </td>

            </tr>
            <?php endwhile; ?>

            </tbody>
        </table>

    </div>
</div>
