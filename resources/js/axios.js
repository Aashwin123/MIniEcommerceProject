import axios from 'axios';

// Base axios instance for your Laravel API
const apiClient = axios.create({
    baseURL: 'http://127.0.0.1:8000/api', // your API base URL
    withCredentials: true,                // ✅ important for Sanctum
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Optional: add interceptors for error logging
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('access_token');
    
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});


export default apiClient;
