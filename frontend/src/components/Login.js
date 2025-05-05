import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Login({ onLogin }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost:5000/api/login', { email, password });
      onLogin(response.data.token);
      navigate('/recipes');
    } catch (err) {
      setError('Invalid email or password');
    }
  };

  const handleRegister = async () => {
    try {
      await axios.post('http://localhost:5000/api/register', { email, password });
      const response = await axios.post('http://localhost:5000/api/login', { email, password });
      onLogin(response.data.token);
      navigate('/recipes');
    } catch (err) {
      setError('Email already exists');
    }
  };

  return (
    <div className="flex items-center justify-center min-h-screen">
      <div className="bg-card p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 className="text-3xl font-bold text-center mb-6 text-gray-800">Welcome to Recipe Book</h2>
        {error && <p className="text-red-500 text-center mb-4">{error}</p>}
        <div>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Email"
            className="w-full p-3 mb-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
          />
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            placeholder="Password"
            className="w-full p-3 mb-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary"
          />
          <button
            onClick={handleSubmit}
            className="w-full bg-secondary text-white p-3 rounded-lg hover:bg-yellow-500 transition"
          >
            Login
          </button>
          <button
            onClick={handleRegister}
            className="w-full mt-4 bg-primary text-gray-800 p-3 rounded-lg hover:bg-pink-200 transition"
          >
            Register
          </button>
        </div>
      </div>
    </div>
  );
}

export default Login;