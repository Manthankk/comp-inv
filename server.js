const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');
const app = express();

// Create a MySQL connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root', // Replace with your MySQL username
    password: '', // Replace with your MySQL password
    database: 'inventorymanagement',
});

// Connect to MySQL
db.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
        return;
    }
    console.log('Connected to MySQL');
});

// Configure middleware to parse JSON requests
app.use(bodyParser.json());

// API endpoint to get users from the "registration" table
app.get('/api/users', (req, res) => {
    const sql = 'SELECT name FROM registration WHERE role = "user"';
    db.query(sql, (err, results) => {
        if (err) {
            console.error('MySQL error:', err);
            res.status(500).json({ error: 'Internal server error' });
            return;
        }
        res.json(results);
    });
});

// Start the server
const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
