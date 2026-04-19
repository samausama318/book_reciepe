<?php
// Set page title
$page_title = "Add New Recipe";

// Include header
include 'includes/header.php';
?>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <h1>Add New Recipe</h1>
            </div>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'database'): ?>
            <div class="alert alert-danger">
                There was an error saving your recipe. Please try again.
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-10 mx-auto">
                <form action="process_recipe.php" method="post" enctype="multipart/form-data" class="bg-white p-4 shadow-sm rounded">
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label for="title" class="form-label">Recipe Title*</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" placeholder="e.g., Dessert, Main Dish, etc.">
                        </div>
                        <div class="col-md-6">
                            <label for="servings" class="form-label">Servings</label>
                            <input type="number" class="form-control" id="servings" name="servings" min="1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="prep_time" class="form-label">Preparation Time</label>
                            <input type="text" class="form-control" id="prep_time" name="prep_time" placeholder="e.g., 15 minutes">
                        </div>
                        <div class="col-md-6">
                            <label for="cook_time" class="form-label">Cooking Time</label>
                            <input type="text" class="form-control" id="cook_time" name="cook_time" placeholder="e.g., 30 minutes">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ingredients" class="form-label">Ingredients* (one per line)</label>
                        <textarea class="form-control" id="ingredients" name="ingredients" rows="6" required></textarea>
                        <small class="text-muted">Example: 1 cup flour</small>
                    </div>

                    <div class="mb-3">
                        <label for="instructions" class="form-label">Instructions* (one step per line)</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="8" required></textarea>
                        <small class="text-muted">Example: Preheat oven to 350°F.</small>
                    </div>

                    <div class="mb-3">
                        <label for="recipe_image" class="form-label">Recipe Image</label>
                        <input type="file" class="form-control" id="recipe_image" name="recipe_image" accept="image/*">
                        <small class="text-muted">Max file size: 2MB. Accepted formats: JPG, PNG, GIF</small>
                    </div>

                    <div class="mb-3">
                        <label for="image_url" class="form-label">OR Image URL (optional)</label>
                        <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://...">
                        <small class="text-muted">Either upload an image or provide an URL, not both</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-success">Add Recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
// Include footer
include 'includes/footer.php';
?>