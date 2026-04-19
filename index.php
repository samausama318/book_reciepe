<?php
// Include database connection
require_once 'db_connect.php';

// Page title
$page_title = "Recipe Book - Home";

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Query to count recipes by category
    $category_query = "SELECT category, COUNT(*) as count FROM recipes GROUP BY category ORDER BY count DESC";
    $category_result = mysqli_query($conn, $category_query);
    $categories = [];

    while ($row = mysqli_fetch_assoc($category_result)) {
        $categories[$row['category']] = $row['count'];
    }

    // Query to get featured/popular recipes (most recently added for now)
    $featured_query = "SELECT id, title, category, prep_time, cook_time, image 
                      FROM recipes 
                      ORDER BY created_at DESC 
                      LIMIT 3";
    $featured_result = mysqli_query($conn, $featured_query);

    // Query to get all recipes with pagination
    $items_per_page = 9;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Get total recipe count
    $count_query = "SELECT COUNT(*) as total FROM recipes";
    $count_result = mysqli_query($conn, $count_query);
    $total_recipes = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_recipes / $items_per_page);

    // Apply filters if set
    $where_clause = "";
    $params = [];

    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category = mysqli_real_escape_string($conn, $_GET['category']);
        $where_clause = " WHERE category = '$category'";
    }

    // Main query with filters and pagination
    $query = "SELECT id, title, category, prep_time, cook_time, image 
              FROM recipes" . $where_clause . "
              ORDER BY created_at DESC 
              LIMIT $offset, $items_per_page";
    $result = mysqli_query($conn, $query);

    // Commit transaction
    mysqli_commit($conn);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    $error_message = "Database error: " . $e->getMessage();
}

// Include header
include 'includes/header.php';
?>

    <div class="container mt-4">
        <!-- Hero Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body p-5 text-center">
                        <h1 class="display-4">My Recipe Collection</h1>
                        <p class="lead">Discover, create, and share your favorite recipes</p>
                        <div class="mt-4">
                            <a href="add_recipe.php" class="btn btn-success btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Add New Recipe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Message Section -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['status'] == 'added'): ?>
                    <i class="fas fa-check-circle me-2"></i>Recipe added successfully!
                <?php elseif ($_GET['status'] == 'updated'): ?>
                    <i class="fas fa-check-circle me-2"></i>Recipe updated successfully!
                <?php elseif ($_GET['status'] == 'deleted'): ?>
                    <i class="fas fa-check-circle me-2"></i>Recipe deleted successfully!
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Featured Recipes Section -->
        <?php if (mysqli_num_rows($featured_result) > 0): ?>
            <section class="mb-5">
                <h2 class="section-title"><i class="fas fa-star me-2"></i>Featured Recipes</h2>
                <div class="row">
                    <?php while ($recipe = mysqli_fetch_assoc($featured_result)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 featured-card">
                                <?php if (!empty($recipe['image'])): ?>
                                    <div class="position-relative">
                                        <img src="<?php echo htmlspecialchars($recipe['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($recipe['title']); ?>" style="height: 200px; object-fit: cover;">
                                        <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                                    <p class="card-text">
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($recipe['category']); ?></span>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>Prep: <?php echo htmlspecialchars($recipe['prep_time']); ?> |
                                            <i class="fas fa-fire me-1"></i>Cook: <?php echo htmlspecialchars($recipe['cook_time']); ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Recipe
                                    </a>
                                    <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this recipe?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Category Filter Section -->
        <section class="mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Browse by Category</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="index.php" class="btn <?php echo !isset($_GET['category']) ? 'btn-success' : 'btn-outline-success'; ?>">
                            All
                        </a>
                        <?php foreach ($categories as $cat => $count): ?>
                            <a href="index.php?category=<?php echo urlencode($cat); ?>"
                               class="btn <?php echo (isset($_GET['category']) && $_GET['category'] == $cat) ? 'btn-success' : 'btn-outline-success'; ?>">
                                <?php echo htmlspecialchars($cat); ?>
                                <span class="badge bg-light text-dark ms-1"><?php echo $count; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recipe List Section -->
        <section>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0"><i class="fas fa-book me-2"></i>Recipe Collection</h2>
                <a href="add_recipe.php" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i>Add New Recipe
                </a>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="row">
                    <?php while ($recipe = mysqli_fetch_assoc($result)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if (!empty($recipe['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($recipe['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($recipe['title']); ?>" style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 180px;">
                                        <i class="fas fa-utensils fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                                    <p class="card-text">
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($recipe['category']); ?></span>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>Prep: <?php echo htmlspecialchars($recipe['prep_time']); ?> |
                                            <i class="fas fa-fire me-1"></i>Cook: <?php echo htmlspecialchars($recipe['cook_time']); ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this recipe?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Recipe pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?><?php echo isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?><?php echo isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : ''; ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No recipes found.
                    <a href="add_recipe.php" class="alert-link">Add your first recipe</a>.
                </div>
            <?php endif; ?>
        </section>
    </div>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
mysqli_close($conn);
?>