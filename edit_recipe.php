<?php
// Include database connection
require_once 'db_connect.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get recipe ID from URL parameter
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Query to get the recipe details
$query = "SELECT * FROM recipes WHERE id = $id";
$result = mysqli_query($conn, $query);

// Check if recipe exists
if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

// Fetch recipe data
$recipe = mysqli_fetch_assoc($result);

// Set page title
$page_title = "Edit Recipe: " . $recipe['title'];

// Include header
include 'includes/header.php';
?>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <h1>Edit Recipe</h1>
            </div>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'database'): ?>
            <div class="alert alert-danger">
                There was an error updating your recipe. Please try again.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-10 mx-auto">
                <form action="process_recipe.php" method="post" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">

                    <div class="mb-3">
                        <label for="title" class="form-label">Recipe Title*</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($recipe['category']); ?>" placeholder="e.g., Dessert, Main Dish, etc.">
                        </div>
                        <div class="col-md-6">
                            <label for="servings" class="form-label">Servings</label>
                            <input type="number" class="form-control" id="servings" name="servings" value="<?php echo htmlspecialchars($recipe['servings']); ?>" min="1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prep_time" class="form-label">Preparation Time</label>
                            <input type="text" class="form-control" id="prep_time" name="prep_time" value="<?php echo htmlspecialchars($recipe['prep_time']); ?>" placeholder="e.g., 15 minutes">
                        </div>
                        <div class="col-md-6">
                            <label for="cook_time" class="form-label">Cooking Time</label>
                            <input type="text" class="form-control" id="cook_time" name="cook_time" value="<?php echo htmlspecialchars($recipe['cook_time']); ?>" placeholder="e.g., 30 minutes">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients* (one per line)</label>
                        <textarea class="form-control" id="ingredients" name="ingredients" rows="6" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
                        <small class="text-muted">Example: 1 cup flour</small>
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instructions* (one step per line)</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="8" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
                        <small class="text-muted">Example: Preheat oven to 350°F.</small>
                    </div>

                    <?php if (!empty($recipe['image'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="recipe_image" class="form-label">New Recipe Image</label>
                        <input type="file" class="form-control" id="recipe_image" name="recipe_image" accept="image/*">
                        <small class="text-muted">Max file size: 2MB. Accepted formats: JPG, PNG, GIF</small>
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label">OR Image URL (optional)</label>
                        <input type="url" class="form-control" id="image_url" name="image_url" value="<?php if (filter_var($recipe['image'], FILTER_VALIDATE_URL)) echo htmlspecialchars($recipe['image']); ?>" placeholder="https://...">
                        <small class="text-muted">Either upload an image or provide an URL, not both</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
mysqli_close($conn);
?>