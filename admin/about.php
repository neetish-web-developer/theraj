<?php
/*************************************************
 * Admin About Page Management
 * Loaded inside dashboard.php
 *************************************************/

// ---------- FETCH CURRENT IMAGE (FOR DISPLAY) ----------
$currentImage = '';

$res = $conn->query(
    "SELECT image_path FROM site_pages WHERE page_name='about' LIMIT 1"
);

if ($res && $res->num_rows === 1) {
    $row = $res->fetch_assoc();
    $currentImage = $row['image_path'];
}

// ---------- UPDATE / INSERT IMAGE ----------
if (isset($_POST['update_about_image'])) {

    if (!empty($_FILES['about_image']['tmp_name'])) {

        // 🔹 Fetch OLD image JUST BEFORE update
        $oldImage = '';
        $check = $conn->query(
            "SELECT image_path FROM site_pages WHERE page_name='about' LIMIT 1"
        );

        if ($check && $check->num_rows === 1) {
            $row = $check->fetch_assoc();
            $oldImage = $row['image_path'];
        }

        // 🔹 Upload new image
        $fileName = time() . "_" . basename($_FILES['about_image']['name']);
        move_uploaded_file(
            $_FILES['about_image']['tmp_name'],
            "../uploads/" . $fileName
        );

        $newPath = "uploads/" . $fileName;

        // 🔹 Update or Insert DB record
        if ($check && $check->num_rows === 1) {
            $stmt = $conn->prepare(
                "UPDATE site_pages SET image_path=? WHERE page_name='about'"
            );
            $stmt->bind_param("s", $newPath);
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO site_pages (page_name, image_path)
                 VALUES ('about', ?)"
            );
            $stmt->bind_param("s", $newPath);
        }

        $stmt->execute();

        // 🔹 Delete OLD image safely
        if ($oldImage && file_exists("../" . $oldImage)) {
            @unlink("../" . $oldImage);
        }

        echo "<script>
            alert('About image updated successfully');
            location.href='dashboard.php?page=about';
        </script>";
        exit;
    }
}
?>

<!-- ================= PAGE CONTENT ================= -->

<div class="mb-4">
    <h4 class="fw-bold">ℹ️ About Page Management</h4>
    <p class="text-muted mb-0">
        Update the About page image shown on website
    </p>
</div>

<div class="card">
    <div class="card-header fw-bold">Update About Page Image</div>
    <div class="card-body">

        <?php if ($currentImage): ?>
            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="../<?= htmlspecialchars($currentImage) ?>"
                     style="max-width:220px;
                            border-radius:8px;
                            border:1px solid #ddd;">
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No image set yet. Upload one now.
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Upload New Image</label>
                <input type="file"
                       name="about_image"
                       class="form-control"
                       accept="image/*"
                       required>
            </div>

            <button name="update_about_image"
                    class="btn btn-success">
                Update Image
            </button>
        </form>

    </div>
</div>
