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

// Page title
$page_title = "Recipe: " . $recipe['title'];

// Include header
include 'includes/header.php';
?>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col">
                <a href="index.php" class="btn btn-outline-secondary">&laquo; Back to Recipes</a>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'added'): ?>
            <div class="alert alert-success">
                Recipe successfully added!
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div class="alert alert-success">
                Recipe successfully updated!
            </div>
        <?php endif; ?>

        <div class="recipe-details bg-white p-4 shadow-sm rounded">
            <div class="row">
                <div class="col-md-8">
                    <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>

                    <div class="recipe-meta mb-3">
                        <?php if (!empty($recipe['category'])): ?>
                            <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($recipe['category']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($recipe['prep_time'])): ?>
                            <span class="me-3"><strong>Prep time:</strong> <?php echo htmlspecialchars($recipe['prep_time']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($recipe['cook_time'])): ?>
                            <span class="me-3"><strong>Cook time:</strong> <?php echo htmlspecialchars($recipe['cook_time']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($recipe['servings'])): ?>
                            <span><strong>Servings:</strong> <?php echo htmlspecialchars($recipe['servings']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary">Edit Recipe</a>
                    <a href="delete_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</a>
                </div>
            </div>

            <?php if (!empty($recipe['image'])): ?>
                <div class="row mt-4">
                    <div class="col-md-8 mx-auto">
                        <div class="text-center">
                            <img src="<?php echo htmlspecialchars($recipe['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mt-4">
                <div class="col-md-5">
                    <h3>Ingredients</h3>
                    <ul class="ingredients-list">
                        <?php
                        // Split ingredients by new line and create list items
                        $ingredients = explode("\n", $recipe['ingredients']);
                        foreach ($ingredients as $ingredient) {
                            if (trim($ingredient) !== '') {
                                echo '<li>' . htmlspecialchars(trim($ingredient)) . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-7">
                    <h3>Instructions</h3>
                    <ol class="instructions-list">
                        <?php
                        // Split instructions by new line and create list items
                        $instructions = explode("\n", $recipe['instructions']);
                        foreach ($instructions as $instruction) {
                            if (trim($instruction) !== '') {
                                echo '<li>' . htmlspecialchars(trim($instruction)) . '</li>';
                            }
                        }
                        ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

<?php
// Include footer
include 'includes/footer.php';

// Close database connection
mysqli_close($conn);
?>