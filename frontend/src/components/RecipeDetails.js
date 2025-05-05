import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useParams, useNavigate } from 'react-router-dom';

function RecipeDetails({ token }) {
  const { id } = useParams();
  const [recipe, setRecipe] = useState(null);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    const fetchRecipe = async () => {
      try {
        const response = await axios.get(`http://localhost:5000/api/recipes/${id}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        setRecipe(response.data);
      } catch (err) {
        if (err.response?.status === 404) {
          setError('Recipe not found');
        } else if (err.response?.status === 401) {
          setError('Unauthorized. Please log in again.');
          localStorage.removeItem('token');
          navigate('/login');
        } else {
          setError('Failed to load recipe. Please try again later.');
        }
        console.error('Error fetching recipe:', err);
      }
    };
    fetchRecipe();
  }, [id, token, navigate]);

  if (error) return <p className="text-red-500 text-center">{error}</p>;
  if (!recipe) return <p className="text-center">Loading...</p>;

  return (
    <div className="p-6">
      <button
        onClick={() => navigate('/recipes')}
        className="bg-gray-500 text-white p-2 rounded-lg mb-6"
      >
        Back to Recipes
      </button>
      <h1 className="text-3xl font-bold mb-6 text-gray-800">{recipe.name}</h1>
      <img src={recipe.image} alt={recipe.name} className="w-full h-64 object-cover rounded-lg mb-6" />
      <h2 className="text-2xl font-bold mb-4">Ingredients</h2>
      <ul className="list-disc list-inside mb-6">
        {recipe.ingredients.map((ingredient, index) => (
          <li key={index}>{ingredient}</li>
        ))}
      </ul>
      <h2 className="text-2xl font-bold mb-4">Steps</h2>
      <ol className="list-decimal list-inside">
        {recipe.steps.map((step, index) => (
          <li key={index}>{step}</li>
        ))}
      </ol>
    </div>
  );
}

export default RecipeDetails;