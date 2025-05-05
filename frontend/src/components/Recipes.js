import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Recipes({ token, onLogout }) {
  const [recipes, setRecipes] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchRecipes = async () => {
      try {
        const response = await axios.get('http://localhost:5000/api/recipes', {
          headers: { Authorization: `Bearer ${token}` },
        });
        setRecipes(response.data);
      } catch (err) {
        console.error('Error fetching recipes:', err);
        if (err.response?.status === 401) {
          // لو الـ token منتهي، نعمل logout ونروح لصفحة الـ login
          onLogout();
          navigate('/login');
        }
      }
    };
    fetchRecipes();
  }, [token, navigate, onLogout]);

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:5000/api/recipes/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      setRecipes(recipes.filter((recipe) => recipe._id !== id));
    } catch (err) {
      console.error('Error deleting recipe:', err);
      if (err.response?.status === 401) {
        // لو الـ token منتهي، نعمل logout ونروح لصفحة الـ login
        onLogout();
        navigate('/login');
      }
    }
  };

  return (
    <div className="container mx-auto p-6">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-4xl font-bold text-gray-800">My Recipe Book</h1>
        <div>
          <button
            onClick={() => navigate('/add-recipe')}
            className="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-yellow-500 transition mr-4"
          >
            Add Recipe
          </button>
          <button
            onClick={onLogout}
            className="bg-primary text-gray-800 px-4 py-2 rounded-lg hover:bg-pink-200 transition"
          >
            Logout
          </button>
        </div>
      </div>
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {recipes.length === 0 ? (
          <p className="text-gray-600 text-center col-span-3">No recipes yet! Add some.</p>
        ) : (
          recipes.map((recipe) => (
            <div
              key={recipe._id}
              className="bg-card p-4 rounded-2xl shadow-lg hover:shadow-xl transition"
            >
              <img
                src={recipe.image}
                alt={recipe.name}
                className="w-full h-48 object-cover rounded-lg mb-4"
              />
              <h3 className="text-xl font-semibold text-gray-800 mb-2">{recipe.name}</h3>
              <div className="flex justify-between gap-2">
                <button
                  onClick={() => navigate(`/recipe/${recipe._id}`)}
                  className="bg-primary text-gray-800 px-3 py-1 rounded-lg hover:bg-pink-200 transition"
                >
                  View
                </button>
                <button
                  onClick={() => navigate(`/edit-recipe/${recipe._id}`)}
                  className="bg-secondary text-white px-3 py-1 rounded-lg hover:bg-yellow-500 transition"
                >
                  Edit
                </button>
                <button
                  onClick={() => handleDelete(recipe._id)}
                  className="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition"
                >
                  Delete
                </button>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
}

export default Recipes;