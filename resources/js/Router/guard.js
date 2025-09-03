// export const authGuard = (to, from, next) => {
//     const token = localStorage.getItem('access_token');
//     const userRole = localStorage.getItem('user_role');
    
//     // If no token, redirect to login
//     if (!token) {
//         next('/login');
//         return;
//     }
    
//     // If route requires admin but user is not admin
//     if (to.meta.requiresAdmin && userRole !== 'admin') {
//         next('/dashboard'); // Redirect to user dashboard
//         return;
//     }
    
//     // If everything is okay, proceed to the route
//     next();
// };