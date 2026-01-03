<?php
/*************************************************
 * Admin Gallery Management
 * Loaded inside dashboard.php
 *************************************************/

// ================= ADD GALLERY ITEM =================
if (isset($_POST['add_gallery'])) {

    $title    = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);

    if (!empty($_FILES['image']['tmp_name'])) {

        $fileName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/" . $fileName
        );

        $path = "uploads/" . $fileName;

        $stmt = $conn->prepare(
            "INSERT INTO gallery_items (title, subtitle, image_path)
             VALUES (?,?,?)"
        );
        $stmt->bind_param("sss", $title, $subtitle, $path);
        $stmt->execute();
    }

    echo "<script>location.href='dashboard.php?page=gallery';</script>";
    exit;
}

// ================= DELETE GALLERY ITEM =================
if (isset($_GET['delete_gallery'])) {

    $gid = (int)$_GET['delete_gallery'];

    $res = $conn->query(
        "SELECT image_path FROM gallery_items WHERE id=$gid"
    );

    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (file_exists("../" . $row['image_path'])) {
            @unlink("../" . $row['image_path']);
        }
        $conn->query("DELETE FROM gallery_items WHERE id=$gid");
    }

    echo "<script>location.href='dashboard.php?page=gallery';</script>";
    exit;
}

// ================= TOGGLE STATUS =================
if (isset($_GET['toggle_gallery'])) {

    $gid = (int)$_GET['toggle_gallery'];
    $conn->query(
        "UPDATE gallery_items
         SET status = IF(status='active','inactive','active')
         WHERE id=$gid"
    );

    echo "<script>location.href='dashboard.php?page=gallery';</script>";
    exit;
}

// ================= FETCH GALLERY =================
$gallery = $conn->query(
    "SELECT * FROM gallery_items ORDER BY id DESC"
);
?>

<!-- ================= PAGE CONTENT ================= -->

<div class="mb-4">
    <h4 class="fw-bold">🖼 Gallery Management</h4>
    <p class="text-muted mb-0">Upload and manage gallery images</p>
</div>

<!-- ================= ADD GALLERY FORM ================= -->
<div class="card mb-4">
    <div class="card-header fw-bold">Add Gallery Image</div>
    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">

                <div class="col-md-4">
                    <input type="text"
                           name="title"
                           class="form-control"
                           placeholder="Title"
                           required>
                </div>

                <div class="col-md-4">
                    <input type="text"
                           name="subtitle"
                           class="form-control"
                           placeholder="Subtitle (optional)">
                </div>

                <div class="col-md-4">
                    <input type="file"
                           name="image"
                           class="form-control"
                           accept="image/*"
                           required>
                </div>

            </div>

            <button name="add_gallery"
                    class="btn btn-success mt-3">
                Add Image
            </button>
        </form>

    </div>
</div>

<!-- ================= GALLERY LIST ================= -->
<div class="card">
    <div class="card-header fw-bold">Gallery Items</div>
    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Status</th>
                <th width="190">Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php while ($g = $gallery->fetch_assoc()): ?>
            <tr>

                <td><?= $g['id'] ?></td>

                <td>
                    <img src="../<?= htmlspecialchars($g['image_path']) ?>"
                         style="width:90px;height:70px;
                                object-fit:cover;border-radius:6px;">
                </td>

                <td><?= htmlspecialchars($g['title']) ?></td>

                <td><?= htmlspecialchars($g['subtitle']) ?></td>

                <td>
                    <span class="badge <?= $g['status']=='active'?'bg-success':'bg-secondary' ?>">
                        <?= ucfirst($g['status']) ?>
                    </span>
                </td>

                <td>
                    <a href="dashboard.php?page=gallery&toggle_gallery=<?= $g['id'] ?>"
                       class="btn btn-warning btn-sm mb-1">
                       Toggle
                    </a>

                    <a href="dashboard.php?page=gallery&delete_gallery=<?= $g['id'] ?>"
                       class="btn btn-danger btn-sm mb-1"
                       onclick="return confirm('Delete this image?')">
                       Delete
                    </a>
                </td>

            </tr>
            <?php endwhile; ?>

            </tbody>
        </table>

    </div>
</div>
