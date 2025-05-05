const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const jwt = require('jsonwebtoken');
const bcrypt = require('bcryptjs');
require('dotenv').config(); // أضفنا مكتبة dotenv علشان نحمّل ملف .env

const app = express();
app.use(cors());
app.use(express.json());

// MongoDB connection
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://mongo:27017/recipebook'; // الـ default لو ملف .env مش موجود
mongoose.connect(MONGODB_URI, { useNewUrlParser: true, useUnifiedTopology: true })
  .then(() => console.log('Connected to MongoDB'))
  .catch(err => console.error('MongoDB connection error:', err));

// User Schema
const userSchema = new mongoose.Schema({
  email: { type: String, required: true, unique: true },
  password: { type: String, required: true }
});
const User = mongoose.model('User', userSchema);

// Recipe Schema
const recipeSchema = new mongoose.Schema({
  userId: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
  name: { type: String, required: true },
  image: { type: String, required: true },
  ingredients: { type: [String], required: true },
  steps: { type: [String], required: true }
});
const Recipe = mongoose.model('Recipe', recipeSchema);

// Middleware to verify JWT
const authMiddleware = (req, res, next) => {
  const token = req.header('Authorization')?.replace('Bearer ', '');
  if (!token) return res.status(401).json({ message: 'No token provided' });
  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    req.user = decoded;
    next();
  } catch (err) {
    res.status(401).json({ message: 'Invalid token' });
  }
};

// Routes
// Register
app.post('/api/register', async (req, res) => {
  const { email, password } = req.body;
  if (!email || !password) {
    return res.status(400).json({ message: 'Email and password are required' });
  }
  try {
    const hashedPassword = await bcrypt.hash(password, 10);
    const user = new User({ email, password: hashedPassword });
    await user.save();
    res.status(201).json({ message: 'User registered successfully' });
  } catch (err) {
    res.status(400).json({ message: 'Email already exists' });
  }
});

// Login
app.post('/api/login', async (req, res) => {
  const { email, password } = req.body;
  if (!email || !password) {
    return res.status(400).json({ message: 'Email and password are required' });
  }
  try {
    const user = await User.findOne({ email });
    if (!user || !(await bcrypt.compare(password, user.password))) {
      return res.status(400).json({ message: 'Invalid credentials' });
    }
    const token = jwt.sign({ userId: user._id }, process.env.JWT_SECRET, { expiresIn: '24h' }); // غيرنا المدة لـ 24 ساعة
    res.json({ token });
  } catch (err) {
    res.status(500).json({ message: 'Server error during login' });
  }
});

// Get all recipes for logged-in user
app.get('/api/recipes', authMiddleware, async (req, res) => {
  try {
    const recipes = await Recipe.find({ userId: req.user.userId });
    res.json(recipes);
  } catch (err) {
    res.status(500).json({ message: 'Server error while fetching recipes' });
  }
});

// Get single recipe by ID
app.get('/api/recipes/:id', authMiddleware, async (req, res) => {
  try {
    const recipe = await Recipe.findOne({ _id: req.params.id, userId: req.user.userId });
    if (!recipe) {
      return res.status(404).json({ message: 'Recipe not found' });
    }
    res.json(recipe);
  } catch (err) {
    res.status(500).json({ message: 'Server error while fetching recipe' });
  }
});

// Add recipe
app.post('/api/recipes', authMiddleware, async (req, res) => {
  const { name, image, ingredients, steps } = req.body;
  if (!name || !image || !ingredients || !steps) {
    return res.status(400).json({ message: 'All fields are required' });
  }
  try {
    const recipe = new Recipe({ userId: req.user.userId, name, image, ingredients, steps });
    await recipe.save();
    res.status(201).json(recipe);
  } catch (err) {
    res.status(500).json({ message: 'Server error while adding recipe' });
  }
});

// Update recipe
app.put('/api/recipes/:id', authMiddleware, async (req, res) => {
  const { name, image, ingredients, steps } = req.body;
  if (!name || !image || !ingredients || !steps) {
    return res.status(400).json({ message: 'All fields are required' });
  }
  try {
    const recipe = await Recipe.findOneAndUpdate(
      { _id: req.params.id, userId: req.user.userId },
      { name, image, ingredients, steps },
      { new: true }
    );
    if (!recipe) return res.status(404).json({ message: 'Recipe not found' });
    res.json(recipe);
  } catch (err) {
    res.status(500).json({ message: 'Server error while updating recipe' });
  }
});

// Delete recipe
app.delete('/api/recipes/:id', authMiddleware, async (req, res) => {
  try {
    const recipe = await Recipe.findOneAndDelete({ _id: req.params.id, userId: req.user.userId });
    if (!recipe) return res.status(404).json({ message: 'Recipe not found' });
    res.json({ message: 'Recipe deleted successfully' });
  } catch (err) {
    res.status(500).json({ message: 'Server error while deleting recipe' });
  }
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));