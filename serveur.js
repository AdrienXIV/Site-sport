const express = require('express');
const mysql = require('mysql');

// Cr�ation connexion
const db = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : '',
    database : 'sport'
});

// Connexion
db.connect((err) => {
    if(err) throw err;
    console.log('Connexion réussi !');
});

const app = express();
app.use(express.json());
const port = process.env.PORT || 2424;


//Inscription
app.post('/connexion/:nom/:prenom/:identifiant/:password', (req,res) => {
    let sql = `INSERT INTO identifiants (nom, prenom, identifiant, password) VALUES ('${req.params.nom}', '${req.params.prenom}', '${req.params.identifiant}', '${req.params.password}')`;
    let query = db.query(sql, (err, result) => {
        if(err) throw err;
        //console.log(result);
        console.log(JSON.stringify(result));
        res.send(JSON.stringify(result));
        
    });
});


app.listen(port, () => console.log(`Serveur lancé sur le port ${port}...`));