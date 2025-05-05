import React, { useState } from 'react';
import { Route, Routes, Navigate } from 'react-router-dom';
import Login from './components/Login';
import Recipes from './components/Recipes';
import AddRecipe from './components/AddRecipe';
import RecipeDetails from './components/RecipeDetails'; // أضفنا الـ import بتاع RecipeDetails
import './index.css';

function App() {
  const [token, setToken] = useState(localStorage.getItem('token') || '');

  const handleLogin = (newToken) => {
    setToken(newToken);
    localStorage.setItem('token', newToken);
  };

  const handleLogout = () => {
    setToken('');
    localStorage.removeItem('token');
  };

  return (
    <div className="overlay">
      <Routes>
        <Route
          path="/login"
          element={token ? <Navigate to="/recipes" /> : <Login onLogin={handleLogin} />}
        />
        <Route
          path="/recipes"
          element={token ? <Recipes token={token} onLogout={handleLogout} /> : <Navigate to="/login" />}
        />
        <Route
          path="/add-recipe"
          element={token ? <AddRecipe token={token} /> : <Navigate to="/login" />}
        />
        <Route
          path="/edit-recipe/:id"
          element={token ? <AddRecipe token={token} isEdit /> : <Navigate to="/login" />}
        />
        <Route
          path="/recipe/:id"
          element={token ? <RecipeDetails token={token} /> : <Navigate to="/login" />}
        />
        <Route path="/" element={<Navigate to={token ? "/recipes" : "/login"} />} />
      </Routes>
    </div>
  );
}

export default App;