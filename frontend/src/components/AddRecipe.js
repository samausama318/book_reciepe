import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate, useParams } from 'react-router-dom';

function AddRecipe({ token, isEdit }) {
  const [name, setName] = useState('');
  const [image, setImage] = useState('');
  const [ingredients, setIngredients] = useState(['']);
  const [steps, setSteps] = useState(['']);
  const navigate = useNavigate();
  const { id } = useParams();

  useEffect(() => {
    if (isEdit && id) {
      const fetchRecipe = async () => {
        try {
          const response = await axios.get(`http://localhost:5000/api/recipes/${id}`, {
            headers: { Authorization: `Bearer ${token}` },
          });
          setName(response.data.name);
          setImage(response.data.image);
          setIngredients(response.data.ingredients);
          setSteps(response.data.steps);
        } catch (err) {
          console.error('Error fetching recipe:', err);
        }
      };
      fetchRecipe();
    }
  }, [isEdit, id, token]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    const recipeData = { name, image, ingredients, steps };
    try {
      if (isEdit) {
        await axios.put(`http://localhost:5000/api/recipes/${id}`, recipeData, {
          headers: { Authorization: `Bearer ${token}` },
        });
      } else {
        await axios.post('http://localhost:5000/api/recipes', recipeData, {
          headers: { Authorization: `Bearer ${token}` },
        });
      }
      navigate('/recipes');
    } catch (err) {
      console.error('Error saving recipe:', err);
    }
  };

  const addField = (setField, field) => {
    setField([...field, '']);
  };

  const updateField = (index, value, setField, field) => {
    const updated = [...field];
    updated[index] = value;
    setField(updated);
  };

  return (
    <div className="container mx-auto p-6">
      <h1 className="text-3xl font-bold text-gray-800 mb-6">{isEdit ? 'Edit Recipe' : 'Add Recipe'}</h1>
      <div className="bg-card p-6 rounded-2xl shadow-lg max-w-lg mx-auto">
        <div>
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="Recipe Name"
            className="w-full p-3 mb-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
          />
          <input
            type="text"
            value={image}
            onChange={(e) => setImage(e.target.value)}
            placeholder="Image URL"
            className="w-full p-3 mb-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
          />
          <h3 className="text-lg font-semibold mb-2">Ingredients</h3>
          {ingredients.map((ingredient, index) => (
            <input
              key={index}
              type="text"
              value={ingredient}
              onChange={(e) => updateField(index, e.target.value, setIngredients, ingredients)}
              placeholder={`Ingredient ${index + 1}`}
              className="w-full p-3 mb-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
            />
          ))}
          <button
            onClick={() => addField(setIngredients, ingredients)}
            className="bg-primary text-gray-800 px-3 py-1 rounded-lg hover:bg-pink-200 transition mb-4"
          >
            Add Ingredient
          </button>
          <h3 className="text-lg font-semibold mb-2">Steps</h3>
          {steps.map((step, index) => (
            <input
              key={index}
              type="text"
              value={step}
              onChange={(e) => updateField(index, e.target.value, setSteps, steps)}
              placeholder={`Step ${index + 1}`}
              className="w-full p-3 mb-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
            />
          ))}
          <button
            onClick={() => addField(setSteps, steps)}
            className="bg-primary text-gray-800 px-3 py-1 rounded-lg hover:bg-pink-200 transition mb-4"
          >
            Add Step
          </button>
          <button
            onClick={handleSubmit}
            className="w-full bg-secondary text-white p-3 rounded-lg hover:bg-yellow-500 transition"
          >
            {isEdit ? 'Update Recipe' : 'Add Recipe'}
          </button>
        </div>
      </div>
    </div>
  );
}

export default AddRecipe;