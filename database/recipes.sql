-- Create database if not exists
CREATE DATABASE IF NOT EXISTS recipe_book;

-- Use the database
USE recipe_book;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
                                     id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create recipes table with user_id foreign key
CREATE TABLE IF NOT EXISTS recipes (
                                       id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    prep_time VARCHAR(50),
    cook_time VARCHAR(50),
    servings INT(11),
    category VARCHAR(100),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample user (password is 'password123')
INSERT INTO users (username, email, password) VALUES
    ('demo_user', 'demo@example.com', '$2y$10$JXgNAZUvMaIjbE6HMDfZj.lmavw.P2ElRmYLh4GjGKhSST0A7tZGC');

-- Insert some sample recipes
INSERT INTO recipes (user_id, title, ingredients, instructions, prep_time, cook_time, servings, category) VALUES
                                                                                                              (1, 'Classic Spaghetti Bolognese',
                                                                                                               '1 lb ground beef\n2 tbsp olive oil\n1 onion, diced\n2 cloves garlic, minced\n1 carrot, diced\n1 celery stalk, diced\n1 can (28 oz) crushed tomatoes\n2 tbsp tomato paste\n1 tsp dried oregano\n1 tsp dried basil\n1/2 cup red wine (optional)\nSalt and pepper to taste\n1 lb spaghetti',
                                                                                                               '1. Heat olive oil in a large pot over medium heat.\n2. Add onion, garlic, carrot, and celery. Cook until softened, about 5 minutes.\n3. Add ground beef and cook until browned, breaking it up as it cooks.\n4. Pour in wine (if using) and let it reduce for 2-3 minutes.\n5. Add crushed tomatoes, tomato paste, oregano, and basil. Season with salt and pepper.\n6. Simmer on low heat for at least 30 minutes (or up to 2 hours for deeper flavor).\n7. Meanwhile, cook spaghetti according to package directions.\n8. Serve sauce over pasta with grated parmesan cheese.',
                                                                                                               '15 minutes', '45 minutes', 4, 'Main Dish'),

                                                                                                              (1, 'Chocolate Chip Cookies',
                                                                                                               '1 cup butter, softened\n1 cup white sugar\n1 cup packed brown sugar\n2 eggs\n2 tsp vanilla extract\n3 cups all-purpose flour\n1 tsp baking soda\n2 tsp hot water\n1/2 tsp salt\n2 cups semisweet chocolate chips',
                                                                                                               '1. Preheat oven to 350°F (175°C).\n2. Cream together butter and sugars until smooth.\n3. Beat in eggs one at a time, then stir in vanilla.\n4. Dissolve baking soda in hot water, add to batter along with salt.\n5. Stir in flour and chocolate chips.\n6. Drop by large spoonfuls onto ungreased baking sheets.\n7. Bake for about 10 minutes, or until edges are nicely browned.',
                                                                                                               '20 minutes', '10 minutes', 24, 'Dessert'),

                                                                                                              (1, 'Simple Greek Salad',
                                                                                                               '4 large tomatoes, cut into chunks\n1 cucumber, sliced\n1 red onion, thinly sliced\n1 green bell pepper, chopped\n1 cup kalamata olives\n8 oz feta cheese, cubed\n2 tbsp olive oil\n1 tbsp red wine vinegar\n1 tsp dried oregano\nSalt and pepper to taste',
                                                                                                               '1. Combine tomatoes, cucumber, onion, bell pepper, and olives in a large bowl.\n2. Add feta cheese cubes.\n3. In a small bowl, whisk together olive oil, vinegar, and oregano.\n4. Pour dressing over salad and toss gently.\n5. Season with salt and pepper.\n6. Refrigerate for 30 minutes before serving for best flavor.',
                                                                                                               '15 minutes', '0 minutes', 4, 'Salad');

-- Create uploads directory to store recipe images
-- Note: This SQL comment is for reference only, you'll need to create this directory manually