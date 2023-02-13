'use strict';

const express = require('express');
const bodyParser = require('body-parser');
const multer = require('multer');
const upload = multer();
const cors = require('cors');


const app = express();



app.use(cors("*"));
app.use(bodyParser.json());
// app.use(bodyParser.urlencoded({ extended: true }));
// app.use(upload.array());
// app.use(express.static('public'));


// Constants
const PORT = 3200;

app.get('/', (req, res) => {
    res.send('Hello World');
});

app.post('/api/student', (req, res) => {
    setTimeout(() => {
        res.send('Document are valid');
    }, 5000);
});

app.post('/api/director', (req, res) => {
    setTimeout(() => {
        res.send('Document are valid');
    }, 1000);
});

app.listen(PORT, () => {
    console.log(`Running on ${PORT}`);
});